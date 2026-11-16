--TEST--
iptcembed(): the IPTC data is inserted into a JPEG without APP0/APP1, and replaces a leading APP13
--FILE--
<?php
function segments(string $jpeg): string {
    $list = [];
    $pos = 2;
    while ($jpeg[$pos] === "\xFF") {
        $m = ord($jpeg[$pos + 1]);
        $list[] = strtoupper(dechex($m));
        if ($m === 0xDA) {
            break;
        }
        $pos += 2 + unpack('n', $jpeg, $pos + 2)[1];
    }
    return implode(' ', $list);
}

function app13(string $jpeg, int $len): ?string {
    $pos = strpos($jpeg, "\xFF\xED");
    return $pos === false ? null : substr($jpeg, $pos + 30, $len);
}

$file = __DIR__ . '/iptcembed_no_app0.jpg';
$iptc = "\x1c\x02\x78\x00\x0bhello world";
$com = "\xFF\xFE\x00\x05abc";
$sos = "\xFF\xDA\x00\x02data\xFF\xD9";
$app13_old = "\xFF\xED\x00\x04zz";

$inputs = [
    'APP0'       => "\xFF\xD8\xFF\xE0\x00\x10JFIF\0\x01\x01\0\0\x01\0\x01\0\0" . $com . $sos,
    'no APP0'    => "\xFF\xD8" . $com . $sos,
    'APP13 first' => "\xFF\xD8" . $app13_old . $com . $sos,
    'SOS only'   => "\xFF\xD8" . $sos,
];
foreach ($inputs as $name => $jpeg) {
    file_put_contents($file, $jpeg);
    $out = iptcembed($iptc, $file);
    echo $name, ': ', segments($out), ' ', bin2hex(substr($out, -6)), "\n";
    var_dump(app13($out, strlen($iptc)) === $iptc);
}

// Nowhere to put the data
file_put_contents($file, "\xFF\xD8");
var_dump(iptcembed($iptc, $file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/iptcembed_no_app0.jpg');
?>
--EXPECT--
APP0: E0 ED FE DA 64617461ffd9
bool(true)
no APP0: ED FE DA 64617461ffd9
bool(true)
APP13 first: ED FE DA 64617461ffd9
bool(true)
SOS only: ED DA 64617461ffd9
bool(true)
bool(false)