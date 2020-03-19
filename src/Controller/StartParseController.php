<?php

namespace App\Controller;

use App\Modules\YoutubeGrabber;
use App\Repository\LexaniVideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StartParseController extends AbstractController
{
    private $grabber;
    private $em;
    private $repository;

    public function __construct(YoutubeGrabber $grabber, EntityManagerInterface $em, LexaniVideosRepository $repository)
    {
        $this->grabber = $grabber;
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @Route("/start/", name="lexani")
     */
    public function getDataFromLexani(Request $request)
    {
        if ($request->request->get('request') === 'New request to Lexani') {

            $oldVideoData = $this->repository->findOldVideoData();
            foreach ($oldVideoData as $oldData) {
                $this->em->remove($oldData);
            }

            $newVideoData = $this->repository->findNewVideoData();
            foreach ($newVideoData as $newData) {
                $newData->setParseType('old');
            }

            $ip = $request->server->get('REMOTE_ADDR');
            $browser = $request->server->get('HTTP_USER_AGENT');

            $this->grabber->getContentsFromYouTube($ip, $browser);
        }

        return $this->redirectToRoute('homepage');
    }
}