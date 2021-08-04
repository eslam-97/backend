<?php

namespace App\traits;


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
