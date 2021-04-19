<?php

namespace App\Controller;

use App\Factories\BoardFactory;
use App\Factories\BoardStateFactory;
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
     * @var BoardFactory
     */
    private $boardFactory;
    /**
     * @var BoardStateFactory
     */
    private $boardStateFactory;

    /**
     * HomepageController constructor.
     */
    public function __construct(
        BoardLogicService $boardLogicService,
        FlashBagInterface $flashBag,
        ComputerTurnService $computerTurnService,
        BoardFactory $boardFactory,
        BoardStateFactory $boardStateFactory
    ) {
        $this->boardLogicService = $boardLogicService;
        $this->flashBag = $flashBag;
        $this->computerTurnService = $computerTurnService;
        $this->boardFactory = $boardFactory;
        $this->boardStateFactory = $boardStateFactory;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render(
            'index.html.twig'
        );
    }

    /**
     * @Route("/homepage", name="homepage")
     * @throws \Exception
     */
    public function homepage(Request $req): Response
    {
        $requestFields = $req->query->all();
        $vsCPU = $requestFields['vsCPU'] ?? false;
        $cpuDifficulty = $requestFields['difficulty'] ?? -1;
        if (count($requestFields) > 2) {
            $board = $this->boardFactory->createBoard($requestFields['size'], $requestFields['turn']);
            $boardState = $this->boardStateFactory->getBoardState($board, $requestFields['state']);
            $message = $this->boardLogicService->calculateTurn($board, $boardState, $requestFields['selectedPos']);

            if ((bool)$vsCPU === true && is_null($message)
                && $this->boardLogicService->checkIfGameHasAnyMovesLeft($boardState)) {
                $computerSelectedTurn =
                    $this->computerTurnService->getWhereWillComputerMove($cpuDifficulty, $boardState, $board);
                $this->boardLogicService->calculateTurn($board, $boardState, $computerSelectedTurn);
            }
            $this->flashBag->add('warning', $message);
        } else {
            $board = $this->boardFactory->createBoard(3);
            $boardState = $this->boardStateFactory->getBoardState($board);
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
