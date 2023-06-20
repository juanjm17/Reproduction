<?php

namespace App\Controller;

use App\Entity\Song;
use App\Entity\User;
use App\Form\SongType;

use App\Repository\SongRepository;
use App\Repository\SongListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/song')]
class SongController extends AbstractController
{

    
    #[Route('/', name: 'app_song_index', methods: ['GET'])]
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('song/index.html.twig', [
            'songs' => $songRepository->findAll(),
        ]);
    }

    #[Route('/song/search', name: 'app_song_search')]
    public function search(Request $request, SongRepository $songRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $songs = $songRepository->findByAuthorOrTitle($searchTerm);
        
        return $this->render('song/songs.html.twig', [
            'songs' => $songs,
        ]);
    }



    #[Route('/allsongs', name: 'app_song_songs', methods: ['GET'])]
    public function songList(SongRepository $songRepository): Response
    {
        return $this->render('song/songs.html.twig', [
            'songs' => $songRepository->findAll(),
        ]);
    }

    #[Route('/new/', name: 'app_song_new', methods: ['GET', 'POST'])]
    public function new(User $user,Request $request, SongRepository $songRepository): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song->setUser($this->getUser());
            $songRepository->save($song, true);

            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('song/new.html.twig', [
            'song' => $song,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_song_show', methods: ['GET'])]
    public function show(Song $song): Response
    {
        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    
    // #[Route('/{id}/edit/{userId}', name: 'app_song_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Song $song, SongRepository $songRepository,SongListRepository $songListRepository): Response
    // {
    // //     $id = $request->attributes->get('userId');
    // //    $songsList =$songListRepository->find($id);
    //     $form = $this->createForm(SongAddType::class, $song);
    //     $form->handleRequest($request);
    //     // var_dump($songListRepository->findByUserId($id));
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Obtener las listas de canciones asociadas a la canción
    //         $songsLists = $song->getSongsLists();
    //        $userLists =  $this->getUser()->getSongsLists;
    //         // Hacer algo con las listas de canciones, como guardar cambios, etc.
    //         // Por ejemplo, asignar la canción a una lista existente
    //         foreach ($songsLists as $songsList) {
    //             // Agregar la canción a la lista
    //             $userLists->addSong($song);
    //         }
    
    //         // Guardar la canción y las listas de canciones
    //         $songRepository->save($song, true);
    
    //         return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    //     }
    
    //     return $this->renderForm('song/addsong.html.twig', [
    //         'song' => $song,
    //         'form' => $form,
            
    //     ]);
    // }
    #[Route('/{id}', name: 'app_song_delete', methods: ['POST'])]
    public function delete(Request $request, Song $song, SongRepository $songRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {
            $songRepository->remove($song, true);
        }

        return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
    }
}
