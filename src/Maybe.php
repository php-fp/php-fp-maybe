<?php

namespace PhpFp\Maybe;

use PhpFp\Maybe\Constructor\{Just, Nothing};

/**
 * An OO-looking implementation of Maybe in PHP.
 */
abstract class Maybe
{
    /**
     * Empty value for the monoid definition.
     * @return Maybe The Nothing value.
     */
    public static function empty() : Nothing
    {
        return new Nothing;
    }

    /**
     * Applicative constructor for Maybe.
     * @param mixed $x The value to be wrapped.
     * @return A new Just-constructed type.
     */
    public static function of($x) : Just
    {
        return new Just($x);
    }
}
