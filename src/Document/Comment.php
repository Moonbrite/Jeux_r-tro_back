<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: "comments")]
class Comment
{
    #[Id]
    private $id;

    #[ReferenceOne(storeAs: "id", targetDocument: User::class)]
    private $author;

    #[Field(type: "string")]
    private $comment;

    #[Field(type: "integer")]
    private $date;

    #[ReferenceOne(storeAs: "id", targetDocument: Game::class)]
    private $game_id;

    #[Field(type: "integer")]
    private $rating;

    public function getId()
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    public function getGameId(): ?Game
    {
        return $this->game_id;
    }

    public function setGameId(Game $game_id): void
    {
        $this->game_id = $game_id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }
}
