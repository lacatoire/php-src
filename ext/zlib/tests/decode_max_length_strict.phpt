--TEST--
gzdecode(), zlib_decode(), gzinflate(), gzuncompress(): max_length is a strict limit
--EXTENSIONS--
zlib
--FILE--
<?php
$fns = [
    'gzdecode' => ['gzencode', 'gzdecode'],
    'zlib_decode' => ['gzencode', 'zlib_decode'],
    'gzinflate' => ['gzdeflate', 'gzinflate'],
    'gzuncompress' => ['gzcompress', 'gzuncompress'],
];
foreach ([[26, 25], [25, 25], [24, 25], [105251, 100000], [100000, 100000], [100001, 100000]] as [$n, $max]) {
    foreach ($fns as $name => [$encode, $decode]) {
        $result = @$decode($encode(str_repeat('a', $n), 1), $max);
        echo "$name $n/$max: ", $result === false ? 'false' : strlen($result), "\n";
    }
}
?>
--EXPECT--
gzdecode 26/25: false
zlib_decode 26/25: false
gzinflate 26/25: false
gzuncompress 26/25: false
gzdecode 25/25: 25
zlib_decode 25/25: 25
gzinflate 25/25: 25
gzuncompress 25/25: 25
gzdecode 24/25: 24
zlib_decode 24/25: 24
gzinflate 24/25: 24
gzuncompress 24/25: 24
gzdecode 105251/100000: false
zlib_decode 105251/100000: false
gzinflate 105251/100000: false
gzuncompress 105251/100000: false
gzdecode 100000/100000: 100000
zlib_decode 100000/100000: 100000
gzinflate 100000/100000: 100000
gzuncompress 100000/100000: 100000
gzdecode 100001/100000: false
zlib_decode 100001/100000: false
gzinflate 100001/100000: false
gzuncompress 100001/100000: false
