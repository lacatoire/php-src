--TEST--
Bug #72506 (idn_to_ascii with INTL_IDNA_VARIANT_UTS46 fatals for long domain names)
--EXTENSIONS--
intl
--SKIPIF--
<?php
if (!defined('INTL_IDNA_VARIANT_UTS46')) die('skip no UTS #46 API');
?>
--FILE--
<?php
// ASCII domain name with 255 characters
$domain = str_repeat('a.', 126) . 'aaa';
$result = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46, $info);
var_dump($result, $info['errors'], strlen($info['result']));

// ASCII domain name with 256 characters – one character added
$domain .= 'a';
$result = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46, $info);
var_dump($result, $info['errors'], strlen($info['result']));

// International domain name with cyrillic "ф" characters
$domain = str_repeat('ф.', 32) . 'a';
$result = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46, $info);
var_dump($result, $info['errors'], strlen($info['result']));
?>
--EXPECT--
bool(false)
int(4)
int(255)
bool(false)
int(4)
int(256)
bool(false)
int(4)
int(257)
