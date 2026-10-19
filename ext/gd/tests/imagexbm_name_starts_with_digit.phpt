--TEST--
imagexbm(): an identifier prefix starting with a digit gets an underscore
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(4, 2);
$dir = __DIR__ . '/imagexbm_name_starts_with_digit_dir';
mkdir($dir);
foreach (['1.xbm', '2icon.xbm', 'my-icon.v2.xbm', 'icon.xbm'] as $name) {
    imagexbm($im, "$dir/$name");
    echo strtok(file_get_contents("$dir/$name"), "\n"), "\n";
}
?>
--CLEAN--
<?php
$dir = __DIR__ . '/imagexbm_name_starts_with_digit_dir';
foreach (['1.xbm', '2icon.xbm', 'my-icon.v2.xbm', 'icon.xbm'] as $name) {
    @unlink("$dir/$name");
}
@rmdir($dir);
?>
--EXPECT--
#define _1_width 4
#define _2icon_width 4
#define my_icon_v2_width 4
#define icon_width 4
