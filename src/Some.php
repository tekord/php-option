<?php

namespace Tekord\Option;

/**
 * @template TValue
 *
 * @property-read TValue $value
 *
 * @extends Option<TValue>
 */
class Some extends Option {
    /**
     * @param TValue $value
     */
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
