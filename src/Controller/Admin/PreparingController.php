<?php

namespace App\Controller\Admin;

use App\Entity\Preparing;
use App\Form\PreparingType;
use App\Repository\PreparingRepository;
use App\Service\EntityTranslator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PreparingController
 * @package App\Controller\Admin
 * @Route("/preparing")
 */
class PreparingController extends AbstractController
{
    /**
     * @Route("/list", name="_preparing_list")
     * @param PreparingRepository $preparingRepository
     * @return Response
     */
    public function list(PreparingRepository $preparingRepository)
    {
        return $this->render('admin/actions/content/preparing/list.html.twig', [
            'preparing' => $preparingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/toggle/{preparing}", name="_preparing_toggle")
     * @param Preparing $preparing
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleEnable(Preparing $preparing, EntityManagerInterface $em)
    {
        $preparing->setEnabled(!$preparing->getEnabled());
        $em->persist($preparing);
        $em->flush();

        $this->addFlash('success', 'Změna úspěšně uložena');
        return $this->redirectToRoute('_preparing_list');
    }

    /**
     * @Route("/add", name="_preparing_add")
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(EntityManagerInterface $em, EntityTranslator $entityTranslator, Request $request)
    {
        $preparing = new Preparing();
        $form = $this->createForm(PreparingType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Preparing $preparing */
            $preparing = $entityTranslator->map($form, $preparing, Preparing::PREPARING_VARS_LANG, Preparing::PREPARING_VARS);
            $preparing->setEnabled(false);

            $em->persist($preparing);
            $em->flush();
            $this->addFlash('success', 'Zaznam byl úspěšně přidán');
            return $this->redirectToRoute('_preparing_list');
        }

        return $this->render('admin/actions/content/preparing/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{preparing}", name="_preparing_edit")
     * @param Preparing $preparing
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @return RedirectResponse|Response
     */
    public function edit(Preparing $preparing, Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator)
    {
        $form = $this->createForm(PreparingType::class, $entityTranslator->unmap($preparing, Preparing::PREPARING_VARS_LANG, Preparing::PREPARING_VARS))->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Preparing $preparing */
            $preparing = $entityTranslator->map($form, $preparing, Preparing::PREPARING_VARS_LANG, Preparing::PREPARING_VARS);

            $em->persist($preparing);
            $em->flush();
            $this->addFlash('success', 'Zaznam byl úspěšně změněn');
            return $this->redirectToRoute('_preparing_edit', ['preparing' => $preparing->getId()]);
        }

        return $this->render('admin/actions/content/preparing/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{preparing}", name="_preparing_delete")
     * @param Preparing $preparing
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function categoryDelete(Preparing $preparing, EntityManagerInterface $em)
    {
        $em->remove($preparing);
        $em->flush();

        $this->addFlash('success', 'Zaznam byl úspěšně smazán');
        return $this->redirectToRoute('_preparing_list');
    }
}