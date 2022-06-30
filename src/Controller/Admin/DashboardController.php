<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="site_app_admin_redirect")
     */
    public function adminRedirect()
    {
        return $this->redirectToRoute('_slider_list');
    }
}