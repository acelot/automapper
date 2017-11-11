<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper;
use function Acelot\AutoMapper\{
    field, value, from, aggregate
};
use Acelot\AutoMapper\SourceInterface;
use PHPUnit\Framework\TestCase;

class AutoMapperTest extends TestCase
{
    public function arrayMarshallingProvider()
    {
        return [
            [
                AutoMapper::create(),
                ['id' => 100],
                []
            ],
            [
                AutoMapper::create(
                    field('id', from('id'))
                ),
                ['id' => 100],
                ['id' => 100]
            ],
            [
                AutoMapper::create(
                    field('id', from('id')->convert('sqrt'))
                ),
                ['id' => 100],
                ['id' => 10]
            ],
            [
                AutoMapper::create(
                    field('id', from('id')->convert('intval')),
                    field('title', from('text')->trim()),
                    field('url', from('link')->trim()),
                    field('isActive', from('is_active')->convert(function ($value) {
                        return $value === 1;
                    })->default(false)),
                    field('count', value(100)),
                    field('isEmpty', aggregate(function (SourceInterface $source) {
                        return !empty($source->get('title')) && !empty($source->get('url'));
                    }))
                )->ignoreAllMissing(),
                [
                    'id' => '100',
                    'text' => 'Very useful text. ',
                    'is_active' => 0
                ],
                [
                    'id' => 100,
                    'title' => 'Very useful text.',
                    'isActive' => false,
                    'count' => 100,
                    'isEmpty' => false
                ]
            ]
        ];
    }

    /**
     * @dataProvider arrayMarshallingProvider
     */
    public function testArrayMarshalling(AutoMapper $mapper, array $source, array $expected)
    {
        $this->assertEquals($expected, $mapper->marshalArray($source));
    }
}