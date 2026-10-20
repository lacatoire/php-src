--TEST--
restore_exception_handler() called twice by an object exception handler
--FILE--
<?php
class H {
    public $p = "prop";
    function __invoke($e) {
        restore_exception_handler();
        restore_exception_handler();
        echo "after: ", $this->p, "\n";
    }
    function __destruct() { echo "DESTRUCT\n"; }
}
class M {
    public $p = "method prop";
    function handle($e) {
        restore_exception_handler();
        restore_exception_handler();
        echo "after: ", $this->p, "\n";
    }
    function __destruct() { echo "DESTRUCT M\n"; }
}
set_exception_handler([new M, 'handle']);
set_exception_handler(new H);
throw new Exception("x");
?>
--EXPECT--
after: prop
DESTRUCT
DESTRUCT M
