<?php

namespace PhpFp\Maybe\Test;

use PhpFp\Maybe\Maybe;
use PhpFp\Maybe\Constructor\{Just, Nothing};

class Value
{
    public function __construct($x)
    {
        $this->value = $x;
    }

    public function equals($that)
    {
        return $this->value === $that->value;
    }
}

class EqualsTest extends \PHPUnit_Framework_TestCase
{
    public function testEqualsParameterCount()
    {
        $count = (new \ReflectionMethod('PhpFp\Maybe\Constructor\Just::equals'))
            ->getNumberOfParameters();

        $this->assertEquals(
            $count,
            1,
            'Equals takes one parameter.'
        );
    }

    public function testEquals()
    {
        $a = Maybe::empty();
        $b = Maybe::of(new Value(2));
        $c = Maybe::of(new Value(3));
        $d = Maybe::of(new Value(3));

        $this->assertEquals(
            $a->equals($a),
            true,
            'Two Nothing values.'
        );

        $this->assertEquals(
            $a->equals($b),
            false,
            'Just and Nothing.'
        );

        $this->assertEquals(
            $b->equals($c),
            false,
            'Unequal values.'
        );

        $this->assertEquals(
            $c->equals($d),
            true,
            'Equal values.'
        );
    }
}
