--TEST--
ftp:// wrapper with a "proxy" context option that is not a non-empty string
--FILE--
<?php

foreach (['', null, 0, []] as $proxy) {
    $context = stream_context_create(['ftp' => ['proxy' => $proxy]]);
    var_dump(@file_get_contents('ftp://127.0.0.1:1/file', false, $context));
}

?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
