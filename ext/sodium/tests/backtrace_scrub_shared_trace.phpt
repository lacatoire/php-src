--TEST--
The scrub of the backtrace of a sodium exception does not write into a shared trace array
--EXTENSIONS--
sodium
--FILE--
<?php

function literal_trace(): array
{
    return [['function' => 'f', 'args' => [1, 2]]];
}

$exception = new Exception('x');
(new ReflectionProperty(Exception::class, 'trace'))->setValue($exception, literal_trace());

set_error_handler(function () use ($exception) {
    throw $exception;
});

try {
    sodium_crypto_scalarmult(str_repeat('a', 32), null);
} catch (Exception $caught) {
    var_dump($caught === $exception);
    var_dump($caught->getTrace()[0]['args']);
}

var_dump(literal_trace()[0]['args']);

?>
--EXPECT--
bool(true)
array(0) {
}
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}
