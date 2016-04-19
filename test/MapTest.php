<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class MapTest extends \PHPUnit_Framework_TestCase {
    public function testMapParameterCount() {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::map'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Map takes one parameter.'
        );
    }

    public function testMap() {
        $add2 = function ($x) {
            return $x + 2;
        };

        $this->assertEquals(
            Maybe::empty()
                ->map($add2)
                ->fork(-1),
            -1,
            'Nothing map.'
        );

        $this->assertEquals(
            Maybe::of(2)
                ->map($add2)
                ->fork(-1),
            4,
            'Just map.'
        );
    }
}
