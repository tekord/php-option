<?php

namespace Tekord\Option\Tests;

use Tekord\Option\Option;
use Tekord\Option\PanicException;

/**
 * @runTestsInSeparateProcesses
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
    }

    public function testFromMethod() {
        {
            $o = Option::from("Hello");

            $this->assertTrue($o->isSome());
            $this->assertFalse($o->isNone());
        }

        {
            $o = Option::from(null);

            $this->assertFalse($o->isSome());
            $this->assertTrue($o->isNone());
        }
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
            Option::some("OK")->expectNone(fn() => "Expected NONE");
        }, function (\Throwable $e) {
            $this->assertInstanceOf(PanicException::class, $e);
            $this->assertEquals("Expected NONE", $e->getMessage());
        });

        $this->withExceptionExpectation(function () {
            Option::none()->expectSome(fn() => "Expected SOME");
        }, function (\Throwable $e) {
            $this->assertInstanceOf(PanicException::class, $e);
            $this->assertEquals("Expected SOME", $e->getMessage());
        });
    }

    public function testFilterMethod() {
        $predicate1 = fn($value) => $value > 100;
        $predicate2 = fn($value) => $value < 100;

        {
            $o = Option::some(120);

            $o1 = $o->filter($predicate1);
            $this->assertTrue($o1->isSome());
            $this->assertEquals(120, $o1->unwrap());

            $o2 = $o->filter($predicate2);
            $this->assertTrue($o2->isNone());
        }

        {
            $o = Option::none();

            $o1 = $o->filter($predicate1);
            $this->assertTrue($o1->isNone());

            $o2 = $o->filter($predicate2);
            $this->assertTrue($o2->isNone());
        }
    }

    public function testTapMethod() {
        {
            $wasTapped = false;

            $option = Option::some("OK")
                ->tap(function () use (&$wasTapped) {
                    $wasTapped = true;
                });

            $this->assertTrue($option->isSome());
            $this->assertTrue($wasTapped);
        }

        {
            $wasTapped = false;

            $option = Option::none()
                ->tap(function () use (&$wasTapped) {
                    $wasTapped = true;
                });

            $this->assertTrue($option->isNone());
            $this->assertTrue($wasTapped);
        }
    }
}
