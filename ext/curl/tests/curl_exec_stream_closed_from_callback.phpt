--TEST--
curl_exec() must detect a CURLOPT_FILE stream closed from within CURLOPT_DEBUGFUNCTION
--EXTENSIONS--
curl
--FILE--
<?php
$srcfile = __DIR__ . '/curl_exec_stream_closed_from_callback_src.txt';
file_put_contents($srcfile, str_repeat('x', 5000));
$outfile = __DIR__ . '/curl_exec_stream_closed_from_callback_out.txt';
$fp = fopen($outfile, 'w');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'file://' . $srcfile);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_DEBUGFUNCTION, function ($ch, $type, $data) use ($fp) {
    static $closed = false;
    if (!$closed) {
        $closed = true;
        fclose($fp);
    }
    return 0;
});

// The transfer must survive: the closed CURLOPT_FILE stream is detected and
// reset to the default (stdout) instead of writing through the dangling
// FILE* libcurl still has cached. What lands on stdout after that point is
// not asserted precisely here, only that curl_exec() completes safely.
var_dump(curl_exec($ch));
echo "\nreached end, no crash\n";

unlink($srcfile);
@unlink($outfile);
?>
--EXPECTF--
Warning: curl_exec(): CURLOPT_FILE resource has gone away, resetting to default in %s on line %d
%Abool(true)

reached end, no crash
