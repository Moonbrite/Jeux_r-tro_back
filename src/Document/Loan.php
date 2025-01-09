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

    #[Field(type: "string")]
    private $borrower_name;

    #[Field(type: "string")]
    private $loan_date;

    #[Field(type: "string")]
    private $return_date;

    #[Field(type: "string")]
    private $comment;

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
    public function getGameId()
    {
        return $this->game_id;
    }

    /**
     * @param mixed $game_id
     */
    public function setGameId($game_id): void
    {
        $this->game_id = $game_id;
    }

    /**
     * @return mixed
     */
    public function getBorrowerName()
    {
        return $this->borrower_name;
    }

    /**
     * @param mixed $borrower_name
     */
    public function setBorrowerName($borrower_name): void
    {
        $this->borrower_name = $borrower_name;
    }

    /**
     * @return mixed
     */
    public function getLoanDate()
    {
        return $this->loan_date;
    }

    /**
     * @param mixed $loan_date
     */
    public function setLoanDate($loan_date): void
    {
        $this->loan_date = $loan_date;
    }

    /**
     * @return mixed
     */
    public function getReturnDate()
    {
        return $this->return_date;
    }

    /**
     * @param mixed $return_date
     */
    public function setReturnDate($return_date): void
    {
        $this->return_date = $return_date;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }




}