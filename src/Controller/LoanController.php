<?php

namespace App\Controller;

use App\Document\Loan;
use App\Document\Game;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api', name: 'loan_')]
class LoanController extends AbstractController
{
    // Ajouter un prêt
    /**
     * @throws MappingException
     * @throws Throwable
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/loans', name: 'add_loan', methods: ['POST'])]
    public function addLoan(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['game_id'], $data['loan_date'], $data['return_date'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        $game = $dm->getRepository(Game::class)->find($data['game_id']);
        if (!$game) {
            return new JsonResponse(['error' => 'Game not found'], 404);
        }

        $loan = new Loan();
        $loan->setGameId($game);
        $loan->setBorrower($user);
        $loan->setLoanDate($data['loan_date']);
        $loan->setReturnDate($data['return_date']);
        $loan->setComment($data['comment'] ?? '');

        $dm->persist($loan);
        $dm->flush();

        return new JsonResponse(['success' => 'Loan created successfully', 'loan_id' => $loan->getId()], 201);
    }

    // Récupérer tous les prêts
    #[Route('/loans', name: 'get_all_loans', methods: ['GET'])]
    public function getAllLoans(DocumentManager $dm): JsonResponse
    {
        $loans = $dm->getRepository(Loan::class)->findAll();

        $loanData = array_map(function (Loan $loan) {
            return [
                'id' => $loan->getId(),
                'game_id' => $loan->getGameId()->getId(),
                'borrower' => $loan->getBorrower()->getId(),
                'loan_date' => $loan->getLoanDate(),
                'return_date' => $loan->getReturnDate(),
                'comment' => $loan->getComment(),
            ];
        }, $loans);

        return new JsonResponse($loanData, 200);
    }

    // Récupérer un prêt par ID
    #[Route('/loans/{id}', name: 'get_loan', methods: ['GET'])]
    public function getLoan(string $id, DocumentManager $dm): JsonResponse
    {
        $loan = $dm->getRepository(Loan::class)->find($id);

        if (!$loan) {
            return new JsonResponse(['error' => 'Loan not found'], 404);
        }

        $loanData = [
            'id' => $loan->getId(),
            'game_id' => $loan->getGameId()->getId(),
            'borrower' => $loan->getBorrower()->getId(),
            'loan_date' => $loan->getLoanDate(),
            'return_date' => $loan->getReturnDate(),
            'comment' => $loan->getComment(),
        ];

        return new JsonResponse($loanData, 200);
    }

    // Mettre à jour un prêt

    /**
     * @throws Throwable
     * @throws MappingException
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/loans/{id}', name: 'update_loan', methods: ['PUT'])]
    public function updateLoan(string $id, Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $loan = $dm->getRepository(Loan::class)->find($id);
        if (!$loan) {
            return new JsonResponse(['error' => 'Loan not found'], 404);
        }

        if (isset($data['loan_date'])) {
            $loan->setLoanDate($data['loan_date']);
        }
        if (isset($data['return_date'])) {
            $loan->setReturnDate($data['return_date']);
        }
        if (isset($data['comment'])) {
            $loan->setComment($data['comment']);
        }

        $dm->flush();

        return new JsonResponse(['success' => 'Loan updated successfully'], 200);
    }

    // Supprimer un prêt

    /**
     * @throws Throwable
     * @throws MappingException
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/loans/{id}', name: 'delete_loan', methods: ['DELETE'])]
    public function deleteLoan(string $id, DocumentManager $dm): JsonResponse
    {
        $loan = $dm->getRepository(Loan::class)->find($id);
        if (!$loan) {
            return new JsonResponse(['error' => 'Loan not found'], 404);
        }

        $dm->remove($loan);
        $dm->flush();

        return new JsonResponse(['success' => 'Loan deleted successfully'], 200);
    }
}

