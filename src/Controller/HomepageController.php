<?php

namespace App\Controller;

use App\Entity\LexaniVideos;
use App\Entity\UserParameters;
use App\Form\VideoDataFormType;
use App\Repository\LexaniVideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends AbstractController
{
    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em, LexaniVideosRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage(Request $request)
    {
        if ($request->request->get('clearAll') === 'Clear All') {
            $dataForClear = $this->repository->findAll();
            foreach ($dataForClear as $data) {
                $this->em->remove($data);
            }
            $this->em->flush();
        }

        $newVideoData = $this->repository->findAll();
        $oldVideoData = $this->repository->findOldVideoData();

        $buttonStatus = '';
        if (empty($oldVideoData)) {
            $buttonStatus = 'disabled';
        }

        return $this->render('parse/homepage.html.twig', [
            'videoData' => $newVideoData,
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
            $videoData = $form->getData();
            $this->em->persist($videoData);

            $this->setUserParameters($request, $videoData);

            $this->em->flush();
            $this->addFlash('success', 'Field added to data successfully');

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
            $this->em->persist($videoData);

            $this->setUserParameters($request, $videoData);

            $this->em->flush();

            $this->addFlash('success', 'Field updated successfully');

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
        $video = $this->repository->find($id);
        $this->em->remove($video);
        $this->em->flush();

        $this->addFlash('success', 'Field deleted successfully');

        return $this->redirectToRoute('homepage');
    }

    private function setUserParameters(Request $request, LexaniVideos $videoData)
    {
        $userParameters = new UserParameters();
        $userParameters->setIp($request->server->get('REMOTE_ADDR'));
        $userParameters->setBrowser($request->server->get('HTTP_USER_AGENT'));
        $userParameters->addLexaniVideo($videoData);
        $this->em->persist($userParameters);
    }
}