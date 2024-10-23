<?php

namespace App\Controller;

use App\Entity\PlayerTracks;
use App\Form\PlayerTracksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PlayerTrackController extends AbstractController
{

    #[Route('/tracks/upload', name: 'app_tracks_upload')]
    public function trackuploader(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {
            $upload = new PlayerTracks();
            $form = $this->createForm(PlayerTracksType::class, $upload);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $replayFile */
                $replayFile = $form->get('Replay')->getData();

                if ($replayFile) {
                    $originalFilename = pathinfo($replayFile->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$replayFile->guessExtension();

                    try {
                        $replayFile->move(
                            $this->getParameter('replays_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                    }

                    $upload->setReplay($newFilename);
                }

                $imageFile = $form->get('Image')->getData();

                if ($imageFile) {
                    $originalImageFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeImageFilename = $slugger->slug($originalImageFilename);
                    $newImageFilename = $safeImageFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newImageFilename
                        );
                    } catch (FileException $e) {

                    }
                    $upload->setImage($newImageFilename);
                }

                $upload->setTitle(
                    $form->get('title')->getData()
                );
                $upload->setAuthorTime(
                    $form->get('AuthorTime')->getData()
                );
                $upload->setDescription(
                    $form->get('description')->getData()
                );
                $upload->setDifficulty(
                    $form->get('Difficulty')->getData()
                );
                $upload->setTracktype(
                    $form->get('tracktype')->getData()
                );
                $upload->setAuthor(
                    $this->getUser()
                );

                $entityManager->persist($upload);
                $entityManager->flush();


                return $this->redirectToRoute('app_tracks_player');
            }

        return $this->render('app_route/upload.html.twig', [
            'uploadTrackForm' => $form->createView(),
        ]);
    }
}
