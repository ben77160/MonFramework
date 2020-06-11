<?php

namespace App\Blog\Entity;

class Post
{

    public $id;

    public $name;

    public $slug;

    public $content;

    public $createdAt;

    public $updatedAt;

    public $image;

    public function setCreatedAt($datetime)
    {
        if (is_string($datetime)) {
            $this->createdAt = new \DateTime($datetime);
        } else {
            $this->createdAt = $datetime;
        }
    }

    public function setUpdatedAt($datetime)
    {
        if (is_string($datetime)) {
            $this->updatedAt = new \DateTime($datetime);
        } else {
            $this->updatedAt = $datetime;
        }
    }

    public function getThumb()
    {
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->image);
        return '/uploads/posts/' . $filename . '_thumb.' . $extension;
    }

    public function getImageUrl()
    {
        return '/uploads/posts/' . $this->image;
    }
}
