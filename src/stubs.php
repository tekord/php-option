<?php

if (!class_exists(\NoDiscard::class, false)) {
    #[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_FUNCTION)]
    final class NoDiscard {}
}
