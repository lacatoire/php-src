--TEST--
odbc_fetch_array(): numeric column names are converted to integer keys
--EXTENSIONS--
odbc
--SKIPIF--
<?php include 'skipif.inc'; ?>
--FILE--
<?php

include 'config.inc';

$conn = odbc_connect($dsn, $user, $pass);

$res = odbc_exec($conn, 'SELECT 5 AS "3", 6 AS y');
$row = odbc_fetch_array($res);
var_dump(isset($row[3]), isset($row["3"]), array_key_exists(3, $row));
var_dump(array_keys($row));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
array(2) {
  [0]=>
  int(3)
  [1]=>
  string(1) "y"
}
