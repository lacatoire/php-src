--TEST--
FPM: session_set_cookie_params() changes nothing when an entry is locked
--EXTENSIONS--
session
--SKIPIF--
<?php include "skipif.inc"; ?>
--FILE--
<?php

require_once "tester.inc";

$cfg = <<<EOT
[global]
error_log = {{FILE:LOG}}
[unconfined]
listen = {{ADDR}}
pm = dynamic
pm.max_children = 5
pm.start_servers = 1
pm.min_spare_servers = 1
pm.max_spare_servers = 3
php_admin_value[session.cookie_path]=/locked
EOT;

$code = <<<'EOT'
<?php
ob_start();
set_error_handler(function ($errno, $message) {
    echo $message, "\n";
    return true;
});
var_dump(session_set_cookie_params(['lifetime' => 77, 'path' => '/x', 'domain' => 'dom.example']));
var_dump(ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
EOT;

$tester = new FPM\Tester($cfg, $code);
$tester->start();
$tester->expectLogStartNotices();
$tester->request()->expectBody([
    'session_set_cookie_params(): Session cookie parameters cannot be changed, session.cookie_path is locked',
    'bool(false)',
    'string(1) "0"',
    'string(7) "/locked"',
    'string(0) ""',
]);
$tester->terminate();
$tester->close();

?>
Done
--EXPECT--
Done
--CLEAN--
<?php
require_once "tester.inc";
FPM\Tester::clean();
?>
