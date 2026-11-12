--TEST--
snmp_set_valueretrieval() does not apply to the following requests of a worker
--EXTENSIONS--
snmp
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--CONFLICTS--
server
--FILE--
<?php

include __DIR__ . '/../../../sapi/cli/tests/php_cli_server.inc';

$extension_file = ini_get('extension_dir') . '/snmp.' . PHP_SHLIB_SUFFIX;
$args = is_file($extension_file) ? ['-d', 'extension=' . $extension_file] : [];

php_cli_server_start('
if (isset($_GET["set"])) {
    snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
}
echo snmp_get_valueretrieval(), "\n";
', 'index.php', $args);

foreach (['/', '/?set=1', '/', '/'] as $path) {
    echo file_get_contents('http://' . PHP_CLI_SERVER_ADDRESS . $path);
}

?>
--EXPECT--
0
1
0
0
