<?php

namespace tests\AppBundle\Tests\Controller;

use AppBundle\Entity\Format;
use AppBundle\Entity\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /** @var Type */
    private $type;

    /** @var Format */
    private $format;

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

        $this->client = static::createClient();
        $manager = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');

        $manager->persist($this->type);
        $manager->persist($this->format);
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
        $manager->flush();
    }

    /**
     * @param string $uri
     *
     * @dataProvider providerSimpleUris
     */
    public function testSimplesUris(string $uri)
    {
        $this->client->request('GET', $uri);

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditType()
    {
        $this->client->request('GET', sprintf('/configuration/types/edit/%d', $this->type->getId()));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testListFormats()
    {
        $this->client->request('GET', sprintf('/configuration/types/%d/formats', $this->type->getId()));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEditFormat()
    {
        $this->client->request('GET', sprintf('/configuration/types/%d/formats/edit/%d', $this->type->getId(), $this->format->getId()));

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function providerSimpleUris(): array
    {
        return [
            ['/configuration'],
            ['/configuration/types/add'],
        ];
    }
}
