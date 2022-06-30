<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    /**
     * @Route("/change-show-language/{language}", name="_change_show_language", requirements={"language"="en|cs"})
     * @param $language
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeShowLanguage($language, Request $request)
    {
        $this->get('session')->set('language', $language);

        return $this->redirect($request->headers->get('referer'));
    }
}