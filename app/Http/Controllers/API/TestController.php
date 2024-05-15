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
        $numQuestions = 10;

        // Retrieve the test with a limited number of questions and their answers
        $test = Test::with(['questions' => function ($query) use ($numQuestions) {
            $query->with(['answers' => function ($query) {
                $query->inRandomOrder();
            }])->inRandomOrder()->take($numQuestions);
        }])->findOrFail($TestId);

        return $this->successResponse(['test' => $test], "Now you have a test with up to $numQuestions questions.");
    }
}
