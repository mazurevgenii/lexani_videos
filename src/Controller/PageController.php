<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage() {

        $request = Request::createFromGlobals();

        $url = 'https://lexani.com/videos';

        //

        return $this->render('parse/homepage.html.twig');
    }
}