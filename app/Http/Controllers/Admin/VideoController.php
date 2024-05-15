<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;

class VideoController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        $videos = Video::all();
        return $this->successResponse($videos, 'this is all videos');
    }

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return $this->successResponse($video, 'this is video num:' . $id);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string',
            'url' => 'required|string|max:255',
        ]);

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'course_id' => 1
        ]);
        return $this->successResponse($video, 'new video stored successfully');
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'string',
            'url' => 'required|string|max:255',
        ]);

        $video = Video::find($id);
        if (!$video) {
            return $this->errorResponse('Video not found', 404);
        }

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
        ]);
        return $this->successResponse($video, 'Video updated successfully');
    }

    public function destroy($id)
    {
        $video = Video::find($id);
        if (!$video) {
            return $this->errorResponse('Video not found', 404);
        }

        $video->delete();
        return $this->successResponse('Video deleted successfully');
    }
}
