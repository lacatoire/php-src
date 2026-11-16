--TEST--
zlib_get_coding_type() honours qvalues and token boundaries in Accept-Encoding
--EXTENSIONS--
zlib
--FILE--
<?php
$code = 'ini_set("zlib.output_compression", "On"); $t = zlib_get_coding_type(); ini_set("zlib.output_compression", "Off"); var_dump($t);';
foreach ([
    'gzip', 'gzip;q=0', 'gzip; q=0.0', 'identity;q=1, gzip;q=0', 'gzip;q=0, deflate',
    'GZIP', 'x-gzip', 'notgzip', 'gzip;q=0.5', 'br', 'deflate, gzip;q=0',
] as $header) {
    $env = ['HTTP_ACCEPT_ENCODING' => $header];
    $p = proc_open([PHP_BINARY, '-n', '-r', $code], [1 => ['pipe', 'w']], $pipes, null, $env);
    echo str_pad($header, 24), ': ', trim(stream_get_contents($pipes[1])), "\n";
    proc_close($p);
}
?>
--EXPECT--
gzip                    : string(4) "gzip"
gzip;q=0                : bool(false)
gzip; q=0.0             : bool(false)
identity;q=1, gzip;q=0  : bool(false)
gzip;q=0, deflate       : string(7) "deflate"
GZIP                    : string(4) "gzip"
x-gzip                  : string(4) "gzip"
notgzip                 : bool(false)
gzip;q=0.5              : string(4) "gzip"
br                      : bool(false)
deflate, gzip;q=0       : string(7) "deflate"
