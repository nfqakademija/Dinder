<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController.
 *
 * @Route("/")
 */
class HomeController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(): Response
    {
        $limit = $this->getParameter('item_featured_limit');

        $featuredItems = $this->getDoctrine()->getRepository(Item::class)->findFeatured($limit);

        return $this->render('AppBundle:Home:index.html.twig', [
            'featured_items' => $featuredItems
        ]);
    }
}
