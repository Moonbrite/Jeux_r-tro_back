<?php

namespace App\Controller;

use App\Document\Game;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/api', name: 'app_game')]
class GameController extends AbstractController
{
    /**
     * @throws MongoDBException
     * @throws Throwable
     */
    #[Route('/games', name: 'app_games_add', methods: ['POST'])]
    public function addGame(Request $request, DocumentManager $dm): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $game = new Game();
        $game->setTitle($data['title']);
        $game->setPlatform($data['platform']);
        $game->setGenre($data['genre']);
        $game->setDeveloper($data['developer']);
        $game->setPublisher($data['publisher']);
        $game->setReleaseYear($data['release_year']);
        $game->setBoxCondition($data['box_condition']);
        $game->setCartridgeCondition($data['cartridge_condition']);
        $game->setPurchasePrice($data['purchase_price']);
        $game->setCollection($data['collection']);
        $game->setFavorites($data['favorites']);
        $game->setRating($data['rating']);
        $game->setStatus($data['status']);

        $dm->persist($game);
        $dm->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Game added successfully.',
            'game' => [
                'id' => $game->getId(),
                'title' => $game->getTitle()
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/games/{id}', name: 'app_games_find', methods: ['GET'])]
    public function findGame(DocumentManager $dm, string $id): JsonResponse
    {
        $game = $dm->find(Game::class, $id);

        if (!$game) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Game not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'success' => true,
            'game' => [
                'id' => $game->getId(),
                'title' => $game->getTitle(),
                'platform' => $game->getPlatform(),
                'genre' => $game->getGenre(),
                'developer' => $game->getDeveloper(),
                'publisher' => $game->getPublisher(),
                'release_year' => $game->getReleaseYear(),
                'box_condition' => $game->getBoxCondition(),
                'cartridge_condition' => $game->getCartridgeCondition(),
                'purchase_price' => $game->getPurchasePrice(),
                'collection' => $game->getCollection(),
                'favorites' => $game->getFavorites(),
                'rating' => $game->getRating(),
                'status' => $game->getStatus()
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/games', name: 'app_games_find_all', methods: ['GET'])]
    public function findAllGames(DocumentManager $dm): JsonResponse
    {
        $games = $dm->getRepository(Game::class)->findAll();

        if (!$games) {
            return new JsonResponse([
                'success' => false,
                'message' => 'No games found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $gamesJson = array_map(function (Game $game) {
            return [
                'id' => $game->getId(),
                'title' => $game->getTitle(),
                'platform' => $game->getPlatform(),
                'genre' => $game->getGenre(),
                'developer' => $game->getDeveloper(),
                'publisher' => $game->getPublisher(),
                'release_year' => $game->getReleaseYear(),
                'box_condition' => $game->getBoxCondition(),
                'cartridge_condition' => $game->getCartridgeCondition(),
                'purchase_price' => $game->getPurchasePrice(),
                'collection' => $game->getCollection(),
                'favorites' => $game->getFavorites(),
                'rating' => $game->getRating(),
                'status' => $game->getStatus()
            ];
        }, $games);

        return new JsonResponse([
            'success' => true,
            'games' => $gamesJson,
            'count' => count($gamesJson)
        ], Response::HTTP_OK);
    }

    /**
     * @throws MongoDBException
     * @throws Throwable
     */
    #[Route('/games/{id}', name: 'app_games_update', methods: ['PATCH'])]
    public function updateGame(Request $request, DocumentManager $dm, string $id): JsonResponse
    {
        $game = $dm->find(Game::class, $id);

        if (!$game) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Game not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($game, $setter)) {
                $game->$setter($value);
            }
        }

        $dm->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Game updated successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * @throws Throwable
     * @throws MongoDBException
     */
    #[Route('/games/{id}', name: 'app_games_delete', methods: ['DELETE'])]
    public function deleteGame(DocumentManager $dm, string $id): JsonResponse
    {
        $game = $dm->find(Game::class, $id);

        if (!$game) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Game not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $dm->remove($game);
        $dm->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Game deleted successfully.'
        ], Response::HTTP_OK);
    }
}
