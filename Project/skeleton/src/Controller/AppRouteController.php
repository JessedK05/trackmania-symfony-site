<?php

namespace App\Controller;

use App\Entity\PlayerReplays;
use App\Entity\PlayerTracks;
use App\Entity\User;
use App\Form\PlayerReplaysFormType;
use App\Repository\PlayerReplaysRepository;
use App\Repository\PlayerTracksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppRouteController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('app_route/index.html.twig', [
            'controller_name' => 'AppRouteController',
        ]);
    }
    #[Route('/players', name: 'app_players_overview')]
    public function playersoverview(): Response
    {
        return $this->render('app_route/players-overview.html.twig', [
            'controller_name' => 'AppRouteController',
        ]);
    }
    #[Route('/tracks', name: 'app_track_type')]
    public function choosetracks(): Response
    {
        return $this->render('app_route/track-selection.html.twig', [
            'controller_name' => 'AppRouteController',
        ]);
    }
    #[Route('/tracks/campaign', name: 'app_tracks_campaign')]
    public function trackoverviewcampaign(): Response
    {
        return $this->render('app_route/tracks-overview-campaign.html.twig', [
            'controller_name' => 'AppRouteController',
        ]);
    }
    #[Route('/tracks/player-made', name: 'app_tracks_player')]
    public function trackoverviewplayers(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager -> getRepository(PlayerTracks::class);
        $tracks = $repository->findAll();

        return $this->render('app_route/tracks-overview-playermade.html.twig', [
            'tracks' => $tracks,
        ]);
    }
    #[Route('/tracks/player-made/{id}', name: 'app_tracks_player_unique')]
    public function trackoverviewplayersunique($id, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager, PlayerReplaysRepository $playerReplaysRepository): Response
    {
        $repository = $entityManager -> getRepository(PlayerTracks::class);
        $track = $repository->find($id);
        $replaystodisplay = $playerReplaysRepository->findBY([], ['TimeSet' => 'ASC']);

        $replay = new PlayerReplays();
        $form = $this->createForm(PlayerReplaysFormType::class, $replay);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var UploadedFile $replayfile */
            $replayfile = $form->get('Replay')->getData();

            if ($replayfile) {
                $originalFilename = pathinfo($replayfile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$replayfile->guessExtension();

                try {
                    $replayfile->move(
                        $this->getParameter('player_replays_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                $replay->setReplay($newFilename);
            }
            $replay->setUser(
                $this->getUser()
            );
            $replay->setTimeSet(
                $form->get('TimeSet')->getData()

            );
            $replay->setTrack(
                $track
            );

            $entityManager->persist($replay);
            $entityManager->flush();

            return $this->redirectToRoute('app_tracks_player_unique', ['id' => $track->getId()]);
        }


        return $this->render('app_route/track-page.html.twig', [
            'ReplayForm' => $form->createView(),
            'track' => $track,
            'replays' => $replaystodisplay
        ]);
    }
    #[Route('/profile/{id}', name: 'app_profile')]
    public function profile($id,  EntityManagerInterface $entityManager, PlayerTracksRepository $playerTracksRepository): Response
    {
        $repository = $entityManager -> getRepository(User::class);
        $user = $repository->find($id);
        $trackstodisplay = $playerTracksRepository->findAll();

        return $this->render('app_route/profilepage.html.twig', [
            'user' => $user,
            'tracks' => $trackstodisplay,
        ]);
    }
}
