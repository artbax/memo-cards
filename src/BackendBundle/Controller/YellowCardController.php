<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\YellowCard;
use BackendBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;


/**
 * Yellowcard controller.
 *
 * @Route("yellowcard")
 */
class YellowCardController extends Controller
{
    /**
     * Lists all yellowCard entities.
     *
     * @Route("/", name="yellowcard_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->get('security.context')->getToken()->getUser()->getUsername();

        $yellowCards = $em->getRepository('BackendBundle:YellowCard')->findBy(array('author' => $userName), array('id' => 'DESC'));

        return $this->render('yellowcard/index.html.twig', array(
            'yellowCards' => $yellowCards,
        ));
    }

    /**
     * Creates a new yellowCard entity.
     *
     * @Route("/new", name="yellowcard_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $yellowCard = new YellowCard();
        $yellowCard->setAuthor($this->get('security.context')->getToken()->getUser()->getUsername());
        $form = $this->createForm('BackendBundle\Form\YellowCardType', $yellowCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($yellowCard);
            $em->flush($yellowCard);

            return $this->redirectToRoute('yellowcard_index');
        }
        
        
           return $this->render('yellowcard/new.html.twig', array(
            'yellowCard' => $yellowCard,
            'form' => $form->createView(),
            ));
    }

    
   

    /**
     * Displays a form to edit an existing yellowCard entity.
     *
     * @Route("/{id}/edit", name="yellowcard_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, YellowCard $yellowCard)
    {
        if($yellowCard->getAuthor() == $this->get('security.context')->getToken()->getUser()->getUsername())
        {
		    $editForm = $this->createForm('BackendBundle\Form\YellowCardType', $yellowCard);
		    $editForm->handleRequest($request);

		    if ($editForm->isSubmitted() && $editForm->isValid()) {
		        $this->getDoctrine()->getManager()->flush();

		        return $this->redirectToRoute('yellowcard_edit', array('id' => $yellowCard->getId()));
		    }

		    return $this->render('yellowcard/edit.html.twig', array(
		        'yellowCard' => $yellowCard,
		        'edit_form' => $editForm->createView(),
		        
		    ));
        }
        else
        {
            return $this->render('error/error.html.twig');
        }
    }

    
}
