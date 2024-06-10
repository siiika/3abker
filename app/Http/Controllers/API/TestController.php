<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use ApiResponseTrait;

    public function Create($TestId)
    {
        $numQuestions = 10; // This variable is no longer needed if we are retrieving all questions.

        // Retrieve the test with all questions and their answers
        $test = Test::with(['questions' => function ($query) {
            $query->with(['answers' => function ($query) {
                $query->inRandomOrder();
            }])->inRandomOrder(); // This will fetch all questions in a random order.
        }])->findOrFail($TestId);

        return $this->successResponse(['test' => $test], "Now you have a test with all questions.");
    }
}