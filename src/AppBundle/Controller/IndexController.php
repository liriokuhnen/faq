<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $answers = $this->getDoctrine()
                          ->getRepository(Answer::class)
                          ->findAll();

        return $this->render('index/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'answers' => $answers,
        ]);
    }
}
