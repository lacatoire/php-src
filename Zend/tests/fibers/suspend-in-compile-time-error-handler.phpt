--TEST--
Fiber switches are blocked in an error handler called during compilation
--FILE--
<?php
$fiber = new Fiber(function () {
    set_error_handler(function () {
        Fiber::suspend();
        return true;
    });
    eval('$x = (integer) 2;');
});

set_error_handler(function () use ($fiber) {
    try {
        $fiber->start();
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
    return true;
});
eval('$y = (integer) 1;');
var_dump($y);
restore_error_handler();

try {
    $fiber->start();
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump($fiber->isTerminated());
?>
--EXPECT--
FiberError: Cannot switch fibers in current execution context
int(1)
FiberError: Cannot switch fibers in current execution context
bool(true)
