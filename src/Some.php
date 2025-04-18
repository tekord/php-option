<?php

namespace Tekord\Option;

/**
 * @template TValue
 *
 * @property TValue $value
 *
 * @extends Option<TValue>
 */
class Some extends Option {
    public function __construct(
        public readonly mixed $value
    ) {
    }

    /**
     * @return TValue
     */
    public function getValue() {
        return $this->value;
    }
}
