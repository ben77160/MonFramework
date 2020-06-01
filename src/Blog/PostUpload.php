<?php

namespace App\Blog;

use App\Framework\Upload;

class PostUpload extends Upload
{
    protected $path = 'public/uploads/posts';

    protected $formats = [
        'thumb' => [320, 180]
    ];
}
