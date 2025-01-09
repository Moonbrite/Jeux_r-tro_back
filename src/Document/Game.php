<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\EmbedMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;

#[Document(collection: "games")]
class Game
{
    #[Id]
    private $id;

    #[Field(type: "string")]
    private $title;


    #[Field(type: "string")]
    private $platform;

    #[Field(type: "string")]
    private $genre;

    #[Field(type: "string")]
    private $developer;

    #[Field(type: "string")]
    private $publisher;

    #[Field(type: "integer")]
    private $release_year;

    #[Field(type: "string")]
    private $box_condition;

    #[Field(type: "string")]
    private $cartridge_condition;

    #[Field(type: "float")]
    private $purchase_price;

    #[Field(type: "boolean")]
    private $collection;

    #[Field(type: "boolean")]
    private $favorites;

    #[Field(type: "float")]
    private $rating;

    #[Field(type: "string")]
    private $status;

    #[EmbedMany(targetDocument: Loan::class)]
    private $loans = [];

    #[EmbedMany(targetDocument: Event::class)]
    private $events = [];

    #[EmbedMany(targetDocument: Event::class)]
    private $comments = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param mixed $platform
     */
    public function setPlatform($platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * @param mixed $developer
     */
    public function setDeveloper($developer): void
    {
        $this->developer = $developer;
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param mixed $publisher
     */
    public function setPublisher($publisher): void
    {
        $this->publisher = $publisher;
    }

    /**
     * @return mixed
     */
    public function getReleaseYear()
    {
        return $this->release_year;
    }

    /**
     * @param mixed $release_year
     */
    public function setReleaseYear($release_year): void
    {
        $this->release_year = $release_year;
    }

    /**
     * @return mixed
     */
    public function getBoxCondition()
    {
        return $this->box_condition;
    }

    /**
     * @param mixed $box_condition
     */
    public function setBoxCondition($box_condition): void
    {
        $this->box_condition = $box_condition;
    }

    /**
     * @return mixed
     */
    public function getCartridgeCondition()
    {
        return $this->cartridge_condition;
    }

    /**
     * @param mixed $cartridge_condition
     */
    public function setCartridgeCondition($cartridge_condition): void
    {
        $this->cartridge_condition = $cartridge_condition;
    }

    /**
     * @return mixed
     */
    public function getPurchasePrice()
    {
        return $this->purchase_price;
    }

    /**
     * @param mixed $purchase_price
     */
    public function setPurchasePrice($purchase_price): void
    {
        $this->purchase_price = $purchase_price;
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param mixed $collection
     */
    public function setCollection($collection): void
    {
        $this->collection = $collection;
    }

    /**
     * @return mixed
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * @param mixed $favorites
     */
    public function setFavorites($favorites): void
    {
        $this->favorites = $favorites;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getLoans(): array
    {
        return $this->loans;
    }

    public function setLoans(array $loans): void
    {
        $this->loans = $loans;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function setEvents(array $events): void
    {
        $this->events = $events;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }



}