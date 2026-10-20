--TEST--
usort()/uasort()/uksort()/array_multisort()/array_udiff()/array_uintersect(): suspending a fiber from the comparator throws instead of corrupting the request-global comparator
--FILE--
<?php
// The comparator (or anything it calls, like a __toString() magic method) is
// not allowed to suspend the current fiber while a sort is using the shared
// request-global comparator state: that would let another fiber's sort run
// with, and eventually restore, the wrong comparator. It must fail cleanly
// with a FiberError instead of corrupting state or crashing.

function run(string $label, callable $sort) {
    $fiber = new Fiber($sort);
    try {
        $fiber->start();
        echo "$label: no error (unexpected)\n";
    } catch (FiberError $e) {
        echo "$label: ", $e->getMessage(), "\n";
    }
}

run('usort', function () {
    $a = [3, 1, 2];
    usort($a, function ($x, $y) { Fiber::suspend(); return $x <=> $y; });
});

run('uasort', function () {
    $a = [3, 1, 2];
    uasort($a, function ($x, $y) { Fiber::suspend(); return $x <=> $y; });
});

run('uksort', function () {
    $a = ['b' => 1, 'a' => 2];
    uksort($a, function ($x, $y) { Fiber::suspend(); return $x <=> $y; });
});

run('array_udiff', function () {
    array_udiff([1, 2], [1], function ($x, $y) { Fiber::suspend(); return $x <=> $y; });
});

run('array_uintersect', function () {
    array_uintersect([1, 2], [1], function ($x, $y) { Fiber::suspend(); return $x <=> $y; });
});

run('array_diff_ukey', function () {
    array_diff_ukey(['a' => 1], ['a' => 1], function ($x, $y) { Fiber::suspend(); return $x <=> $y; });
});

run('array_multisort', function () {
    $a = [
        new class { function __toString(): string { Fiber::suspend(); return '3'; } },
        new class { function __toString(): string { return '1'; } },
    ];
    array_multisort($a, SORT_STRING);
});

// A comparator that does NOT try to switch fibers must still sort correctly,
// including when the sort itself runs inside a fiber.
$fiber = new Fiber(function () {
    $a = [3, 1, 2];
    usort($a, fn($x, $y) => $x <=> $y);
    return $a;
});
$fiber->start();
var_dump($fiber->getReturn());

// Suspending BEFORE or AFTER a sort (not during it) must keep working.
$fiber = new Fiber(function () {
    Fiber::suspend('before');
    $a = [3, 1, 2];
    usort($a, fn($x, $y) => $x <=> $y);
    return $a;
});
var_dump($fiber->start());
$fiber->resume();
var_dump($fiber->getReturn());

// A comparator that throws (instead of suspending) must not leave fiber
// switching permanently blocked: PHP_ARRAY_CMP_FUNC_RESTORE() must still run
// on the exception path, unblocking before the exception propagates out of
// usort(). A later, unrelated fiber suspend must work normally afterwards.
$a = [3, 1, 2];
try {
    usort($a, function ($x, $y) { throw new Exception('boom'); });
    echo "exception: no exception (unexpected)\n";
} catch (Exception $e) {
    echo "exception: caught ", $e->getMessage(), "\n";
}
$fiber = new Fiber(function () { Fiber::suspend('after exception'); });
var_dump($fiber->start());

// Synchronous nesting (no fiber involved at all): a comparator that itself
// calls usort() on another array must keep working, unaffected by the fiber
// switch block/unblock now wrapping the outer sort.
$a = [3, 1, 2];
usort($a, function ($x, $y) {
    $inner = [2, 1];
    usort($inner, fn($p, $q) => $p <=> $q);
    return $x <=> $y;
});
var_dump($a);
?>
--EXPECT--
usort: Cannot switch fibers in current execution context
uasort: Cannot switch fibers in current execution context
uksort: Cannot switch fibers in current execution context
array_udiff: Cannot switch fibers in current execution context
array_uintersect: Cannot switch fibers in current execution context
array_diff_ukey: Cannot switch fibers in current execution context
array_multisort: Cannot switch fibers in current execution context
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
string(6) "before"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
exception: caught boom
string(15) "after exception"
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
