<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

class MediaService extends BaseService
{
    private Media $model;

    public function __construct(Media $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function storeImage(UploadedFile $image)
    {
        $filename = time() . '_asset.' . $image->extension();
        $publicPath = public_path() . '/uploads/images';
        $urlPath = url()->to('/') . '/uploads/images';

        $data = [
            'path' => $urlPath,
            'filename' => $filename,
            'mime' => $image->getMimeType(),
            'status' => Constants::STATUS_INACTIVE,
            'expires_at' => now()
        ];

        $image->move($publicPath, $filename);

        return $this->create($data);
    }

    public function activateImage(Media $media)
    {
        $media->status = Constants::STATUS_ACTIVE;
        $media->expires_at = null;
        return $media->save();
    }
}
