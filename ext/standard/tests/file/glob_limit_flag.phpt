--TEST--
glob(): GLOB_LIMIT is a registered, usable flag
--FILE--
<?php
echo "*** Testing glob() : GLOB_LIMIT flag ***\n";

var_dump(defined('GLOB_LIMIT'));

$dirname = __DIR__ . "/glob_limit_flag";
mkdir($dirname);
fclose(fopen("$dirname/a.txt", "w"));
fclose(fopen("$dirname/b.txt", "w"));

// Before the fix, GLOB_LIMIT was rejected by the available-flags mask:
// glob() emitted an E_WARNING and returned false instead of matching.
$results = glob("$dirname/*.txt", GLOB_LIMIT);
sort($results);
var_dump($results);

$results = glob("$dirname/*.txt", GLOB_LIMIT | GLOB_NOSORT);
sort($results);
var_dump($results);

echo "Done\n";
?>
--CLEAN--
<?php
$dirname = __DIR__ . "/glob_limit_flag";
unlink("$dirname/a.txt");
unlink("$dirname/b.txt");
rmdir($dirname);
?>
--EXPECTF--
*** Testing glob() : GLOB_LIMIT flag ***
bool(true)
array(2) {
  [0]=>
  string(%d) "%s/glob_limit_flag/a.txt"
  [1]=>
  string(%d) "%s/glob_limit_flag/b.txt"
}
array(2) {
  [0]=>
  string(%d) "%s/glob_limit_flag/a.txt"
  [1]=>
  string(%d) "%s/glob_limit_flag/b.txt"
}
Done
