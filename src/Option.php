<?php

namespace Tekord\Option;

use Tekord\Option\Concerns\OptionMethods;
use Tekord\Option\Concerns\PanicAware;

/**
 * @template TValue
 *
 * @mixin OptionMethods<TValue>
 */
abstract class Option {
    use PanicAware;
    use OptionMethods;

    /**
     * @param TValue $value
     *
     * @return Some<TValue>
     */
    #[\NoDiscard]
    public static function some($value): Some {
        return new Some($value);
    }

    /**
     * @return None
     */
    #[\NoDiscard]
    public static function none(): None {
        return None::getInstance();
    }

    /**
     * @return static<TValue>
     */
    #[\NoDiscard]
    public static function from($value): static {
        return $value === null
            ? static::none()
            : static::some($value);
    }

    // -

    /**
     * Indicates whether the option is SOME.
     *
     * @return bool
     */
    public function isSome(): bool {
        return $this instanceof Some;
    }

    /**
     * Indicates whether the option is NONE.
     *
     * @return bool
     */
    public function isNone(): bool {
        return $this instanceof None;
    }
}
