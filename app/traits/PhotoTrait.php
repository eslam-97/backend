<?php

namespace App\traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait photoTrait
{
    public function savePhoto($photo, $path)
    {
        $extenstion = $photo->getClientOriginalExtension();
        $file_name =$path.'/'. time() . '.' . $extenstion;
        $photo->move($path, $file_name);
        return $file_name;
    }
}
