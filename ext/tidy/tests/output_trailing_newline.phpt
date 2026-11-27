--TEST--
Output strips the whole trailing newline with newline=CRLF and UTF-16 output encodings
--EXTENSIONS--
tidy
--FILE--
<?php
foreach ([['newline' => 'CRLF'], ['output-encoding' => 'utf16le'], ['output-encoding' => 'utf16be']] as $config) {
    $config += ['show-body-only' => true];
    $tidy = tidy_parse_string('<p>x</p>', $config);
    $text = $tidy->body()->child[0]->child[0];

    var_dump(bin2hex(tidy_get_output($tidy)));
    var_dump(bin2hex((string) $tidy));
    var_dump(bin2hex($tidy->value));
    var_dump(bin2hex(tidy_repair_string('<p>x</p>', $config)));
    var_dump(bin2hex($text->value));
    var_dump(bin2hex((string) $text));
}
?>
--EXPECT--
string(20) "3c703e783c2f703e0d0a"
string(20) "3c703e783c2f703e0d0a"
string(20) "3c703e783c2f703e0d0a"
string(20) "3c703e783c2f703e0d0a"
string(2) "78"
string(2) "78"
string(36) "3c0070003e0078003c002f0070003e000a00"
string(36) "3c0070003e0078003c002f0070003e000a00"
string(36) "3c0070003e0078003c002f0070003e000a00"
string(36) "3c0070003e0078003c002f0070003e000a00"
string(4) "7800"
string(4) "7800"
string(36) "003c0070003e0078003c002f0070003e000a"
string(36) "003c0070003e0078003c002f0070003e000a"
string(36) "003c0070003e0078003c002f0070003e000a"
string(36) "003c0070003e0078003c002f0070003e000a"
string(4) "0078"
string(4) "0078"
