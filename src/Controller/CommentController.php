<?php

namespace App\Controller;

use App\Document\Comment;
use App\Document\Game;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api', name: 'app_comments')]
class CommentController extends AbstractController
{
    /**
     * @throws MappingException
     * @throws Throwable
     * @throws MongoDBException
     * @throws LockException
     */
    #[Route('/comments', name: 'add_comment', methods: ['POST'])]
    public function addComment(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validate required fields
        if (!isset($data['comment'], $data['game_id'], $data['rating'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        // Get the authenticated user
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        // Find the game
        $game = $dm->getRepository(Game::class)->find($data['game_id']);
        if (!$game) {
            return new JsonResponse(['error' => 'Game not found'], 404);
        }

        // Create and save the comment
        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setComment($data['comment']);
        $comment->setGameId($game);
        $comment->setDate(time());
        $comment->setRating($data['rating']);

        $dm->persist($comment);
        $dm->flush();

        return new JsonResponse(['success' => 'Comment added successfully']);
    }

    #[Route('/comments/{id}', name: 'app_comments_find', methods: ['GET'])]
    public function findComment(Request $request, DocumentManager $dm, string $id): JsonResponse
    {
        $comment = $dm->find(Comment::class, $id);

        if (!$comment) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Commentaire introuvable.'
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'success' => true,
            'comment' => [
                'id' => $comment->getId(),
                'author' => $comment->getAuthor()->getId(),
                'comment' => $comment->getComment(),
                'date' => $comment->getDate(),
                'game_id' => $comment->getGameId()->getId(),
                'rating' => $comment->getRating()
            ]
        ], Response::HTTP_OK);
    }

    /**
     * @throws Throwable
     * @throws MongoDBException
     */
    #[Route('/comments/{id}', name: 'app_comments_delete', methods: ['DELETE'])]
    public function deleteComment(Request $request, DocumentManager $dm, string $id, Security $security): JsonResponse
    {
        $comment = $dm->find(Comment::class, $id);

        if (!$comment) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Commentaire introuvable.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérification que l'utilisateur connecté est l'auteur du commentaire
        $user = $security->getUser(); // Obtenir l'utilisateur connecté
        if ($comment->getAuthor()->getId() !== $user->getId()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Vous ne pouvez supprimer que vos propres commentaires.'
            ], Response::HTTP_FORBIDDEN);
        }

        $dm->remove($comment);
        $dm->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès.'
        ], Response::HTTP_OK);
    }

    #[Route('/comments', name: 'app_comments_find_all', methods: ['GET'])]
    public function findAllComments(Request $request, DocumentManager $dm): JsonResponse
    {
        $comments = $dm->getRepository(Comment::class)->findAll();

        if (!$comments) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Aucun commentaire trouvé.'
            ], Response::HTTP_NOT_FOUND);
        }

        $commentsJson = array_map(function ($comment) {
            return [
                'id' => $comment->getId(),
                'author' => $comment->getAuthor()->getId(),
                'comment' => $comment->getComment(),
                'date' => $comment->getDate(),
                'game_id' => $comment->getGameId()->getId(),
                'rating' => $comment->getRating()
            ];
        }, $comments);

        return new JsonResponse([
            'success' => true,
            'comments' => $commentsJson
        ], Response::HTTP_OK);
    }

    /**
     * @throws MongoDBException
     * @throws Throwable
     */
    #[Route('/comments/{id}', name: 'app_comments_update', methods: ['PATCH'])]
    public function updateComment(Request $request, DocumentManager $dm, string $id, Security $security): JsonResponse
    {
        $comment = $dm->find(Comment::class, $id);

        if (!$comment) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Commentaire introuvable.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Vérification que l'utilisateur connecté est l'auteur du commentaire
        $user = $security->getUser(); // Obtenir l'utilisateur connecté
        if ($comment->getAuthor()->getId() !== $user->getId()) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Vous ne pouvez modifier que vos propres commentaires.'
            ], Response::HTTP_FORBIDDEN);
        }

        // Récupération des données de la requête
        $data = json_decode($request->getContent(), true);

        if (isset($data['comment'])) {
            $comment->setComment($data['comment']);
        }
        if (isset($data['date'])) {
            $comment->setDate($data['date']);
        }
        if (isset($data['rating'])) {
            $comment->setRating($data['rating']);
        }

        $dm->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Commentaire mis à jour avec succès.',
            'comment' => [
                'id' => $comment->getId(),
                'author' => $comment->getAuthor()->getId(),
                'comment' => $comment->getComment(),
                'date' => $comment->getDate(),
                'game_id' => $comment->getGameId()->getId(),
                'rating' => $comment->getRating()
            ]
        ], Response::HTTP_OK);
    }

}
