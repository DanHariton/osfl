<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use App\Service\EntityTranslator;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

/**
 * Class OfferController
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/list", name="_offer_list")
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function list(OfferRepository $offerRepository)
    {
        return $this->render('admin/actions/offer/list.html.twig', [
            'offers' => $offerRepository->findAll()
        ]);
    }

    /**
     * @Route("/add", name="_offer_add")
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(EntityManagerInterface $em, ImageUploader $imageUploader, EntityTranslator $entityTranslator, Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Offer $offer */
            $offer = $entityTranslator->map($form, $offer, Offer::OFFER_VARS_LANG, Offer::OFFER_VARS);
            $offer->setEnabled(false);

            $images = $form->get('files')->getData();
            if (!empty($images)) {
                foreach ($images as $order => $image) {
                    $imageName = $imageUploader->upload($image, ImageUploader::TYPE_1050x770);
                    $imageFile = new File();
                    $imageFile->setFileName($imageName);
                    $imageFile->setOrder($order);
                    $offer->addImage($imageFile);
                    $em->persist($imageFile);
                }
            }

            $em->persist($offer);
            $em->flush();
            $this->addFlash('success', 'Nabídka byla úspěšně přidána');
            return $this->redirectToRoute('_offer_list');
        }

        return $this->render('admin/actions/offer/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/toggle/{offer}", name="_offer_toggle")
     * @param Offer $offer
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleEnable(Offer $offer, EntityManagerInterface $em)
    {
        $offer->setEnabled(!$offer->getEnabled());
        $em->persist($offer);
        $em->flush();

        $this->addFlash('success', 'Změna úspěšně uložena');
        return $this->redirectToRoute('_offer_list');
    }

    /**
     * @Route("/delete/{offer}", name="_offer_delete")
     * @param Offer $offer
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @return RedirectResponse
     */
    public function delete(Offer $offer, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $images = $offer->getImages();
        foreach ($images as $image) {
            $imageUploader->remove($image->getFileName());
            $em->remove($image);
        }
        $em->remove($offer);
        $em->flush();
        $this->addFlash('success', 'Nabídka byla úspěšně odstraněna');
        return $this->redirectToRoute('_offer_list');
    }

    /**
     * @Route("/edit/{offer}", name="_offer_edit")
     * @param Offer $offer
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @param ImageUploader $imageUploader
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(Offer $offer, Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator,
                              ImageUploader $imageUploader)
    {
        $form = $this->createForm(OfferType::class, $entityTranslator->unmap($offer, Offer::OFFER_VARS_LANG, Offer::OFFER_VARS))->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Offer $offer */
            $offer = $entityTranslator->map($form, $offer, Offer::OFFER_VARS_LANG, Offer::OFFER_VARS);
            $maxOrder = $offer->getMaxOrder();

            $images = $form->get('files')->getData();
            if (!empty($images)) {
                foreach ($images as $order => $image) {
                    $imageName = $imageUploader->upload($image, ImageUploader::TYPE_1050x770);
                    $imageFile = new File();
                    $imageFile->setFileName($imageName);
                    $imageFile->setOrder($order);
                    $imageFile->setOrder($maxOrder + $order);
                    $offer->addImage($imageFile);
                    $em->persist($imageFile);
                }
            }

            $em->persist($offer);
            $em->flush();
            $this->addFlash('success', 'Nabídka byla úspěšně změněna');
            return $this->redirectToRoute('_offer_edit', ['offer' => $offer->getId()]);
        }

        return $this->render('admin/actions/offer/edit.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer
        ]);
    }

    /**
     * @Route("/image/delete/{offer}/{file}", name="_offer_image_delete")
     * @param Offer $offer
     * @param File $file
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @return RedirectResponse
     */
    public function imageDelete(Offer $offer, File $file, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $imageUploader->remove($file->getFileName());
        $em->remove($file);
        $em->flush();
        $this->addFlash('success', 'Nabídka byla úspěšně změněna');
        return $this->redirectToRoute('_offer_edit', ['offer' => $offer->getId()]);
    }

    /**
     * @Route("/image/reorder/{offer}/{file}/{way}", name="_offer_image_reorder")
     * @param Offer $offer
     * @param File $file
     * @param $way
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function imageReorder(Offer $offer, File $file, $way, EntityManagerInterface $em)
    {
        $offer->reorder($file, $way === 'top' ? 1 : -1);
        $em->flush();
        $this->addFlash('success', 'Nabídka byla úspěšně změněna');
        return $this->redirectToRoute('_offer_edit', ['offer' => $offer->getId()]);
    }
}