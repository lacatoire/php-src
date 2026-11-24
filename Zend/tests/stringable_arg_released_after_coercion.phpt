--TEST--
A Stringable argument is replaced by its string before the object is released
--FILE--
<?php

class Inner {
    public static $backtrace;

    public function __destruct() {
        self::$backtrace = debug_backtrace();
    }
}

class Str {
    public $inner;

    public function __construct() {
        $this->inner = new Inner;
    }

    public function __toString(): string {
        return "abc";
    }
}

var_dump(strncmp(new Str, "abc", 3));
var_dump(Inner::$backtrace[1]['args'][0]);

?>
--EXPECT--
int(0)
string(3) "abc"
