<?php

namespace App\Library;

use Storage;

class Image {

    public function storePostImage ($file) {
        return substr_replace(($file->store('public/posts')), 'storage', 0, 6);
    }

    public function deleteImage ($path) {
        return Storage::delete(substr_replace($path, 'public', 0, 7));
    }

}
