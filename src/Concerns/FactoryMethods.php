<?php

namespace Tekord\Option\Concerns;

use Tekord\Option\None;

/**
 * @template TValue
 *
 * @author Cyrill Tekord
 */
trait FactoryMethods {
    /**
     * @param TValue $value
     *
     * @return static<TValue>
     */
    public static function some($value): static {
        $option = new static();
        $option->_value = $value;

        return $option;
    }

    /**
     * @return static<None>
     */
    public static function none(): static {
        $option = new static();
        $option->_value = None::getInstance();

        return $option;
    }
}
