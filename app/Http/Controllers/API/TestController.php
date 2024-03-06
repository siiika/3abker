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
        $numQuestions = 2;
        $test = Test::with(['questions' => function ($query) use ($numQuestions) {
            $query->with('answers')->take($numQuestions);
        }])->findOrFail($TestId);
        return $this->successResponse(['test' => $test], "now you have atest with $numQuestions queations");
    }
}
