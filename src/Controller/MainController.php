<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SongsListRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    private $songsListRepository;

    public function __construct(SongsListRepository $songsListRepository)
    {
        $this->songsListRepository = $songsListRepository;
    }

    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        $user = $this->getUser();
        $currentUserLists = [];

 
        
        if ($user) {
            $currentUserLists = $this->songsListRepository->findByCurrentUser();

            foreach ($currentUserLists as $list) {
                $totalDuration = 0;
                foreach ($list->getSong() as $song) {
                    $totalDuration += $song->getDuration()->getTimestamp();
                }
                $list->setTotalDuration(new \DateTime("@$totalDuration"));
            }
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'current_user_lists' => $currentUserLists,
        ]);
    }
}