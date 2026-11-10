--TEST--
imagettftext(), imagefttext(), imagettfbbox(), imageftbbox(): the angle must be finite
--EXTENSIONS--
gd
--SKIPIF--
<?php
if (!function_exists("imagettftext")) die("skip freetype support required");
?>
--FILE--
<?php
$font = __DIR__ . '/Tuffy.ttf';
$im = imagecreatetruecolor(50, 50);
foreach ([NAN, INF, -INF] as $angle) {
    foreach ([
        fn() => imageftbbox(12, $angle, $font, 'Hello'),
        fn() => imagettfbbox(12, $angle, $font, 'Hello'),
        fn() => imagefttext($im, 12, $angle, 5, 30, 0xff0000, $font, 'Hi'),
        fn() => imagettftext($im, 12, $angle, 5, 30, 0xff0000, $font, 'Hi'),
    ] as $f) {
        try {
            $f();
        } catch (Throwable $e) {
            echo $e::class, ': ', $e->getMessage(), "\n";
        }
    }
}
var_dump(count(imageftbbox(12, 0, $font, 'Hello')));
?>
--EXPECT--
ValueError: imageftbbox(): Argument #2 ($angle) must be finite
ValueError: imagettfbbox(): Argument #2 ($angle) must be finite
ValueError: imagefttext(): Argument #3 ($angle) must be finite
ValueError: imagettftext(): Argument #3 ($angle) must be finite
ValueError: imageftbbox(): Argument #2 ($angle) must be finite
ValueError: imagettfbbox(): Argument #2 ($angle) must be finite
ValueError: imagefttext(): Argument #3 ($angle) must be finite
ValueError: imagettftext(): Argument #3 ($angle) must be finite
ValueError: imageftbbox(): Argument #2 ($angle) must be finite
ValueError: imagettfbbox(): Argument #2 ($angle) must be finite
ValueError: imagefttext(): Argument #3 ($angle) must be finite
ValueError: imagettftext(): Argument #3 ($angle) must be finite
int(8)
