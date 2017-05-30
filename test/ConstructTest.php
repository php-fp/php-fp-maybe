<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class ConstructTest extends \PHPUnit_Framework_TestCase
{
    public function testJustParameterCount()
    {
        $count = (new \ReflectionClass('PhpFp\Maybe\Constructor\Just'))
            ->getConstructor()->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Takes one parameter.'
        );
    }

    public function testConstruct()
    {
        $this->assertEquals(
            (Maybe::just(2))->fork(null),
            2,
            'Constructs a Just.'
        );

        $this->assertEquals(
            Maybe::nothing()->fork(-1),
            -1,
            'Constructs a Nothing.'
        );
    }
}
