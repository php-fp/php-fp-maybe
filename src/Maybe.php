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

    /**
     * Application, derived with chain.
     * @param Maybe $that The wrapped parameter.
     * @return Maybe The wrapped result.
     */
    abstract public function ap(Maybe $that) : Maybe;

    /**
     * Semigroup concatenation of two Maybe values.
     * @param Maybe $that Inner types must match!
     * @return Maybe The concatenated value.
     * @todo Make friendly with primitives.
     */
    abstract public function concat(Maybe $that) : Maybe;

    /**
     * PHP implemenattion of Haskell Maybe's >>=.
     * @param callable $f a -> Maybe b
     * @return Maybe The result of the function.
     */
    abstract public function chain(callable $f) : Maybe;

    /**
     * Check whether two Maybe values be equal.
     * @param Maybe $that Inner types should match!
     * @return bool
     * @todo Make this compatible with setoid inner values.
     */
    abstract public function equals(Maybe $that) : bool;

    /**
     * Fork this Maybe, with a default for Nothing.
     * @param mixed $default In case of Nothing.
     * @return mixed Whatever the Maybe's inner type is.
     */
    abstract public function fork($default);

    /**
     * Functor map, derived from chain.
     * @param callable $f The mapping function.
     * @return Maybe The outer structure is preserved.
     */
    abstract public function map(callable $f) : Maybe;

    /**
     * Left fold / reduction for Maybe.
     * @param callable $f The folding function.
     * @param mixed $x The accumulator value.
     * @return mixed The $x type and $f return type.
     */
    abstract public function reduce(callable $f, $x);
}
