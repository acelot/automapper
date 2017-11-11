<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Definition;

use Acelot\AutoMapper\Definition\Aggregate;
use Acelot\AutoMapper\Source\ArraySource;
use Acelot\AutoMapper\SourceInterface;
use PHPUnit\Framework\TestCase;

class AggregateTest extends TestCase
{
    public function testShouldAggregateValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => 'baz']);
        $definition = Aggregate::create(function (SourceInterface $source) {
            return $source->get('foo') . $source->get('boo');
        });

        $this->assertEquals('barbaz', $definition->getValue($source));
    }
}