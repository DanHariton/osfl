<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Preparing;
use App\Form\EventType;
use App\Form\PreparingType;
use App\Repository\EventRepository;
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
            'form' => $form->createView(),
            'preparing' => $preparing
        ]);
    }

    /**
     * @Route("/delete/{preparing}", name="_preparing_delete")
     * @param Preparing $preparing
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(Preparing $preparing, EntityManagerInterface $em)
    {
        $em->remove($preparing);
        $em->flush();

        $this->addFlash('success', 'Zaznam byl úspěšně smazán');
        return $this->redirectToRoute('_preparing_list');
    }

    /**
     * @Route("/event/add", name="_event_add")
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addEvent(EntityManagerInterface $em, EntityTranslator $entityTranslator, Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Event $event */
            $event = $entityTranslator->map($form, $event, Event::EVENT_VARS_LANG, Event::EVENT_VARS);

            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Event byl úspěšně přidán');
            return $this->redirectToRoute('_event_list');
        }

        return $this->render('admin/actions/content/event/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/event/list", name="_event_list")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function listEvents(EventRepository $eventRepository)
    {
        return $this->render('admin/actions/content/event/list.html.twig', [
            'events' => $eventRepository->findAll()
        ]);
    }

    /**
     * @Route("/event/edit/{event}", name="_event_edit")
     * @param Event $event
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @return RedirectResponse|Response
     */
    public function editEvent(Event $event, Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator)
    {
        $form = $this->createForm(EventType::class, $entityTranslator->unmap($event, Event::EVENT_VARS_LANG, Event::EVENT_VARS))->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Event $event */
            $event = $entityTranslator->map($form, $event, Event::EVENT_VARS_LANG, Event::EVENT_VARS);

            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Event byl úspěšně změněn');
            return $this->redirectToRoute('_event_edit', ['event' => $event->getId()]);
        }

        return $this->render('admin/actions/content/event/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/event/delete/{event}", name="_event_delete")
     * @param Event $event
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteEvent(Event $event, EntityManagerInterface $em)
    {
        $em->remove($event);
        $em->flush();

        $this->addFlash('success', 'Event byl úspěšně smazán');
        return $this->redirectToRoute('_event_list');
    }
}