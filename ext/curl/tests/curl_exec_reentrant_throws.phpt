--TEST--
curl_exec() on a handle already being executed from one of its own callbacks must throw, not silently truncate the outer result
--EXTENSIONS--
curl
--FILE--
<?php
$srcfile = __DIR__ . '/curl_exec_reentrant_throws_src.txt';
file_put_contents($srcfile, str_repeat('x', 3000));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'file://' . $srcfile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOPROGRESS, false);
curl_setopt($ch, CURLOPT_XFERINFOFUNCTION, function ($ch, $dltotal, $dlnow, $ultotal, $ulnow) {
    static $reentered = false;
    if (!$reentered && $dlnow > 100) {
        $reentered = true;
        try {
            curl_exec($ch);
            echo "FAIL: no exception thrown\n";
        } catch (\Error $e) {
            echo "caught: " . $e->getMessage() . "\n";
        }
    }
    return 0;
});

$result = curl_exec($ch);
var_dump(strlen($result));
var_dump(curl_errno($ch));

unlink($srcfile);
?>
--EXPECT--
caught: curl_exec(): Attempt to execute cURL handle already being executed from a callback
int(3000)
int(0)
