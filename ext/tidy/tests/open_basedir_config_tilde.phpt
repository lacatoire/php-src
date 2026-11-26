--TEST--
tidy config file paths starting with "~" do not bypass open_basedir
--EXTENSIONS--
tidy
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
if (!function_exists('posix_getpwuid')) die('skip posix extension not available');
$home = posix_getpwuid(posix_geteuid())['dir'] ?? '';
if ($home === '' || !is_dir($home)) die('skip no home directory');
?>
--FILE--
<?php
$dir = __DIR__ . '/open_basedir_config_tilde';
@mkdir("$dir/allowed", 0777, true);
$config = "$dir/secret.conf";
file_put_contents($config, "alt-text: the quick brown fox\n");

$user = posix_getpwuid(posix_geteuid())['name'];
$home = posix_getpwuid(posix_geteuid())['dir'];
$tildePath = "~$user" . str_repeat('/..', substr_count(rtrim($home, '/'), '/')) . $config;

chdir("$dir/allowed");
ini_set('open_basedir', "$dir/allowed");

var_dump(@tidy_parse_string('<p>x</p>', $config));
$tidy = @tidy_parse_string('<p>x</p>', $tildePath);
var_dump($tidy->getOpt('alt-text'));
?>
--CLEAN--
<?php
$dir = __DIR__ . '/open_basedir_config_tilde';
@unlink("$dir/secret.conf");
@rmdir("$dir/allowed");
@rmdir($dir);
?>
--EXPECT--
bool(false)
string(0) ""
