--TEST--
curl_multi_getcontent(): a string read during the transfer is not changed by later writes
--EXTENSIONS--
curl
--FILE--
<?php
$file = __DIR__ . '/curl_multi_getcontent_during_transfer.bin';
file_put_contents($file, str_repeat('x', 3000000));

$ch = curl_init("file://$file");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOPROGRESS, false);
$first = null;
$length = 0;
curl_setopt($ch, CURLOPT_XFERINFOFUNCTION, function () use ($ch, &$first, &$length) {
    $content = curl_multi_getcontent($ch);
    if ($first === null && is_string($content) && strlen($content) > 0) {
        $first = $content;
        $length = strlen($content);
    }
    return 0;
});
curl_exec($ch);
var_dump($length > 0, strlen($first) === $length, $first === str_repeat('x', $length));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/curl_multi_getcontent_during_transfer.bin');
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
