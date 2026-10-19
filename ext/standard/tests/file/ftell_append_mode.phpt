--TEST--
ftell(): reports correct position for append-mode streams opened on non-empty files
--FILE--
<?php
$tmp = tempnam(sys_get_temp_dir(), 'ftell');
file_put_contents($tmp, "0123456789"); // 10 bytes

$h = fopen($tmp, 'a+');

// Initial position must be at end-of-file, not 0
var_dump(ftell($h));   // int(10)

// After a write, position must be end-of-file + bytes written
fwrite($h, 'AB');
var_dump(ftell($h));   // int(12)

// Reading at that position must return empty (we are at EOF)
var_dump(fread($h, 3)); // string(0) ""

// Seeking to 0 and reading must return the full content including the write
fseek($h, 0);
var_dump(fread($h, 100)); // string(12) "0123456789AB"

fclose($h);
unlink($tmp);
?>
--EXPECT--
int(10)
int(12)
string(0) ""
string(12) "0123456789AB"
