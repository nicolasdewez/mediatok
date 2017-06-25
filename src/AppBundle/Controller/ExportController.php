<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ExportMediaType;
use AppBundle\Model\ExportMedia;
use AppBundle\Service\Tasker\AskExport;
use AppBundle\Service\Manager\DeleteExport;
use AppBundle\Service\Manager\ListExports;
use AppBundle\Service\Manager\ShowExport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/exports")
 */
class ExportController extends Controller
{
    /**
     * @param ListExports $listExports
     *
     * @return Response
     *
     * @Route("", name="app_exports", methods={"GET"})
     */
    public function listAction(ListExports $listExports): Response
    {
        $elements = $listExports->execute();

        return $this->render('exports/list.html.twig', ['elements' => $elements]);
    }

    /**
     * @param ShowExport $showExport
     * @param string     $file
     *
     * @return Response
     *
     * @Route("/show/{file}", name="app_exports_get", methods={"GET"})
     */
    public function getAction(ShowExport $showExport, string $file): Response
    {
        return $showExport->getResponse($file);
    }

    /**
     * @param DeleteExport $deleteExport
     * @param string       $file
     *
     * @return Response
     *
     * @Route("/delete/{file}", name="app_exports_delete", methods={"GET"})
     */
    public function deleteAction(DeleteExport $deleteExport, string $file): Response
    {
        try {
            $deleteExport->process($file);
        } catch (FileNotFoundException $exception) {
            $this->addFlash('notice', 'Une erreur est survenue lors de la suppression de l\'export.');

            return $this->redirectToRoute('app_exports');
        }

        $this->addFlash('notice', 'L\'export a été supprimé.');

        return $this->redirectToRoute('app_exports');
    }

    /**
     * @param Request   $request
     * @param AskExport $askExport
     *
     * @return Response
     *
     * @Route("/ask", name="app_exports_ask", methods={"GET", "POST"})
     */
    public function askAction(Request $request, AskExport $askExport): Response
    {
        $exportMedia = new ExportMedia();
        $form = $this->createForm(ExportMediaType::class, $exportMedia);
        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $askExport->execute($exportMedia);
            $this->addFlash('notice', 'L\'export de médias va être lancé.');

            return $this->redirectToRoute('app_exports');
        }

        return $this->render('exports/ask.html.twig', ['form' => $form->createView()]);
    }
}
