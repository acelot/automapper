<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\ExtractorResolverInterface;
use Acelot\AutoMapper\Path\ParserInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class Get implements ProcessorInterface
{
    public function __construct(
        private ParserInterface $parser,
        private ExtractorResolverInterface $extractorResolver,
        private string $path
    ) {}

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        $path = $this->parser->parse($this->path);

        /** @var mixed $currentValue */
        $currentValue = $value->getValue();

        foreach ($path->getParts() as $part) {
            $extractor = $this->extractorResolver->resolve($part);

            if (!$extractor->isExtractable($currentValue)) {
                return new NotFoundValue($this->path);
            }

            /** @var mixed $currentValue */
            $currentValue = $extractor->extract($currentValue);
        }

        return new UserValue($currentValue);
    }
}
