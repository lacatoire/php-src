--TEST--
mb_encode_numericentity()/mb_decode_numericentity(): map elements must fit in 32 bits
--EXTENSIONS--
mbstring
--FILE--
<?php
$maps = [
    [0x100000000, 0x100000100, 0, 0xffffff],
    [0x41, 0x100000041, 0, 0xffffff],
    [0x41, 0x41, 0x100000000, 0xffffff],
    [0x41, 0x41, 0, 0x100000000],
    [-1, 0x41, 0, 0xffffff],
    [0x41, 0x41, 0, -1],
];
foreach ($maps as $map) {
    try {
        var_dump(mb_decode_numericentity("&#65;", $map));
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
    try {
        var_dump(mb_encode_numericentity("A", $map));
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
// Valid boundaries and negative offsets still work
var_dump(mb_encode_numericentity("A", [0x41, 0x41, -1, 0xffffffff]));
var_dump(mb_encode_numericentity("A", [0, 0xffffffff, 0, 0xffffffff]));
?>
--EXPECT--
mb_decode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_encode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_decode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_encode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_decode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_encode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_decode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_encode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_decode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_encode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_decode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
mb_encode_numericentity(): Argument #2 ($map) must only be composed of values that fit in an unsigned 32-bit integer
string(5) "&#64;"
string(5) "&#65;"
