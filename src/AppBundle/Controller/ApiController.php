<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Field;
use AppBundle\Entity\Format;
use AppBundle\Entity\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @param Type $type
     *
     * @return JsonResponse
     *
     * @Route("/types/{id}/formats", name="app_api_formats_by_types", methods={"GET"})
     */
    public function formatsByTypeAction(Type $type): JsonResponse
    {
        $formats = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository(Format::class)
            ->findBy(['type' => $type, 'active' => true], ['title' => 'ASC'])
        ;

        return new JsonResponse(
            $this->get('serializer')->serialize($formats, 'json', ['groups' => ['api_get']]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param Type $type
     *
     * @return JsonResponse
     *
     * @Route("/types/{id}/fields", name="app_api_fields_by_types", methods={"GET"})
     */
    public function fieldsByTypeAction(Type $type): JsonResponse
    {
        $fields = $this->get('doctrine.orm.default_entity_manager')
            ->getRepository(Field::class)
            ->getActiveAndSortedByType($type)
        ;

        return new JsonResponse(
            $this->get('serializer')->serialize($fields, 'json', ['groups' => ['api_get']]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
