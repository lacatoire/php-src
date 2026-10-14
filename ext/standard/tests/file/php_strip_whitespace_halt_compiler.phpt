--TEST--
php_strip_whitespace() keeps the data after __halt_compiler() verbatim
--FILE--
<?php
$file = __DIR__ . '/php_strip_whitespace_halt_compiler.tmp.php';

file_put_contents($file, "<?php echo 1;  /* c */ __halt_compiler ( ) ;DATA  # not a comment\n/* also\nx   y");
var_dump(php_strip_whitespace($file));

file_put_contents($file, "<?php echo 1; __halt_compiler() ?>\n  raw   data  // x");
var_dump(php_strip_whitespace($file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/php_strip_whitespace_halt_compiler.tmp.php');
?>
--EXPECT--
string(70) "<?php echo 1; __halt_compiler ( ) ;DATA  # not a comment
/* also
x   y"
string(53) "<?php echo 1; __halt_compiler() ?>
  raw   data  // x"
