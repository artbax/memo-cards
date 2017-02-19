<?php

namespace BackendBundle\Controller;

use BackendBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 *
 * @Route("comment")
 */
class CommentController extends Controller
{
    /**
     * Lists all comment entities.
     *
     * @Route("/", name="comment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->get('security.context')->getToken()->getUser()->getUsername();

        $comments = $em->getRepository('BackendBundle:Comment')->findBy(array('author' => $userName), array('id' => 'DESC'));

        return $this->render('comment/index.html.twig', array(
            'comments' => $comments,
        ));
    }

   
    /**
     * Displays a form to edit an existing comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Comment $comment)
    {
        if($comment->getAuthor() == $this->get('security.context')->getToken()->getUser()->getUsername())
        {
		    $editForm = $this->createForm('BackendBundle\Form\CommentType', $comment);
		    $editForm->handleRequest($request);

		    if ($editForm->isSubmitted() && $editForm->isValid()) {
		        $this->getDoctrine()->getManager()->flush();

		        return $this->redirectToRoute('comment_edit', array('id' => $comment->getId()));
		    }

		    return $this->render('comment/edit.html.twig', array(
		        'comment' => $comment,
		        'edit_form' => $editForm->createView(),
		        ));
        }
        else
        {
           return $this->render('error/error.html.twig');
        }
    }

    
}
