<?php

namespace Tekord\Option\Concerns;

use Tekord\Option\PanicException;

trait PanicAware {
    public static $panicExceptionClass = PanicException::class;

    /**
     * @param \Throwable|mixed $error
     *
     * @return never
     *
     * @throws \Throwable
     */
    protected static function panic($error): never {
        if ($error instanceof \Throwable)
            throw $error;

        throw new static::$panicExceptionClass($error);
    }
}
