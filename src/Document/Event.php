<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: "events")]
class Event
{
    #[Id]
    private mixed $id;

    #[ReferenceOne(storeAs: "id", targetDocument: Game::class)]
    private mixed $game_id;

    #[Field(type: "string")]
    private mixed $name;

    #[Field(type: "string")]
    private mixed $description;

    #[Field(type: "integer")]
    private mixed $date;

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGameId(): mixed
    {
        return $this->game_id;
    }

    /**
     * @param mixed $game_id
     */
    public function setGameId(mixed $game_id): void
    {
        $this->game_id = $game_id;
    }

    /**
     * @return mixed
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(mixed $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription(): mixed
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription(mixed $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate(mixed $date): void
    {
        $this->date = $date;
    }



}