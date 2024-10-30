<?php

namespace Tekord\Option;

/**
 * @template TValue
 *
 * @author Cyrill Tekord
 */
interface OptionInterface {
    /**
     * Indicates whether the option is SOME.
     *
     * @return bool
     */
    public function isSome();

    /**
     * Indicates whether the option is NONE.
     *
     * @return bool
     */
    public function isNone();
}
