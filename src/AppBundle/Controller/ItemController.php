<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Match;
use AppBundle\Entity\Image;
use AppBundle\Entity\Category;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

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
        $items = $em->getRepository(Item::class)->findBy(['user' => $user]);
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('item/index.html.twig', array(
            'items' => $items,
            'categories' => $categories
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
     * @return Response
     */
    public function newAction(Request $request): Response
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
            $item->setExpires(new \DateTime('+'.$this->getParameter('item_valid_days').' days'));
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
        $margin = $this->getParameter('item_match_margin');
        $limit = $this->getParameter('item_match_limit');

        $deleteForm = $this->createDeleteForm($item);

        $itemsToMatch = $this
            ->getDoctrine()
            ->getRepository(Item::class)
            ->findAvailableMatches($item, $this->getUser(), $margin, $limit);

        return $this->render('item/show.html.twig', array(
            'item' => $item,
            'items_to_match' => $itemsToMatch,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Adds item to rejected list
     *
     * @Route("/{id}/reject/{rejected}", name="item_reject")
     *
     * @Method("GET")
     *
     * @return Response
     */
    public function rejectAction(Item $item, Item $rejected): Response
    {
        if ($item->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $match = new Match();
        $match->setItemOwner($item);
        $match->setItemRespondent($rejected);
        $match->setStatus(Match::STATUS_REJECTED);

        $em->persist($match);
        $em->flush();

        return $this->redirectToRoute('item_show', ['id' => $item->getId()]);
    }

    /**
     * Adds item to match wishlist
     *
     * @Route("/{id}/accept/{accepted}", name="item_accept")
     *
     * @Method("GET")
     *
     * @return Response
     */
    public function acceptAction(Item $item, Item $accepted): Response
    {
        if ($item->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $match = new Match();
        $match->setItemOwner($item);
        $match->setItemRespondent($accepted);
        $match->setStatus(Match::STATUS_ACCEPTED);

        $em->persist($match);
        $em->flush();

        return $this->redirectToRoute('item_show', ['id' => $item->getId()]);
    }

    /**
     * Adds category to item categoryToMatch list
     *
     * @Route("/category-add", name="item_category_add")
     *
     * @Method("POST")
     *
     * @return Response
     */
    public function categoryAddAction(Request $request): Response
    {
        $itemId = $request->get('item', null);
        $categoryId = $request->get('category', null);

        if(!$itemId || !$categoryId) {
            throw new InvalidArgumentException('Missing parameter');
        }

        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository(Item::class)->find($itemId);
        $category = $em->getRepository(Category::class)->find($categoryId);

        if(!$item || !$category) {
            throw new NoResultException();
        }

        if ($item->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        if(count($item->getCategoriesToMatch()) > 2) {
            throw $this->createAccessDeniedException("Item has reached the maximum limit of categories");
        }

        $item->addCategoriesToMatch($category);

        try {
            $this->getDoctrine()->getManager()->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw $this->createAccessDeniedException("This category is already in item's categories list");
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * Removes category from item categoryToMatch list
     *
     * @Route("/{id}/category-remove/{category}", name="item_category_remove")
     *
     * @Method("GET")
     *
     * @return Response
     */
    public function categoryRemoveAction(Item $item, Category $category): Response
    {
        if ($item->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $item->removeCategoriesToMatch($category);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('item_index');
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
     * @return Response
     */
    public function editAction(Request $request, Item $item): Response
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
