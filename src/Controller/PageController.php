<?php


namespace App\Controller;

use App\Entity\LexaniVideos;
use App\Entity\UserParameters;
use App\Form\VideoDataFormType;
use App\Modules\CsvSaver;
use App\Modules\YoutubeGrabber;
use App\Repository\LexaniVideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    private $grabber;
    private $save;
    private $em;
    private $repository;

    public function __construct(YoutubeGrabber $grabber, CsvSaver $save, EntityManagerInterface $em, LexaniVideosRepository $repository)
    {
        $this->grabber    = $grabber;
        $this->save       = $save;
        $this->em         = $em;
        $this->repository = $repository;
    }

    /**
     * @return YoutubeGrabber
     */
    private function getGrabber(): YoutubeGrabber
    {
        return $this->grabber;
    }

    /**
     * @return CsvSaver
     */
    public function getSave(): CsvSaver
    {
        return $this->save;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @return LexaniVideosRepository
     */
    public function getRepository(): LexaniVideosRepository
    {
        return $this->repository;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request)
    {
        if ($request->request->get('request') === 'New request to Lexani') {

            $oldVideoData = $this->getRepository()->findOldVideoData();
            foreach ($oldVideoData as $oldData) {
                $this->getEm()->remove($oldData);
            }

            $newVideoData = $this->getRepository()->findNewVideoData();
            foreach ($newVideoData as $newData) {
                $newData->setParseType('old');
            }
            $this->getEm()->flush();

            $this->getGrabber()->getContentsFromYouTube($this->getEm());
        }

        if ($request->request->get('clearAll') === 'Clear All') {
            $dataForClear = $this->getRepository()->findAll();
            foreach ($dataForClear as $data) {
                $this->getEm()->remove($data);
            }
            $this->getEm()->flush();
        }

        $newVideoData = $this->getRepository()->findNewVideoData();
        $oldVideoData = $this->getRepository()->findOldVideoData();

        if ($request->request->get('save') === 'Save to CSV') {

            $this->getSave()->saveToCsv($this->getRepository());

        } elseif ($request->request->get('saveWithCompare') === 'Save to CSV with Compare') {

            $this->getSave()->saveToCsvWithCompare($this->getRepository());

        }

        $buttonStatus = '';
        if (empty($oldVideoData)) {
            $buttonStatus = 'disabled';
        }

        return $this->render('parse/homepage.html.twig', ['videoData' => $newVideoData,
            'buttonStatus' => $buttonStatus]);

    }

    /**
     * @Route("/new/", name="new_data")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(VideoDataFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            $this->getEm()->persist($data);
            $this->getEm()->flush();
            $this->addFlash('success', 'Field added to data');



            return $this->redirectToRoute('homepage');
        }

        return $this->render('parse/new.html.twig', [
            'videoDataForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="update_data")
     */
    public function update(Request $request, LexaniVideos $videoData)
    {
        $form = $this->createForm(VideoDataFormType::class, $videoData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($videoData);
            $this->getEm()->flush();

            $this->addFlash('success', 'Field Updated');

            return $this->redirectToRoute('homepage', [
                'id' => $videoData->getId(),
            ]);
        }

        return $this->render('parse/edit.html.twig', [
            'videoDataForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_data")
     */
    public function delete($id)
    {
        $video = $this->getRepository()->find($id);
        $this->getEm()->remove($video);
        $this->getEm()->flush();

        return $this->redirectToRoute('homepage');
    }
}