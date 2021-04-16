<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\BoardState;
use App\Services\BoardLogicService;
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
     * HomepageController constructor.
     */
    public function __construct(BoardLogicService $boardLogicService, FlashBagInterface $flashBag)
    {
        $this->boardLogicService = $boardLogicService;
        $this->flashBag = $flashBag;
    }


    /**
     * @Route("/homepage", name="homepage")
     */
    public function index(Request $req): Response
    {
        $requestFields = $req->query->all();
        $message = null;
        if(sizeof($requestFields) > 0) {
            $board = new Board($requestFields['size'], $requestFields['turn']);
            $boardState = new BoardState($board, $requestFields['state']);
            $message = $this->boardLogicService->calculateTurn($board, $boardState, $requestFields['selectedPos']);
            $this->flashBag->add('warning', $message);
        } else {
            $board = new Board(3);
            $boardState = new BoardState($board);
        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'board' => $board,
            'state' => $boardState,
            'turn' => $board->getTurn(),
        ]);
    }
}
