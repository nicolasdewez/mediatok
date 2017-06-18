<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Field;
use AppBundle\Entity\Format;
use AppBundle\Entity\Type;
use AppBundle\Serializer\Groups;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @param EntityManagerInterface $manager
     * @param SerializerInterface    $serializer
     * @param Type                   $type
     *
     * @return JsonResponse
     *
     * @Route("/types/{id}/formats", name="app_api_formats_by_types", methods={"GET"})
     */
    public function formatsByTypeAction(EntityManagerInterface $manager, SerializerInterface $serializer, Type $type): JsonResponse
    {
        $formats = $manager
            ->getRepository(Format::class)
            ->findBy(['type' => $type, 'active' => true], ['title' => 'ASC'])
        ;

        return new JsonResponse(
            $serializer->serialize($formats, 'json', ['groups' => [Groups::API_GET]]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @param EntityManagerInterface $manager
     * @param SerializerInterface    $serializer
     * @param Type                   $type
     *
     * @return JsonResponse
     *
     * @Route("/types/{id}/fields", name="app_api_fields_by_types", methods={"GET"})
     */
    public function fieldsByTypeAction(EntityManagerInterface $manager, SerializerInterface $serializer, Type $type): JsonResponse
    {
        $fields = $manager
            ->getRepository(Field::class)
            ->getActiveAndSortedByType($type)
        ;

        return new JsonResponse(
            $serializer->serialize($fields, 'json', ['groups' => [Groups::API_GET]]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
