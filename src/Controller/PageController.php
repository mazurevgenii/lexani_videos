<?php


namespace App\Controller;

use App\Entity\LexaniVideos;
use App\Form\VideoDataFormType;
use App\Modules\CsvSaver;
use App\Modules\YoutubeGrabber;
use App\Repository\LexaniVideosRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function  homepage(YoutubeGrabber $grabber, CsvSaver $save, EntityManagerInterface $em, Request $request, LexaniVideosRepository $repository) {

        if ($request->request->get('request')==='New request to Lexani'){

            $oldVideoData = $repository->findVideoDataByParseType('old');
            foreach ($oldVideoData as $oldData){
                $em->remove($oldData);
            }

            $newVideoData = $repository->findVideoDataByParseType('new');
            foreach ($newVideoData as $newData){
                $newData->setParseType('old');
            }
            $em->flush();

            $grabber->getContentsFromYouTube($em);
        }

        if ($request->request->get('clearAll')==='Clear All'){
            $dataForClear = $repository->findAll();
            foreach ($dataForClear as $data){
                $em->remove($data);
            }
            $em->flush();
        }

        $newVideoData = $repository->findVideoDataByParseType('new');
        $oldVideoData = $repository->findVideoDataByParseType('old');

        $buttonStatus = '';
        if ($oldVideoData == null) {
            $buttonStatus = 'disabled';
        }

        if ($request->request->get('save')==='Save to CSV'){

            $save->saveToCsv($newVideoData);

        } elseif ($request->request->get('saveWithCompare')==='Save to CSV with Compare'){

            $save->saveToCsvWithCompare($em);

        }

        return $this->render('parse/homepage.html.twig', ['videoData' => $newVideoData,
            'buttonStatus' => $buttonStatus]);

    }

    /**
     * @Route("/new/", name="new_data")
     */
    public function new(){
        // TODO разобраться с формами и запилить сюда добавление нового
        $form = $this->createForm(VideoDataFormType::class);

        return $this->render('parse/new.html.twig', [
            'videoDataForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="update_data")
     */
    public function update(){
        // TODO разобраться с формами и запилить сюда обновление поля
    }

    /**
     * @Route("/delete/{id}", name="delete_data")
     */
    public function delete(EntityManagerInterface $em, LexaniVideosRepository $repository, $id){
        $video = $repository->find($id);
        $em->remove($video);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}