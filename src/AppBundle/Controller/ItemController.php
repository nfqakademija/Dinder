<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Image;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Item controller.
 *
 * @Route("item")
 */
class ItemController extends Controller
{
    /**
     * Lists all item entities.
     *
     * @Route("/", name="item_index")
     *
     * @Method("GET")
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $items = $em->getRepository('AppBundle:Item')->findByUser($user);

        return $this->render('item/index.html.twig', array(
            'items' => $items,
        ));
    }

    /**
     * Creates a new item entity.
     *
     * @Route("/new", name="item_new")
     *
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $item = new Item();

        // Adding one image to be main
        $imageMain = new Image();
        $imageMain->setMain(true);
        $item->addImage($imageMain);

        $form = $this->createForm('AppBundle\Form\ItemType', $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setApprovals(0);
            $item->setRejections(0);
            $item->setStatus(Item::STATUS_ACTIVE);
            $item->setCreated(new \DateTime('now'));
            $item->setExpires(new \DateTime('+' . $this->getParameter('item_valid_days') . ' days'));
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $item->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_show', array('id' => $item->getId()));
        }

        return $this->render('item/new.html.twig', array(
            'item' => $item,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a item entity.
     *
     * @Route("/{id}", name="item_show")
     *
     * @Method("GET")
     *
     * @param Item $item
     *
     * @return Response
     */
    public function showAction(Item $item): Response
    {
        $deleteForm = $this->createDeleteForm($item);

        $itemsToMatch = $this
            ->getDoctrine()
            ->getRepository(Item::class)
            ->findAvailableMatches($item, $this->getUser(), $this->getParameter('item_match_margin'), $this->getParameter('item_match_limit'));

        return $this->render('item/show.html.twig', array(
            'item' => $item,
            'items_to_match' => $itemsToMatch,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing item entity.
     *
     * @Route("/{id}/edit", name="item_edit")
     *
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Item $item
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Item $item)
    {
        $deleteForm = $this->createDeleteForm($item);
        $editForm = $this->createForm('AppBundle\Form\ItemType', $item);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_edit', array('id' => $item->getId()));
        }

        return $this->render('item/edit.html.twig', array(
            'item' => $item,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a item entity.
     *
     * @Route("/{id}", name="item_delete")
     *
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Item $item
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Item $item): RedirectResponse
    {
        $form = $this->createDeleteForm($item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($item);
            $em->flush();
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * Creates a form to delete a item entity.
     *
     * @param Item $item
     *
     * @return Form
     */
    private function createDeleteForm(Item $item): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('item_delete', array('id' => $item->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
