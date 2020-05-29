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


    public function setCreatedAt($dateTime)
    {
        if (is_string($dateTime)) {
            $this->createdAt = new \DateTime($dateTime);
        }
    }

    public function setUpdatedAt($dateTime)
    {
        if (is_string($dateTime)) {
            $this->updatedAt = new \DateTime($dateTime);
        }
    }
}
