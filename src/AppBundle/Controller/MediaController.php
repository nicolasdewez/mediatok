<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Media;
use AppBundle\Form\MediaType;
use AppBundle\Form\SearchMediaType;
use AppBundle\Model\SearchMedia;
use AppBundle\Service\AskSearch;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param EntityManagerInterface $manager
     *
     * @return Response
     *
     * @Route("", name="app_medias", methods={"GET"})
     */
    public function listAction(EntityManagerInterface $manager): Response
    {
        $elements = $manager->getRepository(Media::class)->findBy([], ['title' => 'ASC']);

        return $this->render('medias/list.html.twig', ['elements' => $elements]);
    }

    /**
     * @param Request                $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     *
     * @Route("/add", name="app_medias_add", methods={"GET", "POST"})
     */
    public function addAction(Request $request, EntityManagerInterface $manager): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($media);
            $manager->flush();

            $this->addFlash('notice', 'Média ajouté.');

            return $this->redirectToRoute('app_medias');
        }

        return $this->render('medias/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request                $request
     * @param EntityManagerInterface $manager
     * @param Media                  $media
     *
     * @return Response
     *
     * @Route("/edit/{id}", name="app_medias_edit", methods={"GET", "POST"})
     */
    public function editTypeAction(Request $request, EntityManagerInterface $manager, Media $media): Response
    {
        $form = $this->createForm(MediaType::class, $media);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('notice', 'Média modifié.');

            return $this->redirectToRoute('app_medias');
        }

        return $this->render('medias/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request   $request
     * @param AskSearch $askSearch
     *
     * @return Response
     *
     * @Route("/search", name="app_medias_search", methods={"GET", "POST"})
     */
    public function searchAction(Request $request, AskSearch $askSearch): Response
    {
        $searchMedia = new SearchMedia();
        $form = $this->createForm(SearchMediaType::class, $searchMedia);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $askSearch->execute($searchMedia);
            $this->addFlash('notice', 'La recherche de médias va être lancée.');

            return $this->redirectToRoute('app_medias');
        }

        return $this->render('medias/search.html.twig', ['form' => $form->createView()]);
    }
}
