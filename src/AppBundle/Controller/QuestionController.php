<?php

// src/AppBundle/Controller/QuestionController.php
namespace AppBundle\Controller;

use Mailgun\Mailgun;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends Controller
{
    /**
     * @Route("/question", name="send_question")
     */
    public function questionAction(Request $request)
    {
        $context = array();

        $context['sent_form'] = false;

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $text = "Nome:" . $question->getName() . "\n\n";
            $text .= "Email:" . $question->getEmail() . "\n\n";
            $text .= "Question:" . $question->getQuestionName() . "\n\n";

            # Send email
            $mgClient = new Mailgun($this->container->getParameter('mailgun_key'));
            $domain = $this->container->getParameter('mailgun_domain');
            $result = $mgClient->sendMessage("$domain",
                array('from'    => 'Mailgun Sandbox <postmaster@' . $this->container->getParameter('mailgun_domain') . '>',
                      'to'      => $this->container->getParameter('mailgun_to'),
                      'subject' => 'Asked Question',
                      'text'    => $text
            ));

            if($result->http_response_code != 200){
                $request->getSession()
                    ->getFlashBag()
                    ->add('danger', 'ops, unexpected error, please try again.')
                ;
                return $this->redirectToRoute('send_question');
            }

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Thanks, your question was sent with success.')
            ;

            $context['sent_form'] = true;
        }

        $context['form'] = $form->createView();

        return $this->render(
            'question/index.html.twig',
            $context
        );
    }
}