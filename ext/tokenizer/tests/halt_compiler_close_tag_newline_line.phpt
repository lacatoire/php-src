--TEST--
Line of the data after __halt_compiler()?> followed by a newline
--EXTENSIONS--
tokenizer
--FILE--
<?php
$code = "<?php __halt_compiler()?>\nabc";

$tokens = token_get_all($code);
var_dump(end($tokens)[2]);
$tokens = token_get_all($code, TOKEN_PARSE);
var_dump(end($tokens)[2]);
$tokens = PhpToken::tokenize($code);
var_dump(end($tokens)->line);

set_error_handler(function () use ($code) {
    token_get_all($code);
    return true;
});
var_dump(eval("\$x = (integer) 1;\n\nreturn __LINE__;"));
?>
--EXPECT--
int(2)
int(2)
int(2)
int(3)
