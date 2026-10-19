--TEST--
grapheme functions reset the global intl error
--EXTENSIONS--
intl
--INI--
intl.use_exceptions=0
intl.error_level=0
--FILE--
<?php
$calls = [
    'grapheme_strlen' => fn($s) => grapheme_strlen($s),
    'grapheme_substr' => fn($s) => grapheme_substr($s, 0, 1),
    'grapheme_strpos' => fn($s) => grapheme_strpos($s, "a"),
    'grapheme_stripos' => fn($s) => grapheme_stripos($s, "a"),
    'grapheme_strrpos' => fn($s) => grapheme_strrpos($s, "a"),
    'grapheme_strripos' => fn($s) => grapheme_strripos($s, "a"),
    'grapheme_strstr' => fn($s) => grapheme_strstr($s, "a"),
    'grapheme_stristr' => fn($s) => grapheme_stristr($s, "a"),
];
foreach ($calls as $name => $call) {
    $call("\xff\xfe");
    $failed = intl_get_error_code() !== 0;
    $call("abc");
    echo $name, ': ', $failed ? 'failed' : 'no failure', ', then ', intl_get_error_code(), "\n";
}
?>
--EXPECT--
grapheme_strlen: failed, then 0
grapheme_substr: failed, then 0
grapheme_strpos: failed, then 0
grapheme_stripos: failed, then 0
grapheme_strrpos: failed, then 0
grapheme_strripos: failed, then 0
grapheme_strstr: failed, then 0
grapheme_stristr: failed, then 0
