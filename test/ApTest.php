<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class ApTest extends \PHPUnit_Framework_TestCase {
    public function testApParameterCount() {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::ap'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Ap takes one parameter.'
        );
    }

    public function testAp() {
        $add = function ($x) {
            return function ($y) use ($x) {
                return $x + $y;
            };
        };

        $a = Maybe::of(2);
        $b = Maybe::of(4);

        $this->assertEquals(
            Maybe::of($add)->ap($a)->ap($b)
                ->fork(null),
            6,
            'Applies to a Just.'
        );

        $this->assertEquals(
            Maybe::empty()->ap($a)->fork(-1),
            2,
            'Applies to a Nothing.'
        );
    }
}
