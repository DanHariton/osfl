<?php

namespace App\Controller\Site;

use App\Entity\Offer;
use App\Entity\Preparing;
use App\Entity\SliderImages;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/{_locale}", name="site_app_index", defaults={"_locale": "cs"}, requirements={"_locale": "en|cs"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(EntityManagerInterface $em)
    {
        $lastOffers = $em->getRepository(Offer::class)->findLastOffer(6);
        $sliderImages = $em->getRepository(SliderImages::class)->findByEnabled();

        return $this->render('site/app/index.html.twig', [
            'lastOffers' => $lastOffers,
            'sliderImages' => $sliderImages
        ]);
    }

    /**
     * @Route("/{_locale}/about", name="site_app_about", defaults={"_locale": "cs"}, requirements={"_locale": "en|cs"})
     */
    public function about()
    {
        return $this->render('site/app/about.html.twig');
    }

    /**
     * @Route("/{_locale}/events", name="site_app_events", defaults={"_locale": "cs"}, requirements={"_locale": "en|cs"})
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function preparings(EntityManagerInterface $em)
    {
        $preparings = $em->getRepository(Preparing::class)->findLastMonthPreparings();

        return $this->render('site/app/events.html.twig', [
           'preparings' => $preparings
        ]);
    }

    /**
     * @Route("/{_locale}/contact", name="site_app_contact", defaults={"_locale": "cs"}, requirements={"_locale": "en|cs"})
     */
    public function contact()
    {
        return $this->render('site/app/contact.html.twig');
    }
}