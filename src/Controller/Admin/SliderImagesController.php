<?php

namespace App\Controller\Admin;

use App\Entity\SliderImages;
use App\Form\SliderImageType;
use App\Repository\SliderImagesRepository;
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
 * Class SliderImagesController
 * @package App\Controller\Admin
 * @Route("/slider")
 */
class SliderImagesController extends AbstractController
{
    /**
     * @Route("/list", name="_slider_list")
     * @param SliderImagesRepository $sliderImagesRepository
     * @return Response
     */
    public function list(SliderImagesRepository $sliderImagesRepository)
    {
        return $this->render('admin/actions/content/slider/list.html.twig', [
            'sliderImages' => $sliderImagesRepository->findAllByDisplayOrder()
        ]);
    }

    /**
     * @Route("/add", name="_slider_add")
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(EntityManagerInterface $em, ImageUploader $imageUploader, EntityTranslator $entityTranslator, Request $request)
    {
        $sliderImages = new SliderImages();
        $form = $this->createForm(SliderImageType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SliderImages $sliderImage */
            $sliderImages = $entityTranslator->map($form, $sliderImages, SliderImages::SLIDER_VARS_LANG, SliderImages::SLIDER_VARS);

            $sliderImgFile = $form->get('image')->getData();
            $sliderImages->setEnabled(false);

            if ($sliderImgFile) {
                $newFilename = $imageUploader->upload($sliderImgFile, ImageUploader::TYPE_1920x720);
                $sliderImages->setImageFilename($newFilename);
            }

            $em->persist($sliderImages);
            $em->flush();
            $this->addFlash('success', 'Obrázek byl úspěšně přidán');
            return $this->redirectToRoute('_slider_list');
        }

        return $this->render('admin/actions/content/slider/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{sliderImage}", name="_slider_edit")
     * @param SliderImages $sliderImage
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @param EntityTranslator $entityTranslator
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function edit(SliderImages $sliderImage, EntityManagerInterface $em, ImageUploader $imageUploader,
                         EntityTranslator $entityTranslator, Request $request)
    {
        $form = $this->createForm(SliderImageType::class, $entityTranslator->unmap($sliderImage, SliderImages::SLIDER_VARS_LANG, SliderImages::SLIDER_VARS))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SliderImages $sliderImage */
            $sliderImage = $entityTranslator->map($form, $sliderImage, SliderImages::SLIDER_VARS_LANG, SliderImages::SLIDER_VARS);
            $sliderImgFile = $form->get('image')->getData();

            if ($sliderImgFile) {
                $imageUploader->remove($sliderImage->getImageFilename());
                $newFilename = $imageUploader->upload($sliderImgFile, ImageUploader::TYPE_1920x720);
                $sliderImage->setImageFilename($newFilename);
            }

            $em->persist($sliderImage);
            $em->flush();
            $this->addFlash('success', 'Změny v obrázku byli úspěšně uloženy');
            return $this->redirectToRoute('_slider_list');
        }

        return $this->render('admin/actions/content/slider/edit.html.twig', [
            'form' => $form->createView(),
            'sliderImage' => $sliderImage
        ]);
    }

    /**
     * @Route("/toggle/{sliderImage}", name="_slider_toggle")
     * @param SliderImages $sliderImage
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleEnable(SliderImages $sliderImage, EntityManagerInterface $em)
    {
        $sliderImage->setEnabled(!$sliderImage->getEnabled());
        $em->persist($sliderImage);
        $em->flush();

        $this->addFlash('success', 'Změna úspěšně uložena');
        return $this->redirectToRoute('_slider_list');
    }

    /**
     * @Route("/delete/{sliderImage}", name="_slider_delete")
     * @param SliderImages $sliderImage
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @return RedirectResponse
     */
    public function delete(SliderImages $sliderImage, EntityManagerInterface $em, ImageUploader $imageUploader)
    {
        $imageUploader->remove($sliderImage->getImageFilename());
        $em->remove($sliderImage);
        $em->flush();

        $this->addFlash('success', 'Obrázek byl úspěšně odstraněn');
        return $this->redirectToRoute('_slider_list');
    }
}