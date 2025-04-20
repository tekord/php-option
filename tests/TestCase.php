<?php

namespace Tekord\Option\Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase {
    protected function withExceptionExpectation(
        callable $callback,
        ?callable $errorHandler = null,
    ) {
        try {
            $callback();
        }
        catch (\Throwable $e) {
            if ($errorHandler)
                $errorHandler($e);

            return $this;
        }

        $this->assertFalse(true);
    }

    //
}
