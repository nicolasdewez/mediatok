<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Media;
use AppBundle\Form\MediaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/medias")
 */
class MediaController extends Controller
{
    /**
     * @return Response
     *
     * @Route("", name="app_medias", methods={"GET"})
     */
    public function listAction(): Response
    {
        $elements = $this->get('doctrine')->getRepository(Media::class)->findBy([], ['title' => 'ASC']);

        return $this->render('medias/list.html.twig', ['elements' => $elements]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/add", name="app_medias_add", methods={"GET", "POST"})
     */
    public function addAction(Request $request): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->persist($media);
            $manager->flush();

            $this->addFlash('notice', 'Média ajouté.');

            return $this->redirectToRoute('app_medias');
        }

        return $this->render('medias/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Media   $media
     *
     * @return Response
     *
     * @Route("/edit/{id}", name="app_medias_edit", methods={"GET", "POST"})
     */
    public function editTypeAction(Request $request, Media $media): Response
    {
        $form = $this->createForm(MediaType::class, $media);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->flush();

            $this->addFlash('notice', 'Média modifié.');

            return $this->redirectToRoute('app_medias');
        }

        return $this->render('medias/edit.html.twig', ['form' => $form->createView()]);
    }
}
