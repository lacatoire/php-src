--TEST--
pg_convert(): TypeError messages name the type of the given value
--EXTENSIONS--
pgsql
--SKIPIF--
<?php include("inc/skipif.inc"); ?>
--FILE--
<?php
include 'inc/config.inc';
$table_name = "pg_convert_type_error_given_type";

$db = pg_connect($conn_str);
pg_query($db, "CREATE TABLE {$table_name} (i int, f float8, n numeric(10,2), d date)");

foreach ([["i", "abc"], ["f", "abc"], ["n", "abc"], ["d", true]] as [$col, $val]) {
    try {
        pg_convert($db, $table_name, [$col => $val]);
    } catch (TypeError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--CLEAN--
<?php
include 'inc/config.inc';
$db = pg_connect($conn_str);
pg_query($db, "DROP TABLE IF EXISTS pg_convert_type_error_given_type");
?>
--EXPECT--
pg_convert(): Field "i" must be of type int|null, string given
pg_convert(): Field "f" must be of type float|string|int|null, string given
pg_convert(): Field "n" must be of type float|string|int|null, string given
pg_convert(): Field "d" must be of type string|null, bool given
