--TEST--
SNMPv3 calls apply the credentials they are given for a security name used before
--EXTENSIONS--
snmp
--SKIPIF--
<?php
require_once(__DIR__.'/skipif.inc');
if (getenv('SKIP_ASAN')) die('skip Timeouts under ASAN');
if (PHP_OS_FAMILY === 'Windows') die('xfail SNMP tests might possibly fail on Windows');
?>
--FILE--
<?php
require_once(__DIR__.'/snmp_include.inc');

function read_contact($hostname, $user, $auth_pass, $priv_pass, $timeout, $retries) {
    return @snmp3_get($hostname, $user, 'authPriv', 'MD5', $auth_pass, 'AES', $priv_pass, '.1.3.6.1.2.1.1.4.0', $timeout, $retries);
}

var_dump(read_contact($hostname, $rwuser, 'wrongpass1', $priv_pass, $timeout, $retries));
var_dump(is_string(read_contact($hostname, $rwuser, $auth_pass, $priv_pass, $timeout, $retries)));
var_dump(read_contact($hostname, $rwuser, 'wrongpass1', $priv_pass, $timeout, $retries));
var_dump(read_contact($hostname, $rwuser, $auth_pass, 'wrongpass1', $timeout, $retries));

?>
--EXPECT--
bool(false)
bool(true)
bool(false)
bool(false)
