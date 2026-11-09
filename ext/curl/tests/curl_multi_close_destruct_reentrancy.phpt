--TEST--
curl_multi_close(): a handle's own __destruct() reentering curl_multi_add_handle() must not lose the added handle
--EXTENSIONS--
curl
--FILE--
<?php
class Reg {
    public $mh;
    public $newch;
    public function __destruct() {
        curl_multi_add_handle($this->mh, $this->newch);
    }
}

$mh = curl_multi_init();
$victim = curl_init('file://' . __DIR__ . '/curl_testdata1.txt');
$reg = new Reg();
$reg->mh = $mh;
$reg->newch = curl_init('file://' . __DIR__ . '/curl_testdata1.txt');
curl_setopt($victim, CURLOPT_WRITEFUNCTION, function ($c, $d) use ($reg) {
    return strlen($d);
});
unset($reg);
curl_multi_add_handle($mh, $victim);
unset($victim);

curl_multi_close($mh);

var_dump(count(curl_multi_get_handles($mh)));
?>
--EXPECT--
int(1)
