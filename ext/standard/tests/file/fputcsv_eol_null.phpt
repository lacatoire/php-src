--TEST--
fputcsv(): passing null as $eol must throw TypeError (non-nullable parameter)
--FILE--
<?php
declare(strict_types=1);

$stream = fopen('php://temp', 'w+');

/* Under strict_types the non-nullable declaration is enforced with a
   TypeError; in weak mode the same call is deprecated and coerced. */
try {
    fputcsv($stream, ['a', 'b'], escape: '\\', eol: null);
    echo "FAIL: no error\n";
} catch (Throwable $e) {
    echo $e::class . ": " . $e->getMessage() . "\n";
}

// Omitting $eol still works (default \n)
rewind($stream);
ftruncate($stream, 0);
fputcsv($stream, ['a', 'b'], escape: '\\');
rewind($stream);
echo "default eol: " . json_encode(fread($stream, 100)) . "\n";

// A valid string $eol still works
rewind($stream);
ftruncate($stream, 0);
fputcsv($stream, ['a', 'b'], escape: '\\', eol: "\r\n");
rewind($stream);
echo "custom eol: " . json_encode(fread($stream, 100)) . "\n";

fclose($stream);
echo "Done\n";
?>
--EXPECT--
TypeError: fputcsv(): Argument #6 ($eol) must be of type string, null given
default eol: "a,b\n"
custom eol: "a,b\r\n"
Done
