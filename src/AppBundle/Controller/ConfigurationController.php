<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Format;
use AppBundle\Entity\Type;
use AppBundle\Form\FormatType;
use AppBundle\Form\TypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/configuration")
 */
class ConfigurationController extends Controller
{
    /**
     * @return Response
     *
     * @Route("", name="app_types", methods={"GET"})
     */
    public function listTypesAction(): Response
    {
        $elements = $this->get('doctrine')->getRepository(Type::class)->findBy([], ['title' => 'ASC']);

        return $this->render('configuration/listTypes.html.twig', ['elements' => $elements]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/types/add", name="app_types_add", methods={"GET", "POST"})
     */
    public function addTypeAction(Request $request): Response
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->persist($type);
            $manager->flush();

            $this->addFlash('notice', 'Type ajouté.');

            return $this->redirectToRoute('app_types');
        }

        return $this->render('configuration/addType.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Type    $type
     *
     * @return Response
     *
     * @Route("/types/edit/{id}", name="app_types_edit", methods={"GET", "POST"})
     */
    public function editTypeAction(Request $request, Type $type): Response
    {
        $form = $this->createForm(TypeType::class, $type);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->flush();

            $this->addFlash('notice', 'Type modifié.');

            return $this->redirectToRoute('app_types');
        }

        return $this->render('configuration/editType.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Type $type
     *
     * @return Response
     *
     * @Route("/types/{id}/formats", name="app_formats", methods={"GET"})
     */
    public function listFormatsAction(Type $type): Response
    {
        $elements = $this->get('doctrine')->getRepository(Format::class)->findBy(['type' => $type], ['title' => 'ASC']);

        return $this->render('configuration/listFormats.html.twig', ['type' => $type, 'elements' => $elements]);
    }

    /**
     * @param Request $request
     * @param Type    $type
     *
     * @return Response
     *
     * @Route("/types/{id}/formats/add", name="app_formats_add", methods={"GET", "POST"})
     */
    public function addFormatAction(Request $request, Type $type): Response
    {
        $format = new Format();
        $format->setType($type);

        $form = $this->createForm(FormatType::class, $format);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->persist($format);
            $manager->flush();

            $this->addFlash('notice', 'Format ajouté.');

            return $this->redirectToRoute('app_formats', ['id' => $type->getId()]);
        }

        return $this->render('configuration/addFormat.html.twig', ['type' => $type, 'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param Type    $type
     * @param Format  $format
     *
     * @return Response
     *
     * @Route("/types/{type_id}/formats/edit/{id}", name="app_formats_edit", methods={"GET", "POST"})
     * @ParamConverter("type", class="AppBundle:Type", options={"id" = "type_id"})
     */
    public function editFormatAction(Request $request, Type $type, Format $format): Response
    {
        $form = $this->createForm(FormatType::class, $format);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->flush();

            $this->addFlash('notice', 'Format modifié.');

            return $this->redirectToRoute('app_formats', ['id' => $type->getId()]);
        }

        return $this->render('configuration/editFormat.html.twig', ['type' => $type, 'form' => $form->createView()]);
    }
}
