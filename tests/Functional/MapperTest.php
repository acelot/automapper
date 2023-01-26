<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use ArrayIterator;
use PHPUnit\Framework\TestCase;
use stdClass;
use function Acelot\AutoMapper\{call,
    callCtx,
    condition,
    conditionCtx,
    explodeString,
    get,
    getFromCtx,
    ifEmpty,
    ifEqual,
    ifGt,
    ifGte,
    ifLt,
    ifLte,
    ifNotEqual,
    ifNull,
    ifNotFound,
    ignore,
    joinArray,
    map,
    mapIterable,
    marshalArray,
    marshalNestedArray,
    marshalNestedObject,
    marshalObject,
    pass,
    pipe,
    sortArray,
    toArray,
    toBool,
    toFloat,
    toInt,
    toKey,
    toMethod,
    toProp,
    toString,
    trimString,
    uniqueArray,
    value};

class MapperTest extends TestCase
{
    /**
     * @param ToArrayKey[] $fields
     *
     * @dataProvider mapperDataProvider
     */
    public function testArrayMarshallerFunctionality(array $fields, array $expectedResult): void
    {
        $context = new Context();
        $context->set('word_index', 11);
        $context->set('price_min', 50);

        self::assertEquals($expectedResult, marshalArray($context, $this->getSourceData(), ...$fields));
    }

    public function testObjectMarshallerFunctionality(): void
    {
        $context = new Context();

        $result = marshalObject(
            $context,
            $this->getSourceData(),
            toProp('id', get('[id]')),
            toProp('name', pipe(get('[name]'), trimString())),
            toProp('price', pipe(get('[price]'), trimString('$'), toFloat())),
        );

        $expectedResult = new stdClass();
        $expectedResult->id = 42;
        $expectedResult->name = 'Hoover Max Extract® 60 Pressure Pro';
        $expectedResult->price = 199.99;

        self::assertEquals($expectedResult, $result);
    }

    public function testObjectMapperFunctionality(): void
    {
        $context = new Context();
        $target = new TestClass(0, '', 0);

        map(
            $context,
            $this->getSourceData(),
            $target,
            toMethod('setId', get('[id]')),
            toMethod('setName', pipe(get('[name]'), trimString())),
            toMethod('setPrice', pipe(get('[price]'), trimString('$'), toFloat())),
        );

        self::assertEquals(
            [
                'id' => 42,
                'name' => 'Hoover Max Extract® 60 Pressure Pro',
                'price' => 199.99,
            ],
            [
                'id' => $target->getId(),
                'name' => $target->getName(),
                'price' => $target->getPrice(),
            ]
        );
    }

    public function getSourceData(): array
    {
        return [
            'id' => 42,
            'categories' => '20,4,1,',
            'name' => 'Hoover Max Extract® 60 Pressure Pro  ',
            'description' =>
                'The Max Extract® 60 Pressure Pro Deep Carpet Cleaner is an easy-to-use machine that removes dirt ' .
                'and grime with pressurized cleaning.  With the double chamber nozzle and automatic detergent ' .
                'system, you can power wash surfaces with even suction across a wide path.',
            'price' => '$199.99',
            'image' => [
                'url' => 'https://media.hoover.com/i/ttifloorcare/FH50220_FRONT?w=1000',
                'width' => 1000,
                'height' => 1000,
            ],
            'included' => [
                ' Sample Bottle of Cleaning Solution',
                'Upholstery Tool ',
                '  8-Foot Accessory Hose   ',
            ],
            'accessories' => [],
            'cleaning_path' => null,
            'unit_weight' => 26.4,
            'unit_width' => '16.1',
            'unit_height' => 25.4,
            'removable_brushed' => true,
            'clean_surge' => false,
            'reviews' => [4, 4, 4, 5, 5, 5, 5, 4, 2, 3],
            'nested_field' => [
                true,
                1,
                (object)[
                    'interesting field' => [
                        'some key' => new TestClass(1, 'name', 500)
                    ]
                ]
            ],
            'alphabet' => new ArrayIterator([
                'first' => 'a',
                'second' => 'b',
                'third' => 'c',
            ])
        ];
    }

