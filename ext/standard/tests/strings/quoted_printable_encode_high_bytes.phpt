--TEST--
quoted_printable_encode(): soft line breaks for bytes above 0xF4
--FILE--
<?php
foreach (["\xff", "\xf5", "\xf4", "\xc0", "\x80"] as $b) {
    $in = str_repeat($b, 100);
    $e = quoted_printable_encode($in);
    echo bin2hex($b), " ", max(array_map('strlen', explode("\r\n", $e))) <= 76 ? "ok" : "too long", " ";
    var_dump(quoted_printable_decode($e) === $in);
}
?>
--EXPECT--
ff ok bool(true)
f5 ok bool(true)
f4 ok bool(true)
c0 ok bool(true)
80 ok bool(true)
