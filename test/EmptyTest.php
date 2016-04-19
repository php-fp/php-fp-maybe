<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;

class EmptyTest extends \PHPUnit_Framework_TestCase {
    public function testEmptyParameterCount() {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Maybe::empty'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            0,
            'Takes no parameters.'
        );
    }

    public function testEmpty() {
        $this->assertInstanceOf(
            'PhpFp\Maybe\Constructor\Nothing',
            Maybe::empty(),
            'The empty value is a nothing.'
        );
    }
}
