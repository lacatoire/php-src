--TEST--
imagecreatefromwbmp(): truncated pixel data is rejected
--EXTENSIONS--
gd
--FILE--
<?php
$file = __DIR__ . '/imagecreatefromwbmp_truncated.out.wbmp';
foreach ([
    "\x00\x00\x08\x08",
    "\x00\x00\x08\x08\x00\x00\xff",
    "\x00\x00\x08\x08\x00\x00\xff\x00\xff\x00\xff",
    "\x00\x00\x08\x08\xff\xff\xff\xff\xff\xff\xff\xff",
] as $data) {
    file_put_contents($file, $data);
    $im = @imagecreatefromwbmp($file);
    echo strlen($data), ' bytes: ', $im ? imagesx($im) . 'x' . imagesy($im) : 'false', "\n";
    $im = @imagecreatefromstring($data);
    echo strlen($data), ' bytes (string): ', $im ? imagesx($im) . 'x' . imagesy($im) : 'false', "\n";
}
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagecreatefromwbmp_truncated.out.wbmp');
?>
--EXPECT--
4 bytes: false
4 bytes (string): false
7 bytes: false
7 bytes (string): false
11 bytes: false
11 bytes (string): false
12 bytes: 8x8
12 bytes (string): 8x8
