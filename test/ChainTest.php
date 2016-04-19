<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class ChainTest extends \PHPUnit_Framework_TestCase {
    public function testChainParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::chain'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Chain takes one parameter.'
        );
    }

    public function testChain()
    {
        $safeHalf = function ($x)
        {
            return $x % 2 == 0
                ? Maybe::of($x / 2)
                : Maybe::empty();
        };

        $this->assertEquals(
            $safeHalf(16)
                ->chain($safeHalf)
                ->fork(null),
            4,
            'Just chains.'
        );

        $this->assertEquals(
            $safeHalf(5)
                ->chain($safeHalf)
                ->fork(null),
            null,
            'Nothing chains.'
        );
    }
}
