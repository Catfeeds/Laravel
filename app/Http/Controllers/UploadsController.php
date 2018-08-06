<?php

namespace App\Http\Controllers;

use App\Handlers\FileUploadHandler;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UploadRequest;
use App\Models\Upload;
use App\Transformers\ImageTransformer;

class UploadsController extends Controller
{
    public function store(UploadRequest $request, Upload $upload)
    {
        $user = $this->user();
        if($request->type === 'avatar' || $request->type === 'activity_photo') {
            $maxWidth = $request->type == 'avatar' ? 362 : 1024;
            $result = (new ImageUploadHandler())->save($request->file, str_plural($request->type), $user->id, $maxWidth);
        } else {
            $result = (new FileUploadHandler())->save($request->file, str_plural($request->type), $user->id);
        }
        $upload->path = $result['path'];
        $upload->type = $request->type;
        $upload->user_id = $user->id;
        $upload->save();
        return $this->response->item($upload, new ImageTransformer())->setStatusCode(201);
    }
}
