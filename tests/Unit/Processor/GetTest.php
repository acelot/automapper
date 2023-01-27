<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\ExtractorInterface;
use Acelot\AutoMapper\ExtractorResolverInterface;
use Acelot\AutoMapper\Path\ParserInterface;
use Acelot\AutoMapper\Path\PartInterface;
use Acelot\AutoMapper\Path\PathInterface;
use Acelot\AutoMapper\Processor\Get;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Get
 */
final class GetTest extends TestCase
{
    public function testProcess_PassedNotUserValue_NeverCallsParserParseMethod(): void
    {
        $parser = $this->createMock(ParserInterface::class);
        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $parser
            ->expects(self::never())
            ->method('parse');

        $processor->process($context, $value);
    }

    public function testProcess_PassedUserValue_CallsParserParseMethod(): void
    {
        $parser = $this->createMock(ParserInterface::class);
        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test_value');

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $parser
            ->expects(self::once())
            ->method('parse')
            ->with(self::equalTo('some_path'));

        $processor->process($context, $value);
    }

    public function testProcess_PathHasParts_CallsExtractorResolverResolveMethod(): void
    {
        $path = $this->createMock(PathInterface::class);

        $part0 = $this->createMock(PartInterface::class);
        $part1 = $this->createMock(PartInterface::class);

        $path
            ->method('getParts')
            ->willReturn([$part0, $part1]);

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->method('parse')
            ->willReturn($path);

        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test_value');

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $extractor0 = $this->createMock(ExtractorInterface::class);
        $extractor0
            ->method('isExtractable')
            ->willReturn(true);

        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor1
            ->method('isExtractable')
            ->willReturn(true);

        $extractorResolver
            ->expects(self::exactly(2))
            ->method('resolve')
            ->withConsecutive([$part0], [$part1])
            ->willReturnOnConsecutiveCalls($extractor0, $extractor1);

        $processor->process($context, $value);
    }

    public function testProcess_PathHasParts_CallsExtractorsIsExtractableMethod(): void
    {
        $path = $this->createMock(PathInterface::class);

        $part0 = $this->createMock(PartInterface::class);
        $part1 = $this->createMock(PartInterface::class);

        $path
            ->method('getParts')
            ->willReturn([$part0, $part1]);

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->method('parse')
            ->willReturn($path);

        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test_value');

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $extractor0 = $this->createMock(ExtractorInterface::class);
        $extractor0
            ->method('extract')
            ->willReturn('extractor0_value');

        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor0
            ->method('extract')
            ->willReturn('extractor1_value');

        $extractorResolver
            ->method('resolve')
            ->withConsecutive([$part0], [$part1])
            ->willReturnOnConsecutiveCalls($extractor0, $extractor1);

        $extractor0
            ->expects(self::once())
            ->method('isExtractable')
            ->with(self::equalTo('test_value'))
            ->willReturn(true);

        $extractor1
            ->expects(self::once())
            ->method('isExtractable')
            ->with(self::equalTo('extractor0_value'))
            ->willReturn(true);

        $processor->process($context, $value);
    }

    public function testProcess_ExtractorIsExtractableMethodReturnsFalse_ReturnsNotFoundValue(): void
    {
        $path = $this->createMock(PathInterface::class);

        $part0 = $this->createMock(PartInterface::class);
        $part1 = $this->createMock(PartInterface::class);

        $path
            ->method('getParts')
            ->willReturn([$part0, $part1]);

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->method('parse')
            ->willReturn($path);

        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test_value');

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $extractor0 = $this->createMock(ExtractorInterface::class);
        $extractor0
            ->method('isExtractable')
            ->willReturn(false);

        $extractor1 = $this->createMock(ExtractorInterface::class);

        $extractorResolver
            ->method('resolve')
            ->withConsecutive([$part0], [$part1])
            ->willReturnOnConsecutiveCalls($extractor0, $extractor1);

        self::assertEquals(new NotFoundValue('some_path'), $processor->process($context, $value));
    }

    public function testProcess_PathHasParts_CallsExtractorsExtractMethod(): void
    {
        $path = $this->createMock(PathInterface::class);

        $part0 = $this->createMock(PartInterface::class);
        $part1 = $this->createMock(PartInterface::class);

        $path
            ->method('getParts')
            ->willReturn([$part0, $part1]);

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->method('parse')
            ->willReturn($path);

        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test_value');

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $extractor0 = $this->createMock(ExtractorInterface::class);
        $extractor0
            ->method('isExtractable')
            ->willReturn(true);

        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor1
            ->method('isExtractable')
            ->willReturn(true);

        $extractorResolver
            ->method('resolve')
            ->withConsecutive([$part0], [$part1])
            ->willReturnOnConsecutiveCalls($extractor0, $extractor1);

        $extractor0
            ->expects(self::once())
            ->method('extract')
            ->with(self::equalTo('test_value'))
            ->willReturn('extractor0_value');

        $extractor1
            ->expects(self::once())
            ->method('extract')
            ->with(self::equalTo('extractor0_value'))
            ->willReturn('extractor1_value');

        $processor->process($context, $value);
    }

    public function testProcess_ExtractorsExtractValue_ReturnsCorrectValue(): void
    {
        $path = $this->createMock(PathInterface::class);

        $part0 = $this->createMock(PartInterface::class);
        $part1 = $this->createMock(PartInterface::class);

        $path
            ->method('getParts')
            ->willReturn([$part0, $part1]);

        $parser = $this->createMock(ParserInterface::class);
        $parser
            ->method('parse')
            ->willReturn($path);

        $extractorResolver = $this->createMock(ExtractorResolverInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test_value');

        $processor = new Get($parser, $extractorResolver, 'some_path');

        $extractor0 = $this->createMock(ExtractorInterface::class);
        $extractor0
            ->method('isExtractable')
            ->willReturn(true);

        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor1
            ->method('isExtractable')
            ->willReturn(true);

        $extractorResolver
            ->method('resolve')
            ->withConsecutive([$part0], [$part1])
            ->willReturnOnConsecutiveCalls($extractor0, $extractor1);

        $extractor0
            ->method('extract')
            ->willReturn('extractor0_value');

        $extractor1
            ->method('extract')
            ->willReturn('extractor1_value');

        self::assertEquals(new UserValue('extractor1_value'), $processor->process($context, $value));
    }
}
