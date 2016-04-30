# The Maybe Monad for PHP. [![Build Status](https://travis-ci.org/php-fp/php-fp-maybe.svg?branch=master)](https://travis-ci.org/php-fp/php-fp-maybe)

## Intro

The Maybe monad is one of the first monads that you're likely to encounter when reading about monadic functional programming and, more generally, some very basic error handling. Its purpose is to model early-abort computation. Take the following example:

```php
<?php

function prop($key, $xs)
{
    return $xs[$key];
}

$xs = [
    'a' => [
        'b' => ['c' => 3]
    ]
];

prop('c', prop('b', prop('a', $xs))); // 3
```

In this code, we can see that our `prop` method extracts a given key from an array, and we can nest these `prop` calls to extract more nested values. The flaw in this model is seen when the data isn't in the shape we expect. In this instance, you'll run into troubles with undefined array indices. This is where our Maybe monad becomes useful:

```php
<?php

use PhpFp\Maybe\Maybe;

function prop($key)
{
    return function ($xs)
    {
        return new Maybe(
            isset($xs[$key])
                ? $xs[$key]
                : null
        );
    };
}

$xs = [
    'a' => [
        'b' => ['c' => 3]
    ]
];

prop('a')($xs)
    ->chain(prop('b'))
    ->chain(prop('c')); // Just 3

prop('a')($xs)
    ->chain(prop('b'))
    ->chain(prop('d')); // Nothing

prop('immediate_mistake')($xs)
    ->chain(prop('b'))
    ->chain(prop('d')); // Nothing
```

What we can see here is that, when an 'error' occurs, we are given a `Nothing` value, which can be used in exactly the same way as our `Just` value. The key difference is that `Nothing` ignores the chain function and returns `Nothing` again. This means that we can write our full, nesting computation without having to check at each key whether or not the next one exists. When we _do_ find a non-existent key, all further computation is bypassed (hence 'early abort').

The merit of should be obvious: the first example I normally give is with a function that returns a single row from a database (if the query has any results): when successful, your function could return a Just constructor for the row, else Nothing.

### API

In the following type signatures, constructors and static functions are written as one would see in pure languages such as Haskell. The others contain a pipe, where the type before the pipe represents the type of the current Maybe instance, and the type after the pipe represents the function.

### `of :: a -> Maybe a`

This is the applicative constructor for the Maybe monad. It returns the value, regardless of what it is, as a `Just` instance.

```php
<?php

use PhpFp\Maybe\Maybe;

assert(Maybe::of('test')->fork('default') == 'test');
```

### `empty :: Maybe a`

This returns a `Nothing` instance, which is useful when concatenating results (e.g. with `reduce`) and you need an initial accumulator. It is also required (along with `concat`) for the `Monoid` definition.

```php
<?php

use PhpFp\Maybe\Maybe;

$value = Maybe::empty()
    ->concat(Maybe::of('blah'))
    ->fork(null);

assert($value == 'blah');
```

### `__construct :: a -> Maybe a`

Construct a new value, either Just or Nothing. `Just` requires one parameter, and Nothing requires no parameters (because the parameter would always be `null`):

```php
<?php

use PhpFp\Maybe\Maybe;

assert(Maybe::of(12))->fork(5) == 12);
assert(Maybe::empty())->fork(5) == 5);
```

The `fork` parameters are explained later on in this API description.

### `ap :: Maybe (a -> b) | Maybe a -> Maybe b`

Apply a Maybe-wrapped argument to a Maybe-wrapped function. Nothing values act as the identity function, as shown below:

```php
<?php

use PhpFp\Maybe\Maybe;

$add = function ($x)
{
    return function ($y)
    {
        return $x + $y;
    };
};

$value = Maybe::of($add)
    ->ap(Maybe::of(2))
    ->ap(Maybe::of(8));

assert($value->fork(10000) == 10);
assert(Maybe::empty()->ap(Maybe::of(2))->fork(10000) == 2);
```

