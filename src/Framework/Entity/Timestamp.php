<?php
namespace Framework\Entity;

trait Timestamp
{

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|string|null $datetime
     */
    public function setUpdatedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->updatedAt = new \DateTime($datetime);
        } else {
            $this->updatedAt = $datetime;
        }
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|string|null $datetime
     */
    public function setCreatedAt($datetime): void
    {
        if (is_string($datetime)) {
            $this->createdAt = new \DateTime($datetime);
        } else {
            $this->createdAt = $datetime;
        }
    }
}
