<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Value;

use Acelot\AutoMapper\ValueInterface;
use Throwable;

interface ExceptionValueInterface extends ValueInterface
{
    public function getException(): Throwable;
}
