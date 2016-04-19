<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class ReduceTest extends \PHPUnit_Framework_TestCase
{
    public function testReduceParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::reduce'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            2,
            'Reduce takes two parameters.'
        );
    }

    public function testReduce()
    {
        $append = function ($xs, $x)
        {
            return array_merge($xs, [$x]);
        };

        $this->assertEquals(
            Maybe::empty()
                ->reduce($append, []),
            [],
            'Nothing reduction.'
        );

        $this->assertEquals(
            Maybe::of(2)
                ->reduce($append, []),
            [2],
            'Just reduction.'
        );
    }
}
