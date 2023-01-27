<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Fixtures;

interface TestGetInterface
{
    public function get(mixed ...$args): mixed;
}
