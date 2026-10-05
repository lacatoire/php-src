--TEST--
mb_output_handler() announces the converted charset in the Content-Type header
--EXTENSIONS--
mbstring
--CGI--
--FILE--
<?php
mb_internal_encoding("UTF-8");
mb_http_output("SJIS");

$headers = null;
ob_start();
ob_start(function (string $buffer, int $phase) use (&$headers) {
    $out = mb_output_handler($buffer, $phase);
    $headers = headers_list();
    return $out;
});
echo "日本";
ob_end_flush();
ob_end_clean();

var_dump(array_values(array_filter($headers, fn($h) => str_starts_with($h, "Content-Type:"))));
?>
--EXPECT--
array(1) {
  [0]=>
  string(42) "Content-Type: text/html; charset=Shift_JIS"
}
