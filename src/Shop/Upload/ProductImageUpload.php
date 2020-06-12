<?php
namespace App\Shop\Upload;

use Framework\Upload;

class ProductImageUpload extends Upload
{

    protected $path = "public/uploads/products";

    protected $formats = [
        'thumb' => [320, 180]
    ];
}
