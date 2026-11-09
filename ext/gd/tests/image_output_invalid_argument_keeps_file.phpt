--TEST--
imagepng(), imagejpeg(), imagewebp(), imageavif(): an invalid argument leaves the destination untouched
--EXTENSIONS--
gd
--FILE--
<?php
$file = __DIR__ . '/image_output_invalid_argument_keeps_file.bin';
$im = imagecreatetruecolor(4, 4);

$calls = [
    'imagepng' => [[50]],
    'imagejpeg' => [[200]],
    'imagewebp' => [[-5]],
    'imageavif' => [[150], [50, 20]],
];
foreach ($calls as $f => $variants) {
    if (!function_exists($f)) {
        continue;
    }
    foreach ($variants as $args) {
        file_put_contents($file, 'KEEP-ME-1234567890');
        try {
            $f($im, $file, ...$args);
        } catch (ValueError $e) {
            echo $e->getMessage(), "\n";
        }
        var_dump(file_get_contents($file));
    }
}
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/image_output_invalid_argument_keeps_file.bin');
?>
--EXPECT--
imagepng(): Argument #3 ($quality) must be between -1 and 9
string(18) "KEEP-ME-1234567890"
