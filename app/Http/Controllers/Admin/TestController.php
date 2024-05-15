<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $tests = Test::withCount('questions')->get(['id', 'title', 'questions_count']);
        return response()->json($tests);
    }
    public function storeWithQuestions(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|integer|exists:courses,id',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.answers' => 'required|array|min:1',
            'questions.*.answers.*.text' => 'required|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the test
            $test = Test::create([
                'title' => $request->title,
                'course_id' => $request->course_id,
            ]);

            // Create questions and answers
            foreach ($request->questions as $questionData) {
                $question = Question::create([
                    'text' => $questionData['text'],
                    'test_id' => $test->id,
                ]);

                foreach ($questionData['answers'] as $index => $answerData) {
                    $isCorrect = $index === 0; // The first answer is the correct one
                    Answer::create([
                        'text' => $answerData['text'],
                        'is_correct' => $isCorrect,
                        'question_id' => $question->id,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();
            return $this->successResponse(['test' => $test], 'Test created successfully', 201);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();
            return $this->errorResponse(['message' => 'Failed to create test', 'error' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        $test = Test::findOrFail($id);

        DB::beginTransaction();

        try {
            $test->questions()->each(function ($question) {
                $question->answers()->delete();
                $question->delete();
            });

            $test->delete();

            DB::commit();
            return $this->successResponse([], 'Test deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(['message' => 'Failed to delete test', 'error' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $test = Test::select('id', 'title')->findOrFail($id);

        return $this->successResponse($test);
    }
}
