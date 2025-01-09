<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: "loans")]
class Loan
{
    #[Id]
    private $id;

    #[ReferenceOne(storeAs: "id", targetDocument: Game::class)]
    private $game_id;

    #[ReferenceOne(storeAs: "id", targetDocument: User::class)]
    private $borrower;

    #[Field(type: "string")]
    private $loan_date;

    #[Field(type: "string")]
    private $return_date;

    #[Field(type: "string")]
    private $comment;

    public function getId()
    {
        return $this->id;
    }

    public function getGameId(): ?Game
    {
        return $this->game_id;
    }

    public function setGameId(Game $game_id): void
    {
        $this->game_id = $game_id;
    }

    public function getBorrower(): ?User
    {
        return $this->borrower;
    }

    public function setBorrower(User $borrower): void
    {
        $this->borrower = $borrower;
    }

    public function getLoanDate(): ?string
    {
        return $this->loan_date;
    }

    public function setLoanDate(string $loan_date): void
    {
        $this->loan_date = $loan_date;
    }

    public function getReturnDate(): ?string
    {
        return $this->return_date;
    }

    public function setReturnDate(string $return_date): void
    {
        $this->return_date = $return_date;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
