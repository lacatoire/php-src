--TEST--
odbc_connection_string_is_quoted() rejects strings without a closing brace
--EXTENSIONS--
odbc
--FILE--
<?php
foreach (["{", "{abc", "{a}}", "{}}", "{a}}}}", "{abc}", "{}", "{a}}b}", "{a}b}", "abc}"] as $s) {
    echo str_pad(json_encode($s), 10), var_export(odbc_connection_string_is_quoted($s), true), "\n";
}
?>
--EXPECT--
"{"       false
"{abc"    false
"{a}}"    false
"{}}"     false
"{a}}}}"  false
"{abc}"   true
"{}"      true
"{a}}b}"  true
"{a}b}"   false
"abc}"    false
