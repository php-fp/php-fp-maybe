<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class ConcatTest extends \PHPUnit_Framework_TestCase {
    public function testConcatParameterCount() {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::concat'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Concat takes one parameter.'
        );
    }

    public function testConcat() {
        $maybes = [Maybe::empty(), Maybe::of(2), Maybe::empty()];

        $acc = array_reduce(
            $maybes,
            function ($acc, $x) {
                return $acc->concat($x);
            },
            Maybe::empty()
        );

        $this->assertEquals(2, $acc->fork(5), 'Concatenates.');
    }
}
