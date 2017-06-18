<?php

namespace AppBundle\Consumer;

use AppBundle\Model\SearchMedia;
use AppBundle\Service\AcceptSearch;
use AppBundle\Service\FilterSearchMedia;
use AppBundle\Service\FinderMedia;
use AppBundle\Service\SaverMedia;
use PhpAmqpLib\Message\AMQPMessage;

class Search
{
    /** @var AcceptSearch */
    private $acceptSearch;

    /** @var FinderMedia */
    private $finder;

    /** @var FilterSearchMedia */
    private $filter;

    /** @var SaverMedia */
    private $saver;

    /**
     * @param AcceptSearch      $acceptSearch
     * @param FinderMedia       $finder
     * @param FilterSearchMedia $filter
     * @param SaverMedia        $saver
     */
    public function __construct(AcceptSearch $acceptSearch, FinderMedia $finder, FilterSearchMedia $filter, SaverMedia $saver)
    {
        $this->acceptSearch = $acceptSearch;
        $this->finder = $finder;
        $this->filter = $filter;
        $this->saver = $saver;
    }

    /**
     * @param AMQPMessage $message
     */
    public function execute(AMQPMessage $message)
    {
        /** @var SearchMedia $searchMedia */
        $searchMedia = $this->acceptSearch->execute($message->getBody());
        $elements = $this->finder->execute($searchMedia);
        $medias = $this->filter->execute($searchMedia, $elements);
        $this->saver->execute($searchMedia, $medias);
    }
}
