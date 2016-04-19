<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;

class OfTest extends \PHPUnit_Framework_TestCase {
    public function testParameterCount() {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Maybe::of'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Takes one parameter.'
        );
    }

    public function testEmpty() {
        $this->assertInstanceOf(
            'PhpFp\Maybe\Constructor\Just',
            Maybe::of(2),
            'The empty value is a nothing.'
        );

        $this->assertEquals(
            Maybe::of(2)->fork(-1),
            2,
            'Creates a Just value.'
        );
    }
}
