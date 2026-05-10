<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ApplicationNameTest extends TestCase
{
    public function test_application_name_can_be_normalized(): void
    {
        $this->assertSame('runtracker', strtolower('Runtracker'));
    }
}
