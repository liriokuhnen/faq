<?php

// src/AppBundle/Controller/Admin/FaqController.php
namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use AppBundle\Form\AnswerType;
use AppBundle\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FaqController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {

        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('admin/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/admin/logout", name="logout")
     */
    public function logoutAction(Request $request, AuthenticationUtils $authUtils)
    {

        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('admin/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function homeAction(Request $request)
    {
        return $this->render(
            'admin/home.html.twig'
        );
    }

    /**
     * @Route("/admin/answer", name="answer_list")
     */
    public function answerAction(Request $request)
    {
        $answers = $this->getDoctrine()
                ->getRepository('AppBundle:Answer')
                ->findAll();

        return $this->render(
            'admin/answer.html.twig',
            ['answers' => $answers]
        );
    }
    
    /**
     * @Route("/admin/answer/create", name="answer_create")
     */
    public function answerCreateAction(Request $request)
    {
        $answer = new Answer();
        
        $form = $this->createForm(AnswerType::class, $answer);

        if(isset($_GET['question_id']))
        {
            $question = $this->getDoctrine()
                ->getRepository('AppBundle:Question')
                ->find($_GET['question_id']);

            if (empty($question)) {
                $this->addFlash('danger', 'Question not found');
                
                return $this->redirectToRoute('question_list');
            }

            $form->get('question')->setData($question->getQuestionName());
        }

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $answer->setQuestion($form['question']->getData());
            $answer->setAnswer($form['answer']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();
            
            $this->addFlash('success', 'FAQ answer Added');
            
            return $this->redirectToRoute('answer_list');
        }
        
        return $this->render('admin/answer_create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/answer/edit/{id}", name="answer_edit")
     */
    public function answerEditAction($id, Request $request)
    {
        $answer = $this->getDoctrine()
                ->getRepository('AppBundle:Answer')
                ->find($id);
        
        if (empty($answer)) {
            $this->addFlash('danger', 'Answer not found');
            
            return $this->redirectToRoute('answer_list');
        }
        
        $form = $this->createForm(AnswerType::class, $answer);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $answer->setQuestion($form['question']->getData());
            $answer->setAnswer($form['answer']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();
            
            $this->addFlash('success', 'Answer updated');
            
            return $this->redirectToRoute('answer_list');
        }
        
        return $this->render('admin/answer_edit.html.twig', array(
            'form' => $form->createView(),
            'answer' => $answer
        ));
    }

    /**
     * @Route("/admin/answer/delete/{id}", name="answer_delete")
     */
    public function answerDeleteAction($id)
    {
        $answer = $this->getDoctrine()
                ->getRepository('AppBundle:Answer')
                ->find($id);
        
        if (empty($answer)) {
            $this->addFlash('danger', 'Answer not found');
            
            return $this->redirectToRoute('answer_list');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($answer);
        $em->flush();
        
        $this->addFlash('success', 'Answer removed');
       
        return $this->redirectToRoute('answer_list');
    }

    /**
     * @Route("/admin/question", name="question_list")
     */
    public function questionAction(Request $request)
    {
        $questions = $this->getDoctrine()
                ->getRepository('AppBundle:Question')
                ->findBy([], ['id' => 'DESC']);

        return $this->render(
            'admin/question.html.twig',
            ['questions' => $questions]
        );
    }

    /**
     * @Route("/admin/question/unread", name="question_unread")
     */
    public function questionUnreadAction(Request $request)
    {
        $questions = $this->getDoctrine()
                ->getRepository('AppBundle:Question')
                ->findBy(['open' => 0], ['id' => 'DESC']);

        return $this->render(
            'admin/question_unread.html.twig',
            ['questions' => $questions]
        );
    }

    /**
     * @Route("/admin/question/read/{id}", name="question_read")
     */
    public function questionReadAction($id)
    {
        $question = $this->getDoctrine()
                ->getRepository('AppBundle:Question')
                ->find($id);
        
        if (empty($question)) {
            $this->addFlash('danger', 'Question not found');
            return $this->redirectToRoute('question_unread');
        }

        $question->open = 1;
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();
       
        return $this->redirectToRoute('question_unread');
    }
}