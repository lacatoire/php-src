--TEST--
odbc_execute() with a file parameter reads the file through SQLParamData()
--EXTENSIONS--
odbc
--SKIPIF--
<?php include 'skipif.inc'; ?>
--FILE--
<?php
include 'config.inc';

$conn = odbc_connect($dsn, $user, $pass);
odbc_exec($conn, "CREATE TABLE odbc_execute_file_param (txt VARCHAR(50))");

$file = __DIR__ . '/odbc_execute_file_param.txt';
file_put_contents($file, 'from file');

$stmt = odbc_prepare($conn, "INSERT INTO odbc_execute_file_param VALUES (?)");
var_dump(odbc_execute($stmt, ["'" . $file . "'"]));

$res = odbc_exec($conn, "SELECT txt FROM odbc_execute_file_param");
odbc_fetch_row($res);
var_dump(odbc_result($res, 1));
?>
--CLEAN--
<?php
include 'config.inc';

@unlink(__DIR__ . '/odbc_execute_file_param.txt');
$conn = odbc_connect($dsn, $user, $pass);
odbc_exec($conn, "DROP TABLE odbc_execute_file_param");
?>
--EXPECT--
bool(true)
string(9) "from file"