    public function mapperDataProvider(): array
    {
        return [
            'no fields' => [
                [],
                [],
            ],
            'arrayJoin processor' => [
                [
                    toKey(
                        'included_joined',
                        pipe(
                            get('[included]'),
                            joinArray(' ')
                        )
                    ),
                    toKey(
                        'included_joined_by_comma',
                        pipe(
                            get('[included]'),
                            joinArray(', ')
                        )
                    ),
                ],
                [
                    'included_joined' => ' Sample Bottle of Cleaning Solution Upholstery Tool    8-Foot Accessory Hose   ',
                    'included_joined_by_comma' => ' Sample Bottle of Cleaning Solution, Upholstery Tool ,   8-Foot Accessory Hose   ',
                ],
            ],
            'arraySort processor' => [
                [
                    toKey(
                        'reviews_sorted',
                        pipe(
                            get('[reviews]'),
                            sortArray()
                        )
                    ),
                    toKey(
                        'reviews_reverse_sorted',
                        pipe(
                            get('[reviews]'),
                            sortArray(true)
                        )
                    ),
                ],
                [
                    'reviews_sorted' => [2, 3, 4, 4, 4, 4, 5, 5, 5, 5],
                    'reviews_reverse_sorted' => [5, 5, 5, 5, 4, 4, 4, 4, 3, 2],
                ],
            ],
            'arrayUnique processor' => [
                [
                    toKey(
                        'unique_reviews',
                        pipe(
                            get('[reviews]'),
                            uniqueArray()
                        )
                    ),
                    toKey(
                        'unique_reviews_with_keys',
                        pipe(
                            get('[reviews]'),
                            uniqueArray(true)
                        )
                    ),
                ],
                [
                    'unique_reviews' => [4, 5, 2, 3],
                    'unique_reviews_with_keys' => [0 => 4, 3 => 5, 8 => 2, 9 => 3],
                ],
            ],
            'call processor' => [
                [
                    toKey(
                        'sum_of_reviews',
                        pipe(
                            get('[reviews]'),
                            call('array_sum')
                        )
                    ),
                ],
                [
                    'sum_of_reviews' => 41,
                ],
            ],
            'call with context processor' => [
                [
                    toKey(
                        'word',
                        pipe(
                            get('[description]'),
                            explodeString(' '),
                            mapIterable(
                                pipe(
                                    trimString(),
                                    ifEmpty(ignore())
                                )
                            ),
                            toArray(),
                            callCtx(function (ContextInterface $ctx, $value) {
                                $wordIndex = $ctx->get('word_index');
                                return $value[$wordIndex];
                            })
                        )
                    ),
                ],
                [
                    'word' => 'easy-to-use',
                ],
            ],
            'condition processor' => [
                [
                    toKey(
                        'clean_surge',
                        pipe(
                            get('[clean_surge]'),
                            condition(
                                fn($value) => $value === true,
                                true: value('Clean surge available'),
                                false: value('Clean surge not available')
                            )
                        )
                    ),
                ],
                [
                    'clean_surge' => 'Clean surge not available',
                ],
            ],
            'condition with context processor' => [
                [
                    toKey(
                        'is_price_greater_than_min',
                        pipe(
                            get('[price]'),
                            trimString('$'),
                            toInt(),
                            conditionCtx(
                                fn(ContextInterface $ctx, $value) => $value > $ctx->get('price_min'),
                                true: value(true),
                                false: value(false)
                            )
                        )
                    ),
                ],
                [
                    'is_price_greater_than_min' => true,
                ],
            ],
            'get processor' => [
                [
                    toKey(
                        'id',
                        get('[id]')
                    ),
                ],
                [
                    'id' => 42,
                ],
            ],
            'get from context processor' => [
                [
                    toKey(
                        'price_min',
                        getFromCtx('price_min')
                    ),
                ],
                [
                    'price_min' => 50,
                ],
            ],
            'ifEmpty processor' => [
                [
                    toKey(
                        'accessories',
                        pipe(
                            get('[accessories]'),
                            ifEmpty(value('N/A'))
                        )
                    ),
                ],
                [
                    'accessories' => 'N/A',
                ],
            ],
            'ifPathNotFound processor' => [
                [
                    toKey(
                        'short_name',
                        pipe(
                            get('[short_name]'),
                            ifNotFound(value('N/A'))
                        )
                    ),
                ],
                [
                    'short_name' => 'N/A',
                ],
            ],
            'ifNull processor' => [
                [
                    toKey(
                        'cleaning_path',
                        pipe(
                            get('[cleaning_path]'),
                            ifNull(ignore())
                        )
                    ),
                ],
                [],
            ],
            'ifLt, ifLte processor' => [
                [
                    toKey(
                        'is_small_image',
                        pipe(
                            get('[image][width]'),
                            ifLt(1200, value(true), value(false))
                        )
                    ),
                    toKey(
                        'is_smaller_or_equal_1000',
                        pipe(
                            get('[image][width]'),
                            ifLte(1000, value(true), value(false))
                        )
                    ),
                ],
                [
                    'is_small_image' => true,
                    'is_smaller_or_equal_1000' => true,
                ],
            ],
            'ifGt, ifGte processor' => [
                [
                    toKey(
                        'is_big_image',
                        pipe(
                            get('[image][width]'),
                            ifGt(500, value(true), value(false))
                        )
                    ),
                    toKey(
                        'is_bigger_or_equal_1000',
                        pipe(
                            get('[image][width]'),
                            ifGte(1000, value(true), value(false))
                        )
                    ),
                ],
                [
                    'is_big_image' => true,
                    'is_bigger_or_equal_1000' => true,
                ],
            ],
            'ifEqual, ifNotEqual processor' => [
                [
                    toKey(
                        'parent_cat',
                        pipe(
                            get('[id]'),
                            ifEqual(42, value(10), value(20)),
                        )
                    ),
                    toKey(
                        'child_cat',
                        pipe(
                            get('[id]'),
                            ifNotEqual(42, value(100), value(200)),
                        )
                    ),
                ],
                [
                    'parent_cat' => 10,
                    'child_cat' => 200,
                ],
            ],
            'mapIterable, toArray processor' => [
                [
                    toKey(
                        'reviews',
                        pipe(
                            get('[reviews]'),
                            mapIterable(call(fn($v) => $v * 2)),
                            toArray()
                        )
                    ),
                ],
                [
                    'reviews' => [8, 8, 8, 10, 10, 10, 10, 8, 4, 6],
                ],
            ],
            'ignore processor' => [
                [
                    toKey(
                        'accessories',
                        pipe(
                            get('[accessories]'),
                            ignore()
                        )
                    ),
                ],
                [],
            ],
            'override processor' => [
                [
                    toKey(
                        'description',
                        pipe(
                            get('[description]'),
                            value('My description')
                        )
                    ),
                ],
                [
                    'description' => 'My description',
                ],
            ],
            'pass processor' => [
                [
                    toKey(
                        'same_value',
                        pipe(
                            value('Hello, world!'),
                            pass()
                        )
                    ),
                ],
                [
                    'same_value' => 'Hello, world!',
                ],
            ],
            'pipeline processor' => [
                [
                    toKey(
                        'price',
                        pipe(
                            get('[price]'),
                            trimString(' $'),
                            toFloat()
                        )
                    ),
                ],
                [
                    'price' => 199.99,
                ],
            ],
            'stringExplode processor' => [
                [
                    toKey(
                        'cats_ids',
                        pipe(
                            get('[categories]'),
                            explodeString(',')
                        )
                    ),
                ],
                [
                    'cats_ids' => ['20', '4', '1', ''],
                ],
            ],
            'stringTrim processor' => [
                [
                    toKey(
                        'name',
                        pipe(
                            get('[name]'),
                            trimString()
                        )
                    ),
                    toKey(
                        'name_without_H',
                        pipe(
                            get('[name]'),
                            trimString('H')
                        )
                    ),
                ],
                [
                    'name' => 'Hoover Max Extract® 60 Pressure Pro',
                    'name_without_H' => 'oover Max Extract® 60 Pressure Pro  ',
                ],
            ],
            'toBool processor' => [
                [
                    toKey(
                        'has_cleaning_path',
                        pipe(
                            get('[cleaning_path]'),
                            toBool()
                        )
                    ),
                ],
                [
                    'has_cleaning_path' => false,
                ],
            ],
            'toFloat processor' => [
                [
                    toKey(
                        'unit_width',
                        pipe(
                            get('[unit_width]'),
                            toFloat()
                        )
                    ),
                    toKey(
                        'null_to_zero',
                        pipe(
                            value(null),
                            toFloat(true)
                        )
                    ),
                ],
                [
                    'unit_width' => 16.1,
                    'null_to_zero' => 0.0,
                ],
            ],
            'toInt processor' => [
                [
                    toKey(
                        'unit_height',
                        pipe(
                            get('[unit_height]'),
                            toInt()
                        )
                    ),
                    toKey(
                        'null_to_zero',
                        pipe(
                            value(null),
                            toInt(true)
                        )
                    ),
                ],
                [
                    'unit_height' => 25,
                    'null_to_zero' => 0,
                ],
            ],
            'toString processor' => [
                [
                    toKey(
                        'id_string',
                        pipe(
                            get('[id]'),
                            toString()
                        )
                    ),
                ],
                [
                    'id_string' => '42',
                ],
            ],
            'value processor' => [
                [
                    toKey(
                        'id',
                        value(100500)
                    ),
                ],
                [
                    'id' => 100500,
                ],
            ],
            // Complex cases
            'cleanup array' => [
                [
                    toKey(
                        'cats_ids',
                        pipe(
                            get('[categories]'),
                            explodeString(','),
                            mapIterable(
                                pipe(
                                    toInt(),
                                    ifEmpty(ignore())
                                )
                            ),
                            toArray()
                        )
                    ),
                ],
                [
                    'cats_ids' => [20, 4, 1],
                ],
            ],
            'nested array mapper' => [
                [
                    toKey(
                        'image',
                        pipe(
                            get('[image]'),
                            marshalNestedArray(
                                toKey(
                                    'url',
                                    pipe(
                                        get('[url]'),
                                        call(fn(string $url) => preg_replace('/^https/', 'http', $url))
                                    )
                                ),
                                toKey(
                                    'total_pixels',
                                    pipe(
                                        get('@'),
                                        call(fn(array $image) => $image['width'] * $image['height'])
                                    )
                                )
                            )
                        )
                    ),
                ],
                [
                    'image' => [
                        'url' => 'http://media.hoover.com/i/ttifloorcare/FH50220_FRONT?w=1000',
                        'total_pixels' => 1_000_000,
                    ],
                ],
            ],
            'nested object mapper' => [
                [
                    toKey(
                        'image',
                        pipe(
                            get('[image]'),
                            marshalNestedObject(
                                toProp(
                                    'url',
                                    get('[url]')
                                ),
                                toProp(
                                    'width',
                                    get('[width]')
                                ),
                                toProp(
                                    'height',
                                    get('[height]')
                                )
                            )
                        )
                    ),
                ],
                [
                    'image' => (object)[
                        'url' => 'https://media.hoover.com/i/ttifloorcare/FH50220_FRONT?w=1000',
                        'width' => 1_000,
                        'height' => 1_000,
                    ],
                ],
            ],
            'deep path' => [
                [
                    toKey(
                        'price',
                        get('[nested_field][#last]->{interesting field}[some key]->getPrice()')
                    ),
                ],
                [
                    'price' => 500,
                ],
            ],
            'aggregate' => [
                [
                    toKey(
                        'total_pixels',
                        pipe(
                            get('@'),
                            marshalNestedArray(
                                toKey(0, get('[image][width]')),
                                toKey(1, get('[image][height]')),
                            ),
                            call(fn(array $agg) => $agg[0] * $agg[1])
                        )
                    )
                ],
                [
                    'total_pixels' => 1_000_000,
                ],
            ],
            //endregion
        ];
    }
}
