--TEST--
Lexer errors are not chained when tokenizing, highlighting or stripping
--EXTENSIONS--
tokenizer
--INI--
memory_limit=8M
--FILE--
<?php
$code = '<?php ' . str_repeat(')', 20000);

var_dump(count(token_get_all($code)));
var_dump(count(PhpToken::tokenize($code)));
var_dump(strlen(highlight_string($code, true)) > 0);

$file = __DIR__ . '/many_lexer_errors_not_chained.tmp';
file_put_contents($file, $code);
var_dump(strlen(php_strip_whitespace($file)));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/many_lexer_errors_not_chained.tmp');
?>
--EXPECT--
int(20001)
int(20001)
bool(true)
int(20006)
