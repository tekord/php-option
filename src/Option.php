<?php

namespace Tekord\Option;

use Tekord\Option\Concerns\FactoryMethods;
use Tekord\Option\Concerns\OptionMethods;
use Tekord\Option\Concerns\PanicAware;

/**
 * @template TValue
 *
 * @property-read TValue $value
 *
 * @implements OptionInterface<TValue>
 *
 * @mixin FactoryMethods<TValue>
 * @mixin OptionMethods<TValue>
 *
 * @author Cyrill Tekord
 */
class Option implements OptionInterface {
    use PanicAware;
    use FactoryMethods;
    use OptionMethods;

    /** @var TValue|None */
    protected $_value;

    /** @inheritDoc */
    public function isSome() {
        return !$this->_value instanceof None;
    }

    /** @inheritDoc */
    public function isNone() {
        return $this->_value instanceof None;
    }

    protected function __construct() {
    }
}
