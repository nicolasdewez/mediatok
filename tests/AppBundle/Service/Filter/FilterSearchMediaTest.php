<?php

namespace tests\AppBundle\Service\Filter;

use AppBundle\Model\File;
use AppBundle\Model\SearchMedia;
use AppBundle\Service\Filter\FilterSearchMedia;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class FilterSearchMediaTest extends TestCase
{
    public function testIsMatchedByFilter()
    {
        $filter = new FilterSearchMedia(new NullLogger());

        $class = new \ReflectionClass(FilterSearchMedia::class);
        $method = $class->getMethod('isMatchedByFilter');
        $method->setAccessible(true);

        $search = new SearchMedia();

        $search->setFilter('^.*$');
        $this->assertSame(1, $method->invokeArgs($filter, [$search, 'name']));

        $search->setFilter('^[a-r]*$');
        $this->assertSame(0, $method->invokeArgs($filter, [$search, 'nicolas']));
    }

    public function testExecuteFileMode()
    {
        $filter = new FilterSearchMedia(new NullLogger());

        $file1 = new File('file1');
        $file2 = new File('file2');
        $file3 = new File('File3');
        $file4 = new File('file4');
        $directory1 = new File('dir', true, [$file2, $file3]);
        $directory2 = new File('directory', true, [$file4]);

        $elements = [$file1, $directory1, $directory2];
        $expected = ['file1', 'file2', 'file4'];

        $this->assertSame(
            $expected,
            $filter->execute(
                (new SearchMedia())
                    ->setFilter('^[a-r0-9]*$')
                    ->setFileMode(SearchMedia::FILE_MODE_FILES),
                $elements
            )
        );
    }

    public function testExecuteDirectoryMode()
    {
        $filter = new FilterSearchMedia(new NullLogger());

        $file1 = new File('file1');
        $file2 = new File('file2');
        $file3 = new File('file3');
        $directory1 = new File('dir', true, [$file2]);
        $directory2 = new File('directory', true, [$file3]);

        $elements = [$file1, $directory1, $directory2];
        $expected = ['dir'];

        $this->assertSame(
            $expected,
            $filter->execute(
                (new SearchMedia())
                    ->setFilter('^[a-r0-9]*$')
                    ->setFileMode(SearchMedia::FILE_MODE_DIRECTORIES),
                $elements
            )
        );
    }
}
