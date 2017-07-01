<?php

namespace tests\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExportControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->client = static::createClient();
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

    /**
     * @return array
     */
    public function providerUris(): array
    {
        return [
            ['/exports'],
            ['/exports/ask'],
        ];
    }
}
