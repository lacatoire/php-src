--TEST--
session.save_path (files): a ';' in a plain path is kept, an invalid mode is refused
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
$base = __DIR__ . '/session_save_path_files_parsing_dir';
mkdir($base);
mkdir("$base/p1;p2");
mkdir("$base/p3");

function try_path(string $path): void {
    session_save_path($path);
    session_id('fixedid1');
    var_dump(session_start());
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['a'] = 1;
        session_write_close();
    }
}

try_path("$base/p1;p2");
var_dump(file_exists("$base/p1;p2/sess_fixedid1"));

foreach (['0;;', '0;8;', '0;0644x;', '0;40000000600;'] as $prefix) {
    try_path($prefix . "$base/p3");
}
var_dump(file_exists("$base/p3/sess_fixedid1"));

try_path("0;0600;$base/p3");
var_dump(substr(sprintf('%o', fileperms("$base/p3/sess_fixedid1")), -4));
?>
--CLEAN--
<?php
$base = __DIR__ . '/session_save_path_files_parsing_dir';
@unlink("$base/p1;p2/sess_fixedid1");
@unlink("$base/p3/sess_fixedid1");
@rmdir("$base/p1;p2");
@rmdir("$base/p3");
@rmdir($base);
?>
--EXPECTF--
bool(true)
bool(true)

Warning: The second parameter in session.save_path is invalid in %s on line %d

Warning: session_start(): Failed to initialize storage module: files (path: 0;;%s) in %s on line %d
bool(false)

Warning: The second parameter in session.save_path is invalid in %s on line %d

Warning: session_start(): Failed to initialize storage module: files (path: 0;8;%s) in %s on line %d
bool(false)

Warning: The second parameter in session.save_path is invalid in %s on line %d

Warning: session_start(): Failed to initialize storage module: files (path: 0;0644x;%s) in %s on line %d
bool(false)

Warning: The second parameter in session.save_path is invalid in %s on line %d

Warning: session_start(): Failed to initialize storage module: files (path: 0;40000000600;%s) in %s on line %d
bool(false)
bool(false)
bool(true)
string(4) "0600"
