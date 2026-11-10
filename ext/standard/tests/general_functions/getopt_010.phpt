--TEST--
getopt(): a long option with an empty value (--opt=)
--ARGS--
--req= --opt= --other=x --flag --last=
--FILE--
<?php
var_dump(getopt("", ["req:", "opt::", "other:", "flag", "last:"]));
?>
--EXPECT--
array(5) {
  ["req"]=>
  string(0) ""
  ["opt"]=>
  string(0) ""
  ["other"]=>
  string(1) "x"
  ["flag"]=>
  bool(false)
  ["last"]=>
  string(0) ""
}
