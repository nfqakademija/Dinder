<?php

namespace AppBundle\Controller;

use AppBundle\Entity\History;
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
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $activeItems = $em->getRepository(Item::class)->findBy([
            'user' => $user,
            'status' => Item::STATUS_ACTIVE,
        ]);
        $tradedItems = $em->getRepository(Item::class)->findBy([
            'user' => $user,
            'status' => Item::STATUS_TRADED,
        ]);

        $categories = $em->getRepository(Category::class)->findAll();

        $response = $this->render('item/index.html.twig', array(
            'items' => $activeItems,
            'traded_items' => $tradedItems,
            'categories' => $categories
        ));

        $this->markItemsAsSeen($tradedItems);

        return $response;
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

        $form = $this->createForm('AppBundle\Form\ItemType', $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $item->setApprovals(0);
            $item->setRejections(0);
            $item->setStatus(Item::STATUS_ACTIVE);
            $item->setCreated(new \DateTime('now'));
            $item->setUser($user);

            $history = new History();
            $history->setItem($item);
            $history->setUser($item->getUser());
            $history->setSeen(new \DateTime());
            $em->persist($history);

            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', array(
            'item' => $item,
            'form' => $form->createView(),
        ));
    }

    /**
     * Make item active for matches.
     *
     * @Route("/{id}/activate", name="item_activate")
     *
     * @Method("PUT")
     *
     * @param Request $request
     * @param Item $item
     *
     * @return Response
     */
    public function activateAction(Request $request, Item $item): Response
    {
        if (!$this->isCsrfTokenValid($item->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token is invalid');
        }

        if ($this->getUser() !== $item->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $item->setStatus(Item::STATUS_ACTIVE);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('item_index');
    }

    /**
     * Adds/removes item to/from match wishlist
     *
     * @Route("/match", name="item_match")
     *
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function matchAction(Request $request): Response
    {
        $itemOwnerId = $request->get('item', null);
        $itemRespondentId = $request->get('respondent', null);
        $status = $request->get('status', null);

        if (!$itemOwnerId ||
            !$itemRespondentId ||
            $status === null ||
            !in_array($status, [Match::STATUS_ACCEPTED, Match::STATUS_REJECTED])) {
            throw new InvalidArgumentException('Missing parameter');
        }

        $em = $this->getDoctrine()->getManager();
        $itemOwner = $em->getRepository(Item::class)->find($itemOwnerId);
        $itemRespondent = $em->getRepository(Item::class)->find($itemRespondentId);

        if (!$itemOwner || !$itemRespondent) {
            throw new NoResultException();
        }

        if ($itemOwner->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        $em = $this->getDoctrine()->getManager();

        $match = new Match();
        $match->setItemOwner($itemOwner);
        $match->setItemRespondent($itemRespondent);
        $match->setStatus($status);

        if ($status == Match::STATUS_ACCEPTED) {
            $itemRespondent->setApprovals($itemRespondent->getApprovals() + 1);
        }
        if ($status == Match::STATUS_REJECTED) {
            $itemRespondent->setRejections($itemRespondent->getRejections() + 1);
        }
        $em->persist($itemRespondent);

        $em->persist($match);
        $em->flush();

        return new JsonResponse([], 200);
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
        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');

        $itemsToMatch = $this
            ->getDoctrine()
            ->getRepository(Item::class)
            ->findAvailableMatches($item, $this->getUser(), $margin, $limit);

        $items = [];

        foreach ($itemsToMatch as $itemToMatch) {
            $items[] = [
                'id' => $itemToMatch->getId(),
                'title' => $itemToMatch->getTitle(),
                'description' => $itemToMatch->getDescription(),
                'category' => $itemToMatch->getCategory()->getTitle(),
                'value' => $itemToMatch->getValue(),
                'image' => $helper->asset($itemToMatch, 'file'),
            ];
        }

        return new JsonResponse($items);
    }

    /**
     * Adds category to item categoryToMatch list
     *
     * @Route("/category-add", name="item_category_add")
     *
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function categoryAddAction(Request $request): Response
    {
        $itemId = $request->get('item', null);
        $categoryId = $request->get('category', null);

        if (!$itemId || !$categoryId) {
            throw new InvalidArgumentException('Missing parameter');
        }

        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository(Item::class)->find($itemId);
        $category = $em->getRepository(Category::class)->find($categoryId);

        if (!$item || !$category) {
            throw new NoResultException();
        }

        if ($item->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("It's not your item. Please stop cheating!");
        }

        if (count($item->getCategoriesToMatch()) > 2) {
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
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Item $item
     * @param Category $category
     *
     * @return Response
     */
    public function categoryRemoveAction(Request $request, Item $item, Category $category): Response
    {
        if (!$this->isCsrfTokenValid($item->getId(), $request->get('_token'))) {
            throw $this->createAccessDeniedException('CSRF token is invalid');
        }

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

    /**
     * Mark all unseen items as seen
     *
     * @param array $items
     *
     * @return void
     */
    private function markItemsAsSeen(array $items): void
    {
        $em = $this->getDoctrine()->getManager();

        $now = new \DateTime();

        foreach ($items as $item) {
            $history = $item->getLastHistory();
            if ($history && !$history->getSeen()) {
                $history->setSeen($now);

                $em->persist($history);
            }
        }

        $em->flush();
    }
}
