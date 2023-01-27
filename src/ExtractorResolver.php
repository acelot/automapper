<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Exception\UnknownPartException;
use Acelot\AutoMapper\Extractor;
use Acelot\AutoMapper\Path\Part;
use Acelot\AutoMapper\Path\PartInterface;

final class ExtractorResolver implements ExtractorResolverInterface
{
    public function resolve(PartInterface $part): ExtractorInterface
    {
        if ($part instanceof Part\ArrayKey) {
            return new Extractor\FromArrayKey($part->getKey());
        }

        if ($part instanceof Part\ArrayKeyFirst) {
            return new Extractor\FromArrayKeyFirst();
        }

        if ($part instanceof Part\ArrayKeyLast) {
            return new Extractor\FromArrayKeyLast();
        }

        if ($part instanceof Part\ObjectMethod) {
            return new Extractor\FromObjectMethod($part->getMethod());
        }

        if ($part instanceof Part\ObjectProp) {
            return new Extractor\FromObjectProp($part->getProperty());
        }

        if ($part instanceof Part\SelfPointer) {
            return new Extractor\FromSelf();
        }

        throw new UnknownPartException($part);
    }
}
