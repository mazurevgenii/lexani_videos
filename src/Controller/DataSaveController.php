<?php

namespace App\Controller;

use App\Modules\CsvSaver;
use App\Repository\LexaniVideosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DataSaveController extends AbstractController
{
    private $save;
    private $repository;

    public function __construct(CsvSaver $save, LexaniVideosRepository $repository)
    {
        $this->save = $save;
        $this->repository = $repository;
    }

    /**
     * @Route("/save/", name="save")
     */
    public function saveData(Request $request)
    {
        if ($request->request->get('save') === 'Save to CSV') {

            $this->save->saveToCsv($this->repository);

        } elseif ($request->request->get('saveWithCompare') === 'Save to CSV with Compare') {

            $this->save->saveToCsvWithCompare($this->repository);

        }

        return $this->redirectToRoute('homepage');
    }
}