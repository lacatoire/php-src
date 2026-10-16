--TEST--
curl_reset(): write, header and read callables are released
--EXTENSIONS--
curl
--FILE--
<?php
class Probe {
    public function __invoke($ch, $data) { return strlen($data); }
}

foreach ([CURLOPT_WRITEFUNCTION, CURLOPT_HEADERFUNCTION, CURLOPT_READFUNCTION] as $option) {
    $ch = curl_init();
    $probe = new Probe();
    curl_setopt($ch, $option, $probe);
    $ref = WeakReference::create($probe);
    unset($probe);
    curl_reset($ch);
    var_dump($ref->get() === null);
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
