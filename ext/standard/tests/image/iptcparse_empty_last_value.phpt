--TEST--
iptcparse(): a dataset with an empty value ending the data is kept
--FILE--
<?php
$ds = fn($rec, $tag, $val) => "\x1c" . chr($rec) . chr($tag) . pack('n', strlen($val)) . $val;
$long = fn($rec, $tag, $val) => "\x1c" . chr($rec) . chr($tag) . "\x80\x04" . pack('N', strlen($val)) . $val;

var_dump(iptcparse($ds(2, 5, "") . $ds(2, 120, "x")));
var_dump(iptcparse($ds(2, 120, "x") . $ds(2, 5, "")));
var_dump(iptcparse($ds(2, 5, "")));
var_dump(iptcparse($long(2, 5, "")));
var_dump(iptcparse($ds(2, 5, "z")));
var_dump(iptcparse("\x1c\x02\x05\x00"));
var_dump(iptcparse("\x1c\x02\x05\x80\x04\x00\x00\x00"));
?>
--EXPECT--
array(2) {
  ["2#005"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
  ["2#120"]=>
  array(1) {
    [0]=>
    string(1) "x"
  }
}
array(2) {
  ["2#120"]=>
  array(1) {
    [0]=>
    string(1) "x"
  }
  ["2#005"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
}
array(1) {
  ["2#005"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
}
array(1) {
  ["2#005"]=>
  array(1) {
    [0]=>
    string(0) ""
  }
}
array(1) {
  ["2#005"]=>
  array(1) {
    [0]=>
    string(1) "z"
  }
}
bool(false)
bool(false)
