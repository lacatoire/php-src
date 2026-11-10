--TEST--
imagecopymerge(), imagecopymergegray(): pct must be between 0 and 100
--EXTENSIONS--
gd
--FILE--
<?php
$d = imagecreatetruecolor(2, 2);
imagefill($d, 0, 0, 0x408020);
$s = imagecreatetruecolor(2, 2);
imagefill($s, 0, 0, 0xe0c0a0);

foreach (['imagecopymerge', 'imagecopymergegray'] as $f) {
    foreach ([-1, 101, 150, 1000] as $pct) {
        try {
            $f($d, $s, 0, 0, 0, 0, 2, 2, $pct);
        } catch (Throwable $e) {
            echo $e::class, ': ', $e->getMessage(), "\n";
        }
    }
    foreach ([0, 50, 100] as $pct) {
        var_dump($f($d, $s, 0, 0, 0, 0, 2, 2, $pct));
    }
}
printf("%06x\n", imagecolorat($d, 0, 0));
?>
--EXPECTF--
ValueError: imagecopymerge(): Argument #9 ($pct) must be between 0 and 100
ValueError: imagecopymerge(): Argument #9 ($pct) must be between 0 and 100
ValueError: imagecopymerge(): Argument #9 ($pct) must be between 0 and 100
ValueError: imagecopymerge(): Argument #9 ($pct) must be between 0 and 100
bool(true)
bool(true)
bool(true)
ValueError: imagecopymergegray(): Argument #9 ($pct) must be between 0 and 100
ValueError: imagecopymergegray(): Argument #9 ($pct) must be between 0 and 100
ValueError: imagecopymergegray(): Argument #9 ($pct) must be between 0 and 100
ValueError: imagecopymergegray(): Argument #9 ($pct) must be between 0 and 100
bool(true)
bool(true)
bool(true)
%s
