<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\OfferImages;
use App\Form\OfferImagesType;
use App\Repository\OfferImagesRepository;
use App\Service\EntityTranslator;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer-images")
 * Class OfferImagesController
 * @package App\Controller\Admin
 */
class OfferImagesController extends AbstractController
{
    /**
     * @Route("/list", name="_off_images_list")
     * @param OfferImagesRepository $offerImagesRepository
     * @return Response
     */
    public function list(OfferImagesRepository $offerImagesRepository)
    {
        return $this->render('admin/actions/offerImage/list.html.twig', [
            'offersImages' => $offerImagesRepository->findAll()
        ]);
    }

    /**
     * @Route("/toogle-enable/{offerImages}", name="_off_images_toggle_enable")
     * @param OfferImages $offerImages
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleEnable(OfferImages $offerImages, EntityManagerInterface $em)
    {
        $offerImages->setEnabled(!$offerImages->getEnabled());
        $em->persist($offerImages);
        $em->flush();

        $this->addFlash('success', 'Změna úspěšně uložena');
        return $this->redirectToRoute('_off_images_list');
    }

    /**
     * @Route("/delete/{offerImages}", name="_off_images_delete")
     * @param OfferImages $offerImages
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @return RedirectResponse
     */
    public function delete(OfferImages $offerImages, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $images = $offerImages->getImages();
        foreach ($images as $image) {
            $imageUploader->remove($image->getFileName());
            $em->remove($image);
        }

        $em->remove($offerImages);
        $em->flush();
        $this->addFlash('success', 'Nabídka (obrazky) byla úspěšně odstraněna');
        return $this->redirectToRoute('_off_images_list');
    }

    /**
     * @Route("/add", name="_off_images_add")
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(EntityManagerInterface $em, ImageUploader $imageUploader, EntityTranslator $entityTranslator, Request $request)
    {
        $offerImages = new OfferImages();
        $form = $this->createForm(OfferImagesType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferImages $offerImages */
            $offerImages = $entityTranslator->map($form, $offerImages, OfferImages::OFFER_IMAGES_VARS_LANG, OfferImages::OFFER_IMAGES_VARS);
            $offerImages->setEnabled(false);

            $images = $form->get('files')->getData();
            if (!empty($images)) {
                foreach ($images as $order => $image) {
                    $imageName = $imageUploader->upload($image, ImageUploader::TYPE_550x400);
                    $imageFile = new File();
                    $imageFile->setFileName($imageName);
                    $offerImages->addImage($imageFile);
                    $em->persist($imageFile);
                }
            }

            $em->persist($offerImages);
            $em->flush();
            $this->addFlash('success', 'Nabídka (obrazky) byla úspěšně přidána');
            return $this->redirectToRoute('_off_images_list');
        }

        return $this->render('admin/actions/offerImage/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/image/delete/{offerImages}/{file}", name="_off_images_image_delete")
     * @param OfferImages $offerImages
     * @param File $file
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @return RedirectResponse
     */
    public function imageDelete(OfferImages $offerImages, File $file, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $imageUploader->remove($file->getFileName());
        $em->remove($file);
        $em->flush();
        $this->addFlash('success', 'Nabídka byla úspěšně změněna');
        return $this->redirectToRoute('_off_images_edit', ['offerImages' => $offerImages->getId()]);
    }

    /**
     * @Route("/edit/{offerImages}", name="_off_images_edit")
     * @param OfferImages $offerImages
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EntityTranslator $entityTranslator
     * @param ImageUploader $imageUploader
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(OfferImages $offerImages, Request $request, EntityManagerInterface $em, EntityTranslator $entityTranslator,
                         ImageUploader $imageUploader)
    {
        $form = $this->createForm(OfferImagesType::class, $entityTranslator->unmap($offerImages, OfferImages::OFFER_IMAGES_VARS_LANG, OfferImages::OFFER_IMAGES_VARS))->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var OfferImages $offerImages */
            $offerImages = $entityTranslator->map($form, $offerImages, OfferImages::OFFER_IMAGES_VARS_LANG, OfferImages::OFFER_IMAGES_VARS);
            $images = $form->get('files')->getData();

            if (!empty($images)) {
                foreach ($images as $order => $image) {
                    $imageName = $imageUploader->upload($image, ImageUploader::TYPE_550x400);
                    $imageFile = new File();
                    $imageFile->setFileName($imageName);
                    $offerImages->addImage($imageFile);
                    $em->persist($imageFile);
                }
            }

            $em->persist($offerImages);
            $em->flush();
            $this->addFlash('success', 'Nabídka (obrazky) byla úspěšně změněna');
            return $this->redirectToRoute('_off_images_edit', ['offerImages' => $offerImages->getId()]);
        }

        return $this->render('admin/actions/offerImage/edit.html.twig', [
            'form' => $form->createView(),
            'offerImages' => $offerImages
        ]);
    }
}