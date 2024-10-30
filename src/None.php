<?php

namespace Tekord\Option;

final class None {
    private function __construct() {
    }

    /** @var static */
    private static $instance;

    public static function getInstance(): static {
        if (static::$instance === null)
            static::$instance = new static();

        return static::$instance;
    }
}
