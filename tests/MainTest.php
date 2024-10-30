<?php

namespace Tekord\Option\Tests;

use Tekord\Option\None;
use Tekord\Option\Option;
use Tekord\Option\PanicException;

/**
 * @runTestsInSeparateProcesses
 *
 * @author Cyrill Tekord
 */
final class MainTest extends TestCase {
    public function testSome() {
        $o = Option::some("OK");

        $this->assertTrue($o->isSome());
        $this->assertFalse($o->isNone());

        $this->assertEquals("OK", $o->value);
    }

    public function testNone() {
        $o = Option::none();

        $this->assertFalse($o->isSome());
        $this->assertTrue($o->isNone());

        $this->assertEquals(None::getInstance(), $o->value);
    }

    public function testUnwrapMethods() {
        $o = Option::some("OK");
        $this->assertEquals("OK", $o->unwrap());

        $this->withExceptionExpectation(function () {
            $o = Option::none();
            $o->unwrap();
        }, fn($e) => $this->assertInstanceOf(PanicException::class, $e));

        // -

        $o = Option::some("OK");
        $this->assertEquals("OK", $o->unwrapOrDefault(100));

        $o = Option::none();
        $this->assertEquals(100, $o->unwrapOrDefault(100));

        // -

        $o = Option::some("OK");
        $this->assertEquals("OK", $o->unwrapOrElse(fn() => 100));

        $o = Option::none();
        $this->assertEquals(100, $o->unwrapOrElse(fn() => 100));

        // -

        $o = Option::some("OK");
        $this->assertEquals("OK", $o->unwrapOrNull());

        $o = Option::none();
        $this->assertEquals(null, $o->unwrapOrNull());
    }

    public function testMapMethods() {
        $mapper = function ($value) {
            return "($value)";
        };

        {
            $o = Option::some("OK");
            $mappedOption = $o->map($mapper);

            $this->assertInstanceOf(Option::class, $mappedOption);
            $this->assertEquals("(OK)", $mappedOption->value);
        }

        {
            $o = Option::none();
            $mappedOption = $o->map($mapper);

            $this->assertInstanceOf(Option::class, $mappedOption);
            $this->assertTrue($mappedOption->isNone());
        }

        // -

        {
            $o = Option::some("OK");
            $this->assertEquals("(OK)", $o->mapOrDefault($mapper, "NOT SET"));
        }

        {
            $o = Option::none();
            $this->assertEquals("NOT SET", $o->mapOrDefault($mapper, "NOT SET"));
        }

        // -

        {
            $o = Option::some("OK");
            $this->assertEquals('(OK)', $o->mapOrElse($mapper, fn() => '-'));
        }

        {
            $o = Option::none();
            $this->assertEquals('-', $o->mapOrElse($mapper, fn() => '-'));
        }
    }

    public function testWhenMethods() {
        {
            $o = Option::some("OK");

            $ot = $o
                ->whenSome(fn($value) => $this->assertEquals("OK", $value))
                ->whenNone(fn() => $this->assertTrue(false));

            $this->assertInstanceOf(Option::class, $o);
            $this->assertTrue($o === $ot);
        }

        {
            $o = Option::none();

            $ot = $o
                ->whenSome(fn($value) => $this->assertTrue(false))
                ->whenNone(fn() => $this->assertEquals(true, true));

            $this->assertInstanceOf(Option::class, $o);
            $this->assertTrue($o === $ot);
        }
    }

    public function testExpectMethods() {
        {
            $o = Option::some("OK")
                ->expectSome(fn() => $this->assertTrue(false));

            $this->assertEquals("OK", $o);
        }

        {
            Option::none()
                ->expectNone(fn() => $this->assertTrue(false));
        }

        $this->withExceptionExpectation(function () {
            Option::some("OK")->expectNone(fn() => "Expected ERROR");
        }, function (\Throwable $e) {
            $this->assertInstanceOf(PanicException::class, $e);
            $this->assertEquals("Expected ERROR", $e->getMessage());
        });

        $this->withExceptionExpectation(function () {
            Option::none()->expectSome(fn() => "Expected OK");
        }, function (\Throwable $e) {
            $this->assertInstanceOf(PanicException::class, $e);
            $this->assertEquals("Expected OK", $e->getMessage());
        });
    }
}
