<?php

namespace tests\AppBundle\Tests\Controller;

use AppBundle\Entity\Format;
use AppBundle\Entity\Media;
use AppBundle\Entity\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MediaControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /** @var Type */
    private $type;

    /** @var Format */
    private $format;

    /** @var Media */
    private $media;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->format = new Format();
        $this->format->setTitle('format');

        $this->type = new Type();
        $this->type
            ->setTitle('type')
            ->setFormats(new ArrayCollection([$this->format]))
        ;

        $this->format->setType($this->type);

        $this->media = new Media();
        $this->media
            ->setTitle('media')
            ->setType($this->type)
            ->setFormat($this->format)
        ;

        $this->client = static::createClient();
        $manager = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');

        $manager->persist($this->type);
        $manager->persist($this->format);
        $manager->persist($this->media);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $manager = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');

        $manager->remove($this->type);
        $manager->remove($this->format);
        $manager->remove($this->media);
        $manager->flush();
    }

    /**
     * @param string $uri
     *
     * @dataProvider providerUris
     */
    public function testUris(string $uri)
    {
        $this->client->request('GET', $uri);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditMedia()
    {
        $this->client->request('GET', sprintf('/medias/edit/%d', $this->media->getId()));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function providerUris(): array
    {
        return [
            ['/medias'],
            ['/medias/add'],
            ['/medias/search'],
        ];
    }
}
