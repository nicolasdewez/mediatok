<?php

namespace tests\AppBundle\Service\Filter;

use AppBundle\Entity\Media;
use AppBundle\Model\ExportMedia;
use AppBundle\Service\Filter\FilterExportMedia;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class FilterExportMediaTest extends TestCase
{
    public function testIsMatchedByFilter()
    {
        $filter = new FilterExportMedia(new NullLogger());

        $class = new \ReflectionClass(FilterExportMedia::class);
        $method = $class->getMethod('isMatchedByFilter');
        $method->setAccessible(true);

        $export = new ExportMedia();

        $export->setFilter('^.*$');
        $this->assertSame(1, $method->invokeArgs($filter, [$export, 'name']));

        $export->setFilter('^[a-r]*$');
        $this->assertSame(0, $method->invokeArgs($filter, [$export, 'nicolas']));
    }

    public function testExecute()
    {
        $filter = new FilterExportMedia(new NullLogger());

        $mediaKo = new Media();
        $mediaKo->setTitle('nicolas');

        $mediaOk = new Media();
        $mediaOk->setTitle('name');

        $elements = [$mediaKo, $mediaOk];
        $expected = [$mediaOk];

        $this->assertSame(
            $expected,
            $filter->execute(
                (new ExportMedia())->setFilter('^[a-r]*$'),
                $elements
            )
        );
    }
}
