--TEST--
idn_to_ascii() and idn_to_utf8() with a domain longer than the fixed buffers
--EXTENSIONS--
intl
--FILE--
<?php
function dom($n) {
    $l = [];
    $left = $n;
    while ($left > 0) {
        $x = min(60, $left);
        $l[] = str_repeat("a", $x);
        $left -= $x + 1;
    }
    return implode(".", $l);
}

foreach ([253, 254, 255, 300] as $n) {
    $info = [];
    $r = idn_to_ascii(dom($n), 0, INTL_IDNA_VARIANT_UTS46, $info);
    printf("%d: %s errors=%s icu=%d\n", $n, var_export($r === false ? false : strlen($r), true), json_encode($info['errors'] ?? null), intl_get_error_code());
}

$info = [];
var_dump(strlen(idn_to_utf8(dom(1000), 0, INTL_IDNA_VARIANT_UTS46, $info)), $info['errors']);
$info = [];
var_dump(strlen(idn_to_utf8(dom(1010), 0, INTL_IDNA_VARIANT_UTS46, $info)), $info['errors'], intl_get_error_code());
?>
--EXPECT--
253: 253 errors=0 icu=0
254: false errors=4 icu=0
255: false errors=4 icu=0
300: false errors=4 icu=0
int(1000)
int(0)
int(1010)
int(0)
int(0)
