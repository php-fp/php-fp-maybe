<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;

class FromNullableTest extends \PHPUnit_Framework_TestCase
{
    public function testParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Maybe::fromNullable'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Takes one parameter.'
        );
    }

    public function testFromNullable()
    {
        $this->assertInstanceOf(
            'PhpFp\Maybe\Constructor\Nothing',
            Maybe::fromNullable(null),
            'Nulls become Nothing values.'
        );

        $this->assertEquals(
            Maybe::fromNullable(2)->fork(null),
            2,
            'Anything else becomes Just-wrapped.'
        );
    }
}
