<?php

namespace FrontendBundle\Controller;

use BackendBundle\Entity\YellowCard; 
use BackendBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



class DefaultController extends Controller
{
    /**
     * @Route("/", name="frontend")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('BackendBundle:YellowCard')->findAllCards();
        return $this->render('frontend/index.html.twig', array(
            'entities' => $entities
            ));
    }

    /**
     * Finds and displays a yellowCard entity.
     *
     * @Route("/{id}", name="frontend_yellowcard_show")
     * @Method("GET")
     */
    public function showAction(YellowCard $yellowCard)
    {
        
        $cardId = $yellowCard->getId();
        
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('BackendBundle:Comment')->findByyellowcard($cardId);

        
        return $this->render('frontend/show.html.twig', array(
            'yellowCard' => $yellowCard,
            'comments' => $comments,
            ));
    }

    /**
     * Add comment.
     *
     * @Route("/add_comment/{id}", name="frontend_add_comment")
     * @Method("POST")
     */

    public function addCommentAction(YellowCard $card)
    {
       $author = $this->get('security.context')->getToken()->getUser()->getUsername();
       $text = $_POST['comment'];
       
       if(!empty($text))
       {
       

		   $newComment = new Comment();
		   $newComment->setYellowcard($card);
		   $newComment->setAuthor($author);
		   $newComment->setBody($text);

           $em = $this->getDoctrine()->getManager();
		   $em->persist($newComment);
		   $em->flush();

           return $this->redirectToRoute('frontend_yellowcard_show', array('id' => $card->getId())); 
       }
       else
       {
          return $this->render('error/error.html.twig');
       }

       
    }
}
