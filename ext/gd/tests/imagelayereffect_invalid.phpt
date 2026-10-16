--TEST--
imagelayereffect(): the effect must be an IMG_EFFECT_* constant
--EXTENSIONS--
gd
--FILE--
<?php
$im = imagecreatetruecolor(2, 2);
foreach ([IMG_EFFECT_REPLACE, IMG_EFFECT_ALPHABLEND, IMG_EFFECT_NORMAL, IMG_EFFECT_OVERLAY, IMG_EFFECT_MULTIPLY, 5, -1, 99, 2**32 + 1, 2**32] as $effect) {
    try {
        var_dump(imagelayereffect($im, $effect));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
ValueError: imagelayereffect(): Argument #2 ($effect) must be one of the IMG_EFFECT_* constants
ValueError: imagelayereffect(): Argument #2 ($effect) must be one of the IMG_EFFECT_* constants
ValueError: imagelayereffect(): Argument #2 ($effect) must be one of the IMG_EFFECT_* constants
ValueError: imagelayereffect(): Argument #2 ($effect) must be one of the IMG_EFFECT_* constants
ValueError: imagelayereffect(): Argument #2 ($effect) must be one of the IMG_EFFECT_* constants
