<?php

namespace App\Services;

use Storage;
use App\Image;

class ImageService {

    public function save ($file, $model) {
        $image = new Image;
        $image->src = substr_replace(($file->store('public/images')), 'storage', 0, 6);
        $image->save();
        $model->images()->save($image);

        return $image;
    }

//    public function storePostImage ($file) {
//        return substr_replace(($file->store('public/posts')), 'storage', 0, 6);
//    }
//
//    public function storeUserImage ($file) {
//        return substr_replace(($file->store('public/users')), 'storage', 0, 6);
//    }
//
//    public function deleteImage ($path) {
//        return Storage::delete(substr_replace($path, 'public', 0, 7));
//    }

}
