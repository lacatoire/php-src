--TEST--
session_gc(): the files handler counts only the entries it removed
--INI--
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
$dir = __DIR__ . '/session_gc_files_count_dir';
mkdir($dir);
session_save_path($dir);
session_start();

file_put_contents("$dir/sess_aaa", 'x');
touch("$dir/sess_aaa", time() - 100000);
// an entry named like a session file that unlink() cannot remove
mkdir("$dir/sess_adir");
touch("$dir/sess_adir", time() - 100000);

var_dump(session_gc());
var_dump(file_exists("$dir/sess_aaa"), is_dir("$dir/sess_adir"));
session_destroy();
?>
--CLEAN--
<?php
$dir = __DIR__ . '/session_gc_files_count_dir';
@rmdir("$dir/sess_adir");
foreach (glob("$dir/sess_*") as $file) {
    @unlink($file);
}
@rmdir($dir);
?>
--EXPECT--
int(1)
bool(false)
bool(true)
