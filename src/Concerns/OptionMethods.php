<?php

namespace Tekord\Option\Concerns;

use Tekord\Option\Option;

/**
 * @template TValue
 *
 * @property-read TValue $value
 *
 * @mixin PanicAware
 */
trait OptionMethods {
    /**
     * Returns the contained value, or panics if NONE.
     *
     * @return TValue
     */
    public function unwrap() {
        if ($this->isNone())
            static::panic("Called `Option::unwrap` on a `None` value");

        return $this->value;
    }

    /**
     * Returns the contained value, or the default value if NONE.
     *
     * @template T
     *
     * @param T $default
     *
     * @return TValue|T
     */
    public function unwrapOrDefault($default) {
        if ($this->isNone())
            return $default;

        return $this->value;
    }

    /**
     * Returns the contained value, or a value provided by the callback if NONE.
     *
     * @template T
     *
     * @param callable(): T $valueRetriever
     *
     * @return TValue|T
     */
    public function unwrapOrElse(callable $valueRetriever) {
        if ($this->isNone())
            return $valueRetriever();

        return $this->value;
    }

    /**
     * Returns the contained value, or null if NONE.
     *
     * @return TValue|null
     */
    public function unwrapOrNull() {
        return $this->unwrapOrDefault(null);
    }

    /**
     * @template TMappedValue
     *
     * @param callable(TValue): TMappedValue $mapper
     *
     * @return static<TMappedValue>|Option<TMappedValue>
     */
    public function map(callable $mapper) {
        if ($this->isSome())
            return static::some($mapper($this->value));

        return static::none();
    }

    /**
     * Returns the contained value passed through the mapper, or the default value if NONE.
     *
     * @template TMappedValue
     * @template TDefaultValue
     *
     * @param callable(TValue): TMappedValue $mapper
     * @param TDefaultValue $default
     *
     * @return TMappedValue|TDefaultValue
     */
    public function mapOrDefault(callable $mapper, $default) {
        if ($this->isNone())
            return $default;

        return $mapper($this->value);
    }

    /**
     * Returns the contained value passed through the mapper, or a value provided by the callback if NONE.
     *
     * @template TMappedValue
     * @template TDefaultValue
     *
     * @param callable(TValue): TMappedValue $mapper
     * @param callable(): TDefaultValue $valueRetriever
     *
     * @return TMappedValue|TDefaultValue
     */
    public function mapOrElse(callable $mapper, callable $valueRetriever) {
        if ($this->isNone())
            return $valueRetriever();

        return $mapper($this->value);
    }

    /**
     * Calls the provided callback with a reference to the contained value if SOME.
     *
     * @param callable(TValue): void $callback
     *
     * @return $this
     */
    public function whenSome(callable $callback) {
        if ($this->isSome())
            $callback($this->value);

        return $this;
    }

    /**
     * Calls the provided callback if NONE.
     *
     * @param callable(): void $callback
     *
     * @return $this
     */
    public function whenNone(callable $callback) {
        if ($this->isNone())
            $callback();

        return $this;
    }

    /**
     * @param callable(TValue): mixed $callback
     *
     * @return TValue
     *
     * @throws \Throwable
     */
    public function expectSome(callable $callback) {
        if ($this->isSome())
            return $this->value;

        static::panic($callback());
    }

    /**
     * @param callable(TValue): mixed $callback
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function expectNone(callable $callback) {
        if ($this->isNone())
            return;

        static::panic($callback());
    }

    /**
     * @param callable(TValue): bool $predicate
     *
     * @return Option<TValue>
     */
    public function filter(callable $predicate) {
        if ($this->isNone())
            return static::none();

        return $predicate($this->value)
            ? static::some($this->value)
            : static::none();
    }


    /**
     * @param callable(static): void $callback
     *
     * @return $this
     */
    public function tap(callable $callback) {
        $callback($this);

        return $this;
    }
}
