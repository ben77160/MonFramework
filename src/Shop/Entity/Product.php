<?php
namespace App\Shop\Entity;

use Framework\Entity\Timestamp;

class Product
{

    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $slug;

    /**
     * @var float|null
     */
    private $price;

    /**
     * @var string|null
     */
    private $image;

    use Timestamp;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice($price): void
    {
        if (is_string($price)) {
            $price = floatval($price);
        }
        $this->price = $price;
    }

    /**
     * @return null|string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param null|string $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getThumb()
    {
        ['filename' => $filename, 'extension' => $extension] = pathinfo($this->image);
        return '/uploads/products/' . $filename . '_thumb.' . $extension;
    }

    public function getImageUrl()
    {
        return '/uploads/products/' . $this->image;
    }

    public function getPdf()
    {
        return "{$this->id}.pdf";
    }
}
