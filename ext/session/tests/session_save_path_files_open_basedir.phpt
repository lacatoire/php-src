--TEST--
session.save_path (files): open_basedir is checked when the session is opened
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
$base = __DIR__ . '/session_save_path_files_open_basedir_dir';
mkdir($base);
mkdir("$base/allowed");
mkdir("$base/allowed/a");
mkdir("$base/x");

ini_set('open_basedir', "$base/allowed");

// relative path, accepted while it is inside the base, then the cwd changes
chdir("$base/allowed/a");
session_save_path('../x');
chdir("$base/allowed");
session_id('fixedid1');
var_dump(session_start());
var_dump(@file_exists("$base/x/sess_fixedid1"));

// an empty path part is the temporary directory, which is outside the base
session_save_path('0;');
session_id('fixedid1');
var_dump(session_start());

session_save_path("$base/allowed/a");
session_id('fixedid1');
var_dump(session_start());
$_SESSION['a'] = 1;
session_write_close();
var_dump(file_exists("$base/allowed/a/sess_fixedid1"));
?>
--CLEAN--
<?php
$base = __DIR__ . '/session_save_path_files_open_basedir_dir';
@unlink("$base/allowed/a/sess_fixedid1");
@unlink("$base/x/sess_fixedid1");
@rmdir("$base/allowed/a");
@rmdir("$base/allowed");
@rmdir("$base/x");
@rmdir($base);
?>
--EXPECTF--
Warning: session_start(): open_basedir restriction in effect. File(%sx) is not within the allowed path(s): (%sallowed) in %s on line %d

Warning: session_start(): Failed to initialize storage module: files (path: ../x) in %s on line %d
bool(false)
bool(false)

Warning: session_start(): open_basedir restriction in effect. File(%s) is not within the allowed path(s): (%sallowed) in %s on line %d

Warning: session_start(): Failed to initialize storage module: files (path: 0;) in %s on line %d
bool(false)
bool(true)
bool(true)
