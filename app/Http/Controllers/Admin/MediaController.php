<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ImageRequest;
use App\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    private MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    public function storeImage(ImageRequest $request)
    {
        $image = $request->file('image');
        return response()->json([
            'message' => 'Successfully uploaded image',
            'data' => $this->service->storeImage($image)
        ]);
    }
}
