--TEST--
proc_open() rejects a redirect to a descriptor declared later in the spec
--FILE--
<?php

$php = getenv('TEST_PHP_EXECUTABLE');
$cmd = [$php, '-r', 'echo "out"; fprintf(STDERR, "err");'];

var_dump(proc_open($cmd, [1 => ['redirect', 2], 2 => ['pipe', 'w']], $pipes));

$proc = proc_open($cmd, [2 => ['pipe', 'w'], 1 => ['redirect', 2]], $pipes);
var_dump(stream_get_contents($pipes[2]));
proc_close($proc);

?>
--EXPECTF--
Warning: proc_open(): Redirection target 2 must be declared before the redirection in %s on line %d
bool(false)
string(6) "outerr"
