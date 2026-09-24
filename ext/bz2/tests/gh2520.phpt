--TEST--
GH-2520: bzopen() on a stream resource must dup() the fd, not share it
--EXTENSIONS--
bz2
--FILE--
<?php

$fx = tempnam(sys_get_temp_dir(), "bz2gh2520");
$victim = tempnam(sys_get_temp_dir(), "bz2gh2520victim");

$fp = fopen($fx, "w");
$bz = bzopen($fp, "w");
bzwrite($bz, str_repeat("A", 100));

// Closing the plain stream must not close bzopen()'s fd: it should have
// its own dup()'d fd, so this must succeed without warning.
var_dump(fclose($fp));

// Reuse the freed fd for an unrelated file.
$v = fopen($victim, "w");
fwrite($v, "CLEAN");
var_dump(fclose($v));

// Closing the bz2 stream must only close ITS OWN fd, never touching
// $victim's contents.
unset($bz);

var_dump(file_get_contents($victim));

@unlink($fx);
@unlink($victim);

?>
--EXPECT--
bool(true)
bool(true)
string(5) "CLEAN"
