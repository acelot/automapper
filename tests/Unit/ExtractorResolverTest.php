<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit;

use Acelot\AutoMapper\Exception\UnknownPartException;
use Acelot\AutoMapper\Extractor;
use Acelot\AutoMapper\ExtractorResolver;
use Acelot\AutoMapper\Path\Part;
use Acelot\AutoMapper\Path\PartInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\ExtractorResolver
 */
final class ExtractorResolverTest extends TestCase
{
    /**
     * @param string $partClass
     * @param array $partArgs
     * @param string $extractorClass
     *
     * @dataProvider resolverPartsProvider
     */
    public function testResolve_PassedPart_ReturnsCorrectInstanceOfExtractor(
        string $partClass,
        array  $partArgs,
        string $extractorClass
    ): void
    {
        $resolver = new ExtractorResolver();
        $extractor = $resolver->resolve(new $partClass(...$partArgs));

        self::assertInstanceOf($extractorClass, $extractor);
    }


    public function resolverPartsProvider(): array
    {
        return [
            [
                Part\ArrayKey::class,
                ['key'],
                Extractor\FromArrayKey::class
            ],
            [
                Part\ArrayKeyFirst::class,
                [],
                Extractor\FromArrayKeyFirst::class
            ],
            [
                Part\ArrayKeyLast::class,
                [],
                Extractor\FromArrayKeyLast::class
            ],
            [
                Part\ObjectMethod::class,
                ['method'],
                Extractor\FromObjectMethod::class
            ],
            [
                Part\ObjectProp::class,
                ['prop'],
                Extractor\FromObjectProp::class
            ],
            [
                Part\SelfPointer::class,
                [],
                Extractor\FromSelf::class
            ],
        ];
    }

    public function testResolve_PassedArrayKeyPart_ReturnsFromArrayKeyExtractorWithKey(): void
    {
        $resolver = new ExtractorResolver();

        /** @var Extractor\FromArrayKey $extractor */
        $extractor = $resolver->resolve(new Part\ArrayKey('key'));

        self::assertSame('key', $extractor->getKey());
    }

    public function testResolve_PassedObjectMethodPart_ReturnsFromObjectMethodExtractorWithMethod(): void
    {
        $resolver = new ExtractorResolver();

        /** @var Extractor\FromObjectMethod $extractor */
        $extractor = $resolver->resolve(new Part\ObjectMethod('method'));

        self::assertSame('method', $extractor->getMethod());
    }

    public function testResolve_PassedObjectPropPart_ReturnsFromObjectPropExtractorWithProp(): void
    {
        $resolver = new ExtractorResolver();

        /** @var Extractor\FromObjectProp $extractor */
        $extractor = $resolver->resolve(new Part\ObjectProp('prop'));

        self::assertSame('prop', $extractor->getProperty());
    }

    public function testResolve_PassedUnknownPart_ThrowsUnknownPartException(): void
    {
        $resolver = new ExtractorResolver();
        $part = $this->createMock(PartInterface::class);

        self::expectExceptionObject(new UnknownPartException($part));

        $resolver->resolve($part);
    }
}
