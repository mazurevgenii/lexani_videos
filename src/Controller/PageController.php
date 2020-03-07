<?php


namespace App\Controller;

use App\Entity\LexaniVideos;
use App\Modules\CsvSaver;
use App\Modules\YoutubeGrabber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function  homepage(YoutubeGrabber $yg, CsvSaver $save, EntityManagerInterface $em, Request $request) {

        $repository = $em->getRepository(LexaniVideos::class);
        /** @var LexaniVideos $articles */
        $videoData = $repository->findAll();
        /* if (!$videoData) {
            throw $this->createNotFoundException(sprintf('No article for slug"%s"', $slug));
        }*/

        if ($request->request->get('request')==='New request to Lexani'){

            $yg->getContentsFromYouTube($em);

        } elseif ($request->request->get('save')==='Save to CSV'){

            $save->saveToCsv($videoData);

        } elseif ($request->request->get('saveWithCompare')==='Save to CSV with Compare'){

            return new Response(var_dump($request->request->get('saveWithCompare')));

        }

        //

        return $this->render('parse/homepage.html.twig', ['videoData' => $videoData]);

    }
}