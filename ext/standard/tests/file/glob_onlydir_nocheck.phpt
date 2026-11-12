--TEST--
glob(): GLOB_ONLYDIR | GLOB_NOCHECK must not drop the NOCHECK literal pattern
--FILE--
<?php
echo "*** Testing glob() : GLOB_ONLYDIR | GLOB_NOCHECK ***\n";

// GLOB_NOCHECK guarantees at least one result (the literal pattern) when
// nothing matches. Before the fix, the GLOB_ONLYDIR post-filter stat()ed
// that synthetic entry, found no such path, and silently dropped it.
$pattern = __DIR__ . "/glob_onlydir_nocheck_missing_xyz/*";
var_dump(glob($pattern, GLOB_NOCHECK));
var_dump(glob($pattern, GLOB_ONLYDIR | GLOB_NOCHECK));

// Real matches must still be filtered to directories only.
$dirname = __DIR__ . "/glob_onlydir_nocheck";
mkdir($dirname);
mkdir("$dirname/subdir");
fclose(fopen("$dirname/file.txt", "w"));

$results = glob("$dirname/*", GLOB_ONLYDIR | GLOB_NOCHECK);
sort($results);
var_dump($results);

echo "Done\n";
?>
--CLEAN--
<?php
$dirname = __DIR__ . "/glob_onlydir_nocheck";
unlink("$dirname/file.txt");
rmdir("$dirname/subdir");
rmdir($dirname);
?>
--EXPECTF--
*** Testing glob() : GLOB_ONLYDIR | GLOB_NOCHECK ***
array(1) {
  [0]=>
  string(%d) "%s/glob_onlydir_nocheck_missing_xyz/*"
}
array(1) {
  [0]=>
  string(%d) "%s/glob_onlydir_nocheck_missing_xyz/*"
}
array(1) {
  [0]=>
  string(%d) "%s/glob_onlydir_nocheck/subdir"
}
Done
