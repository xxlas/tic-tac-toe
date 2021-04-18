<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\BoardState;
use App\Entity\Computer;
use App\Services\BoardLogicService;
use App\Services\ComputerTurnService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @var BoardLogicService
     */
    private $boardLogicService;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var ComputerTurnService
     */
    private $computerTurnService;

    /**
     * HomepageController constructor.
     */
    public function __construct(BoardLogicService $boardLogicService, FlashBagInterface $flashBag, ComputerTurnService $computerTurnService)
    {
        $this->boardLogicService = $boardLogicService;
        $this->flashBag = $flashBag;
        $this->computerTurnService = $computerTurnService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render(
            'index.html.twig');
    }

    /**
     * @Route("/homepage", name="homepage")
     */
    public function homepage(Request $req): Response
    {
        $requestFields = $req->query->all();
        $vsCPU = $requestFields['vsCPU'] ?? false;
        $cpuDifficulty = $requestFields['difficulty'] ?? -1;
        if(sizeof($requestFields) > 2) {
            $board = new Board($requestFields['size'], $requestFields['turn']);
            $boardState = new BoardState($board, $requestFields['state']);
            $message = $this->boardLogicService->calculateTurn($board, $boardState, $requestFields['selectedPos']);


            // to do implement whether the game is vs AI
            if($vsCPU == true && is_null($message) && $this->boardLogicService->checkIfGameHasAnyMovesLeft($boardState)) {
                $computerSelectedTurn = $this->computerTurnService->getWhereWillComputerMove($cpuDifficulty, $boardState, $board);
                $this->boardLogicService->calculateTurn($board, $boardState, $computerSelectedTurn);
            }
            $this->flashBag->add('warning', $message);
        } else {
            $board = new Board(3);
            $boardState = new BoardState($board);
        }

        return $this->render('homepage/index.html.twig', [
            'board' => $board,
            'state' => $boardState,
            'turn' => $board->getTurn(),
            'vsCPU' => $vsCPU,
            'difficulty' => $cpuDifficulty
        ]);
    }
}