### `concat :: Maybe a | Maybe a -> Maybe a`

Semigroup concatenation for Maybe values. This function should be used to combine two Maybe values into one.
- For two Just instances, the two values are concatenated and wrapped with a Just constructor.
- For one Just and one Nothing, the Just value is returned.
- For two Nothing values, Nothing is returned.

```php
<?php

use PhpFp\Maybe\Maybe;

$maybes = [Maybe::empty(), Maybe::of(2), Maybe::empty()];

$acc = array_reduce(
    $maybes,
    function ($acc, $x)
    {
        return $acc->concat($x);
    },
    Maybe::empty()
);

assert($acc->fork(5) == 2);
```

### `chain :: Maybe a | (a -> Maybe b) -> Maybe b`

This is the equivalent of Haskell's `>>=` (bind) operation for this Maybe implementation. This runs a Maybe-returning function on the inner value of this Maybe, and returns the result. This is the important mechanism behind the example in the introduction.

```php
<?php

use PhpFp\Maybe\Maybe;

function safeHalf($x)
{
    return $x % 2 == 0
        ? Maybe::of($x / 2)
        : Maybe::empty();
}

assert(safeHalf(16)->chain(safeHalf)->fork(null) == 4);
```

### `equals :: Maybe a | Maybe a -> Bool`

Setoid equality for Maybe. First, the two constructors are checked for equality. If they are both `Nothing`, they are equal. If they are both `Just`, The inner values are compared.

```php
<?php

use PhpFp\Maybe\Maybe;

class Value {
    public function __construct($x)
    {
        $this->value = $x;
    }

    public function equals($that)
    {
        return $this->value === $that->value;
    }
}

$a = Maybe::empty();
$b = Maybe::of(new Value(2));
$c = Maybe::of(new Value(3));
$d = Maybe::of(new Value(3));

assert($a->equals($a) == true);
assert($a->equals($b) == false);
assert($b->equals($c) == false);
assert($c->equals($d) == true);
```

### `map :: Maybe a | (a -> b) -> Maybe b`

Standard functor map: transform the value within a Maybe, and return an updated Maybe.

```php
<?php

use PhpFp\Maybe\Maybe;

$add2 = function ($x)
{
    return $x + 2;
};

assert(Maybe::empty()->map($add2)->fork(-1) == -1);
assert(Maybe::of(2)->map($add2)->fork(-1) == 4);
```

### `reduce :: Maybe a | (b -> a -> b) -> Maybe a -> b`

Maybe is a foldable type. For Nothing values, `reduce` returns whatever initial accumulator value was supplied. For `Just` types, the result is the reduction function evaluated with the accumulator and the wrapped value.

```php
<?php

use PhpFp\Maybe\Maybe;

$append = function ($xs)
{
    return function ($x) use ($xs)
    {
        return array_merge($xs, [$x]);
    };
};

assert(Maybe::empty()->reduce($append, []) == []);
assert(Maybe::of(2)->reduce($append, []) == [2]);
```

### `fork :: Maybe a | a -> a`

Extract the value from within the Maybe. At the end of your fail-able computation, you'll usually want to extract the value from the Maybe. For type safety, this function requires a parameter to return in place of a `Nothing` value (of course, you can just return `null`):

```php
<?php

use PhpFp\Maybe\Maybe;

assert(Maybe::of(2)->fork(4) == 2);
assert(Maybe::empty()->fork(4) == 4);
```

## Contributing

This monad is implemented with more variety than IO, as there are many (regularly-used) type classes to which it can be applied, so feel free to add implementations for your favourites. Other than that, the underlying mechanisms are probably far less prone to change.

I see documentation changes being far more likely, and they are also more than welcome: the aim of the PhpFp project is not only to produce a set of functional utilities, but also to provide a learning resource for programmers wishing to learn more about functional programming.
