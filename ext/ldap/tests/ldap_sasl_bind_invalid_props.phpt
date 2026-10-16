--TEST--
ldap_sasl_bind() - invalid SASL security properties
--EXTENSIONS--
ldap
--SKIPIF--
<?php
if (!function_exists('ldap_sasl_bind')) die('skip LDAP extension built without SASL support');
?>
--FILE--
<?php
$link = ldap_connect('ldap://localhost:3899');
ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3);

foreach (['foo=bar', 'notaprop', 'maxssf=abc'] as $props) {
    try {
        ldap_sasl_bind($link, null, 'secret', 'DIGEST-MD5', 'example.com', 'user', null, $props);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
ValueError: ldap_sasl_bind(): Argument #8 ($props) must be a valid list of SASL security properties
ValueError: ldap_sasl_bind(): Argument #8 ($props) must be a valid list of SASL security properties
ValueError: ldap_sasl_bind(): Argument #8 ($props) must be a valid list of SASL security properties
