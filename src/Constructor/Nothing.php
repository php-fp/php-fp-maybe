<?php
declare(strict_types=1);

namespace PhpFp\Maybe\Constructor;

use PhpFp\Maybe\Maybe;

/**
 * An OO-looking implementation of the Nothing constructor in PHP.
 */
final class Nothing extends Maybe
{
    /**
     * Application. Nothing acts as identity.
     * @param Maybe $that The wrapped parameter.
     * @return Maybe The parameter.
     */
    public function ap(Maybe $that) : Maybe
    {
        return $that;
    }

    /**
     * Semigroup concatenation of two Maybe values.
     * @param Maybe $that The Maybe to concatenate.
     * @return Maybe The $that value, regardless.
     */
    public function concat(Maybe $that) : Maybe
    {
        return $that;
    }

    /**
     * Chain is basically a no-op for Nothing.
     * @param callable $f a -> Maybe b
     * @return Maybe $this (Nothing).
     */
    public function chain(callable $_) : Maybe
    {
        return $this;
    }

    /**
     * Check whether two Maybe values be equal.
     * @param Maybe $that Inferred inner types should match!
     * @return bool
     */
    public function equals(Maybe $that) : bool
    {
        return $that instanceof Nothing;
    }

    /**
     * Fork this Maybe, with a default for Maybe.
     * @param mixed $default Returned for Nothing.
     * @return mixed For Nothing, this is $default.
     */
    public function fork($default)
    {
        return $default;
    }

    /**
     * Functor map, derived from chain.
     * @param callable $f The mapping function.
     * @return Maybe The outer structure is preserved.
     */
    public function map(callable $_) : Maybe
    {
        return $this;
    }

    /**
     * Fold for Nothing - returns the accumulator.
     * @param callable $f The folding function.
     * @param mixed $x The accumulator value.
     * @return mixed The $x type and $f return type.
     */
    public function reduce(callable $_, $x)
    {
        return $x;
    }
}
