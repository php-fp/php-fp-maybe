<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class ForkTest extends \PHPUnit_Framework_TestCase {
    public function testForkParameterCount() {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::fork'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Fork takes one parameter.'
        );
    }

    public function testFork() {
        $this->assertEquals(
            Maybe::of(2)
                ->fork(4),
            2,
            'Just fork.'
        );

        $this->assertEquals(
            Maybe::empty()
                ->fork(4),
            4,
            'Nothing fork.'
        );
    }
}
