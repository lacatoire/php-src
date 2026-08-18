--TEST--
fputcsv(): passing null as $eol must throw TypeError (non-nullable parameter)
--FILE--
<?php
$stream = fopen('php://temp', 'w+');

// null must be rejected (Z_PARAM_STR enforces non-nullable at runtime)
try {
    fputcsv($stream, ['a', 'b'], eol: null);
    echo "FAIL: no error\n";
} catch (TypeError $e) {
    echo "OK: " . $e->getMessage() . "\n";
}

// Omitting $eol still works (default \n)
rewind($stream);
ftruncate($stream, 0);
fputcsv($stream, ['a', 'b']);
rewind($stream);
echo "default eol: " . json_encode(fread($stream, 100)) . "\n";

// A valid string $eol still works
rewind($stream);
ftruncate($stream, 0);
fputcsv($stream, ['a', 'b'], eol: "\r\n");
rewind($stream);
echo "custom eol: " . json_encode(fread($stream, 100)) . "\n";

fclose($stream);
echo "Done\n";
?>
--EXPECTF--
OK: fputcsv(): Argument #6 ($eol) must be of type string, null given, called in %s on line %d
default eol: "a,b\n"
custom eol: "a,b\r\n"
Done
