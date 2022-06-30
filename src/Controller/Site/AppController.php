<?php

namespace App\Controller\Site;

use App\Entity\Offer;
use App\Entity\SliderImages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/{_locale}/", name="site_app_index", defaults={"_locale": "cs"}, requirements={"_locale"="en|cs"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em)
    {
        $lastOffers = $em->getRepository(Offer::class)->findLastOffer(3);
        $sliderImages = $em->getRepository(SliderImages::class)->findByEnabled();

        return $this->render('site/app/index.html.twig', [
            'lastOffers' => $lastOffers,
            'sliderImages' => $sliderImages
        ]);
    }

    /**
     * @Route("/about", name="site_app_about")
     */
    public function about()
    {
        return $this->render('site/app/about.html.twig');
    }
}