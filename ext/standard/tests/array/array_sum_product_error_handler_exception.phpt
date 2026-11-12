--TEST--
array_sum() / array_product(): exception or exit() from a user error handler is not discarded
--FILE--
<?php
// 1. A handler converting warnings to exceptions must see the same message
// array_product() would report without a handler, and it must propagate.
set_error_handler(function ($no, $str) { throw new ErrorException($str, 0, $no); });
try {
    array_product(["5abc", 3]);
    echo "1: no exception (unexpected)\n";
} catch (Throwable $e) {
    echo "1: ", get_class($e), ": ", $e->getMessage(), "\n";
}
restore_error_handler();

// 2. A handler that throws its own exception must have that exact exception
// propagate, not a substituted "not supported on type" error.
set_error_handler(function ($no, $str) {
    throw new RuntimeException("handler: $str");
});
try {
    var_dump(array_product(["5abc", 3]));
    echo "2: no exception (unexpected)\n";
} catch (Throwable $e) {
    echo "2: ", get_class($e), ": ", $e->getMessage(), "\n";
}
restore_error_handler();

// 3. array_sum() must behave the same way as array_product().
set_error_handler(function ($no, $str) {
    throw new RuntimeException("handler: $str");
});
try {
    var_dump(array_sum(["5abc", 3]));
    echo "3: no exception (unexpected)\n";
} catch (Throwable $e) {
    echo "3: ", get_class($e), ": ", $e->getMessage(), "\n";
}
restore_error_handler();

// 5. Known limitation: a handler that itself throws a bare, non-subclassed
// \TypeError is indistinguishable from this function's own BC error by
// class alone, so it gets cleared and the BC fallback runs, which emits its
// own warning and re-enters the handler, producing a *second* \TypeError
// with a different message than the one that was discarded (rather than the
// exception being lost entirely, which is the important part: it still
// propagates, just not the original one). See the comment in
// php_array_binop_apply().
set_error_handler(function ($no, $str) {
    throw new TypeError("handler: $str");
});
try {
    array_product(["5abc", 3]);
    echo "5: no exception (unexpected)\n";
} catch (Throwable $e) {
    echo "5: ", get_class($e), ": ", $e->getMessage(), "\n";
}
restore_error_handler();

// 4. exit() called from the handler must stop the script, not just the
// current array_product() call.
set_error_handler(function () {
    echo "4: handler calls exit\n";
    exit(3);
});
array_product([[], []]);
echo "4: still running after exit() (unexpected)\n";
?>
--EXPECT--
1: ErrorException: A non-numeric value encountered
2: RuntimeException: handler: A non-numeric value encountered
3: RuntimeException: handler: A non-numeric value encountered
5: TypeError: handler: array_product(): Multiplication is not supported on type string
4: handler calls exit
