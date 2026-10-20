--TEST--
mb_convert_kana(): the invalid flag error is valid text for any byte
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (["Z", "\0", "\n", "\x7f", "ｱ", "é"] as $mode) {
    try {
        mb_convert_kana("a", $mode);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
        var_dump(mb_check_encoding($e->getMessage(), 'UTF-8'));
    }
}
?>
--EXPECT--
ValueError: mb_convert_kana(): Argument #2 ($mode) contains invalid flag: 'Z'
bool(true)
ValueError: mb_convert_kana(): Argument #2 ($mode) contains invalid flag: 0x00
bool(true)
ValueError: mb_convert_kana(): Argument #2 ($mode) contains invalid flag: 0x0A
bool(true)
ValueError: mb_convert_kana(): Argument #2 ($mode) contains invalid flag: 0x7F
bool(true)
ValueError: mb_convert_kana(): Argument #2 ($mode) contains invalid flag: 0xEF
bool(true)
ValueError: mb_convert_kana(): Argument #2 ($mode) contains invalid flag: 0xC3
bool(true)
