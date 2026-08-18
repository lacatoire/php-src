--TEST--
zip_entry_close(): declared return type matches the only possible outcome
--EXTENSIONS--
zip
--FILE--
<?php

$function = new ReflectionFunction('zip_entry_close');
var_dump((string) $function->getReturnType());

/* zip_entry_open() can genuinely report failure and keeps its bool */
$function = new ReflectionFunction('zip_entry_open');
var_dump((string) $function->getReturnType());

?>
--EXPECT--
string(4) "true"
string(4) "bool"
