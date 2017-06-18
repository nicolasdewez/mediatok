<?php

namespace AppBundle\Consumer;

use AppBundle\Entity\Media;
use AppBundle\Model\ExportMedia;
use AppBundle\Service\AcceptExport;
use AppBundle\Service\ChoiceExporterMedia;
use AppBundle\Service\FilterExportMedia;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Export
{
    /** @var AcceptExport */
    private $acceptExport;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var FilterExportMedia */
    private $filter;

    /**
     * @param AcceptExport           $acceptExport
     * @param EntityManagerInterface $manager
     * @param FilterExportMedia      $filter
     * @param ChoiceExporterMedia    $choiceExporter
     */
    public function __construct(
        AcceptExport $acceptExport,
        EntityManagerInterface $manager,
        FilterExportMedia $filter,
        ChoiceExporterMedia $choiceExporter
    ) {
        $this->acceptExport = $acceptExport;
        $this->manager = $manager;
        $this->filter = $filter;
        $this->choiceExporter = $choiceExporter;
    }

    /**
     * @param AMQPMessage $message
     */
    public function execute(AMQPMessage $message)
    {
        /** @var ExportMedia $exportMedia */
        $exportMedia = $this->acceptExport->execute($message->getBody());
        $elements = $this->manager->getRepository(Media::class)->getByTypes($exportMedia->getTypes());
        $results = $this->filter->execute($exportMedia, $elements);
        $exporter = $this->choiceExporter->execute($exportMedia->getMode());
        $exporter->execute($results);
    }
}
