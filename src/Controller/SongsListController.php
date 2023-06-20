<?php

namespace App\Controller;
use App\Entity\Song;
use App\Entity\SongsList;
use App\Form\SongsListType;
use App\Repository\SongsListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
#[Route('/songslist')]
class SongsListController extends AbstractController
{
    #[Route('/', name: 'app_songs_list_index', methods: ['GET'])]
    public function index(SongsListRepository $songsListRepository): Response
    {
        return $this->render('songs_list/index.html.twig', [
            'songs_lists' => $songsListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_songs_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SongsListRepository $songsListRepository): Response
    {
        $songsList = new SongsList();
        $form = $this->createForm(SongsListType::class, $songsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $songsList->setIdUser($this->getUser());
            $songsList->setTotalDuration(new DateTime());
            $songsListRepository->save($songsList, true);

            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('songs_list/new.html.twig', [
            'songs_list' => $songsList,
            'form' => $form,
        ]);
    }

    // #[Route('/song/add-to-list', name: 'song_add_to_list')]
    // public function addToList(Request $request): Response
    // {
    //     $song = new Song();
    //     $form = $this->createForm(AddSongToList::class, $song);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Realiza el procesamiento de la canción y la lista aquí
    //         // Por ejemplo, guarda la canción en la lista seleccionada
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($song);
    //         $em->flush();

    //         // Redirige a una página de éxito o realiza otra acción necesaria
    //         return $this->redirectToRoute('app_main');
    //     }

    //     return $this->render('song/add_to_list.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }


    #[Route('/{id}', name: 'app_songs_list_show', methods: ['GET'])]
    public function show(SongsList $songsList): Response
    {
        return $this->render('songs_list/show.html.twig', [
            'songs_list' => $songsList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_songs_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SongsList $songsList, SongsListRepository $songsListRepository): Response
    {
        $form = $this->createForm(SongsListType::class, $songsList);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener la canción seleccionada del formulario
           
            foreach ($songsList->getSong() as $song) {
                // Agregar la canción a la lista de reproducción
                $songsList->addSong($song);
            }
            
            // Guardar los cambios en la base de datos
            $songsListRepository->save($songsList,true);
            
            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('songs_list/edit.html.twig', [
            'songs_list' => $songsList,
            'form' => $form,
        ]);
    
   

    return $this->renderForm('songs_list/edit.html.twig', [
        'songs_list' => $songsList,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_songs_list_delete', methods: ['POST'])]
    public function delete(Request $request, SongsList $songsList, SongsListRepository $songsListRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$songsList->getId(), $request->request->get('_token'))) {
            $songsListRepository->remove($songsList, true);
        }

        return $this->redirectToRoute('app_songs_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
