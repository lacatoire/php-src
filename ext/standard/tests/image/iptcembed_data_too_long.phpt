--TEST--
iptcembed(): IPTC data that does not fit in the APP13 segment is rejected
--FILE--
<?php
$file = __DIR__ . '/iptcembed_data_too_long.jpg';
file_put_contents($file, "\xFF\xD8\xFF\xE0\x00\x10JFIF\0\x01\x01\0\0\x01\0\x01\0\0\xFF\xDA\x00\x02\xFF\xD9");

foreach ([65505, 65506, 65507, 65535, 70000] as $n) {
    try {
        $out = iptcembed(str_repeat('A', $n), $file);
        $pos = strpos($out, "\xFF\xED");
        // Segment length field, and the bytes added to the original file
        var_dump($n, unpack('n', $out, $pos + 2)[1], strlen($out) - filesize($file));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/iptcembed_data_too_long.jpg');
?>
--EXPECT--
int(65505)
int(65534)
int(65536)
int(65506)
int(65534)
int(65536)
ValueError: iptcembed(): Argument #1 ($iptc_data) must not be longer than 65506 bytes
ValueError: iptcembed(): Argument #1 ($iptc_data) must not be longer than 65506 bytes
ValueError: iptcembed(): Argument #1 ($iptc_data) must not be longer than 65506 bytes
