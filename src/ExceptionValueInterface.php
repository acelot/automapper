<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Throwable;

interface ExceptionValueInterface extends ValueInterface
{
    public function getException(): Throwable;
}
