<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class ShowExport
{
    /** @var string */
    private $exportPath;

    /**
     * @param string $exportPath
     */
    public function __construct(string $exportPath)
    {
        $this->exportPath = $exportPath;
    }

    /**
     * @param string $file
     *
     * @return Response
     */
    public function getResponse(string $file): Response
    {
        $path = sprintf('%s/%s', $this->exportPath, $file);
        $contentType = $this->getContentType($path);

        $response = new Response(file_get_contents($path));
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file));

        return $response;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function getContentType(string $path): string
    {
        $info = pathinfo($path);
        if (!isset($info['extension'])) {
            return 'text/csv';
        }

        switch ($info['extension']) {
            case 'csv': return 'text/csv';
            case 'pdf': return 'application/pdf';
        }

        return 'text/csv';
    }
}
