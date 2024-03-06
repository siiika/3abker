<?php

namespace App\Http\Controllers\API;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\UserTestAnswers;
use App\Traits\ApiResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class UserTestAnswerController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request, $userTestId)
    {
        //Check if the user has already taken the test
        $userTest = UserTestAnswers::where('user_id', auth()->id())
            ->where('test_id', $userTestId)
            ->first();

        if ($userTest) {
            // Delete existing user test answers for the specified test ID
            UserTestAnswers::where('test_id', $userTestId)
                ->where('user_id', auth()->id())
                ->delete();
        }


        $validatedData = $request->validate([
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id' => 'required|exists:answers,id',
        ]);

        $userTestAnswers = [];

        foreach ($validatedData['answers'] as $answer) {
            $userTestAnswers[] = [
                'user_id' => auth()->id(),
                'test_id' => $userTestId,
                'question_id' => $answer['question_id'],
                'answer_id' => $answer['answer_id']
            ];
        }

        UserTestAnswers::insert($userTestAnswers);

        // Redirect the user to the show route for the user test
        return redirect()->route('user-test.show', ['userTestId' => $userTestId]);
    }
    public function show($userTestId)
    {

        $userTestAnswers = UserTestAnswers::where('test_id', $userTestId)->where('user_id', auth()->id())->get();

        // Calculate the percentage by calling the calculatePercentage method
        $percentage = $this->calculatePercentage($userTestId);

        // Return a JSON response containing the user test answers and the calculated percentage

        return $this->successResponse(['percentage' => $percentage, 'user_test_answers' => $userTestAnswers], 'user test result');
        return response()->json([
            'user_test_answers' => $userTestAnswers,
            'percentage' => $percentage
        ]);
    }
    public function calculatePercentage($userTestId)
    {
        $userTestAnswers = UserTestAnswers::where('test_id', $userTestId)->where('user_id', auth()->id())->get();

        // Get total number of questions in the test
        $totalQuestions = Question::where('test_id', $userTestId)->count();

        // Calculate number of correct answers given by the user
        $correctAnswers = $userTestAnswers->filter(function ($userTestAnswer) {
            return $userTestAnswer->answer->is_correct;
        })->count();

        // Calculate percentage
        $percentage = ($correctAnswers / $totalQuestions) * 100;

        return $percentage;
    }
}
