<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_application_name_can_be_normalized(): void
    {
        $this->assertSame('runtracker', strtolower('Runtracker'));
    }
}
