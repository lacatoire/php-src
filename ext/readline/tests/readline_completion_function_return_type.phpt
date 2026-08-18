--TEST--
readline_completion_function(): declared return type matches the only possible outcome
--EXTENSIONS--
readline
--FILE--
<?php

$function = new ReflectionFunction('readline_completion_function');
var_dump((string) $function->getReturnType());

var_dump(readline_completion_function('strtolower'));
var_dump(readline_completion_function(fn (string $input): array => []));

?>
--EXPECT--
string(4) "true"
bool(true)
bool(true)
