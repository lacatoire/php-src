--TEST--
exec() and system() keep the whole last line when it follows a long line and has no trailing newline
--FILE--
<?php
$php = getenv('TEST_PHP_EXECUTABLE_ESCAPED');
$code = 'echo str_repeat(\".\", 20000), \"\n\", str_repeat(\"x\", 8190);';

ob_start();
$last = system($php . ' -n -r "' . $code . '"');
ob_end_clean();
var_dump(strlen($last));

$last = exec($php . ' -n -r "' . $code . '"', $lines);
var_dump(strlen($last), count($lines), strlen($lines[1]));
?>
--EXPECT--
int(8190)
int(8190)
int(2)
int(8190)
