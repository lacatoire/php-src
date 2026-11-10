--TEST--
Test rsort() function : usage variations - String values
--FILE--
<?php

$array = [
    "lemoN",
    "Orange",
    "banana",
    "apple",
    "Test",
    "TTTT",
    "ttt",
    "ww",
    "x",
    "X",
    "oraNGe",
    "BANANA",
];

echo "Default flag\n";
$temp_array = $array;
var_dump(rsort($temp_array)); // expecting : bool(true)
var_dump($temp_array);

echo "SORT_REGULAR\n";
$temp_array = $array;
var_dump(rsort($temp_array, SORT_REGULAR)); // expecting : bool(true)
var_dump($temp_array);

echo "SORT_STRING\n";
$temp_array = $array;
var_dump(rsort($temp_array, SORT_STRING)); // expecting : bool(true)
var_dump($temp_array);

?>
--EXPECT--
Default flag
bool(true)
array(12) {
  [0]=>
  string(1) "x"
  [1]=>
  string(2) "ww"
  [2]=>
  string(3) "ttt"
  [3]=>
  string(6) "oraNGe"
  [4]=>
  string(5) "lemoN"
  [5]=>
  string(6) "banana"
  [6]=>
  string(5) "apple"
  [7]=>
  string(1) "X"
  [8]=>
  string(4) "Test"
  [9]=>
  string(4) "TTTT"
  [10]=>
  string(6) "Orange"
  [11]=>
  string(6) "BANANA"
}
SORT_REGULAR
bool(true)
array(12) {
  [0]=>
  string(1) "x"
  [1]=>
  string(2) "ww"
  [2]=>
  string(3) "ttt"
  [3]=>
  string(6) "oraNGe"
  [4]=>
  string(5) "lemoN"
  [5]=>
  string(6) "banana"
  [6]=>
  string(5) "apple"
  [7]=>
  string(1) "X"
  [8]=>
  string(4) "Test"
  [9]=>
  string(4) "TTTT"
  [10]=>
  string(6) "Orange"
  [11]=>
  string(6) "BANANA"
}
SORT_STRING
bool(true)
array(12) {
  [0]=>
  string(1) "x"
  [1]=>
  string(2) "ww"
  [2]=>
  string(3) "ttt"
  [3]=>
  string(6) "oraNGe"
  [4]=>
  string(5) "lemoN"
  [5]=>
  string(6) "banana"
  [6]=>
  string(5) "apple"
  [7]=>
  string(1) "X"
  [8]=>
  string(4) "Test"
  [9]=>
  string(4) "TTTT"
  [10]=>
  string(6) "Orange"
  [11]=>
  string(6) "BANANA"
}
