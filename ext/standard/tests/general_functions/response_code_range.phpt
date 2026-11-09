--TEST--
header(), http_response_code(): the response code must be a valid HTTP status
--FILE--
<?php
ob_start();
foreach ([-1, 99, 600, 99999999, 4294967296, 4294967500, PHP_INT_MAX] as $code) {
    try {
        header("X-T: 1", true, $code);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
    try {
        http_response_code($code);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
header("X-T: 1", true, 404);
var_dump(http_response_code());
?>
--EXPECTF--
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
header(): Argument #3 ($response_code) must be 0 or between 100 and 599
http_response_code(): Argument #1 ($response_code) must be 0 or between 100 and 599
int(404)
