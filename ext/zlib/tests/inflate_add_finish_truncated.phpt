--TEST--
inflate_add(): ZLIB_FINISH on a truncated stream fails
--EXTENSIONS--
zlib
--FILE--
<?php
mt_srand(1);
$data = '';
for ($i = 0; $i < 20000; $i++) {
    $data .= chr(97 + mt_rand(0, 25));
}
$z = zlib_encode($data, ZLIB_ENCODING_DEFLATE);
$cut = substr($z, 0, intdiv(strlen($z), 2));

$c = inflate_init(ZLIB_ENCODING_DEFLATE);
var_dump(strlen(inflate_add($c, $cut)) > 0, inflate_get_status($c));

$c = inflate_init(ZLIB_ENCODING_DEFLATE);
var_dump(inflate_add($c, $cut, ZLIB_FINISH));

// A complete stream is fine, in one chunk or several
$c = inflate_init(ZLIB_ENCODING_DEFLATE);
var_dump(inflate_add($c, $z, ZLIB_FINISH) === $data);

$c = inflate_init(ZLIB_ENCODING_DEFLATE);
$out = inflate_add($c, substr($z, 0, 100)) . inflate_add($c, substr($z, 100), ZLIB_FINISH);
var_dump($out === $data);

// Finishing a stream that already ended with nothing left is not an error
var_dump(inflate_add($c, '', ZLIB_FINISH));
?>
--EXPECTF--
bool(true)
int(%d)

Warning: inflate_add(): buffer error in %s on line %d
bool(false)
bool(true)
bool(true)
string(0) ""
