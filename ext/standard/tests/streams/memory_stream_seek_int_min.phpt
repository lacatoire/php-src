--TEST--
php://memory stream seek with PHP_INT_MIN does not trigger undefined behavior
--FILE--
<?php
$stream = fopen('php://memory', 'r+');
fwrite($stream, 'hello');

// SEEK_CUR with PHP_INT_MIN
fseek($stream, 2, SEEK_SET);
var_dump(fseek($stream, PHP_INT_MIN, SEEK_CUR));
var_dump(ftell($stream));

// SEEK_END with PHP_INT_MIN
var_dump(fseek($stream, PHP_INT_MIN, SEEK_END));
var_dump(ftell($stream));

// Normal seek should still work after failed seeks
fseek($stream, 0, SEEK_SET);
var_dump(ftell($stream));

fclose($stream);
echo "Done\n";
?>
--EXPECT--
int(-1)
int(0)
int(-1)
int(0)
int(0)
Done
