--TEST--
pg_set_error_verbosity() applies PGSQL_ERRORS_TERSE and rejects invalid values
--EXTENSIONS--
pgsql
--SKIPIF--
<?php include("inc/skipif.inc"); ?>
--FILE--
<?php
include 'inc/config.inc';

$db = pg_connect($conn_str);

var_dump(pg_set_error_verbosity($db, PGSQL_ERRORS_VERBOSE));
var_dump(pg_set_error_verbosity($db, PGSQL_ERRORS_TERSE));
var_dump(pg_set_error_verbosity($db, PGSQL_ERRORS_DEFAULT));

foreach ([4, 5, 99, -1] as $value) {
    try {
        pg_set_error_verbosity($db, $value);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

foreach ([3, 99, -1] as $value) {
    try {
        pg_set_error_context_visibility($db, $value);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
int(1)
int(2)
int(0)
pg_set_error_verbosity(): Argument #2 ($verbosity) must be one of PGSQL_ERRORS_TERSE, PGSQL_ERRORS_DEFAULT, PGSQL_ERRORS_VERBOSE or PGSQL_ERRORS_SQLSTATE
pg_set_error_verbosity(): Argument #2 ($verbosity) must be one of PGSQL_ERRORS_TERSE, PGSQL_ERRORS_DEFAULT, PGSQL_ERRORS_VERBOSE or PGSQL_ERRORS_SQLSTATE
pg_set_error_verbosity(): Argument #2 ($verbosity) must be one of PGSQL_ERRORS_TERSE, PGSQL_ERRORS_DEFAULT, PGSQL_ERRORS_VERBOSE or PGSQL_ERRORS_SQLSTATE
pg_set_error_verbosity(): Argument #2 ($verbosity) must be one of PGSQL_ERRORS_TERSE, PGSQL_ERRORS_DEFAULT, PGSQL_ERRORS_VERBOSE or PGSQL_ERRORS_SQLSTATE
pg_set_error_context_visibility(): Argument #2 ($visibility) must be one of PGSQL_SHOW_CONTEXT_NEVER, PGSQL_SHOW_CONTEXT_ERRORS or PGSQL_SHOW_CONTEXT_ALWAYS
pg_set_error_context_visibility(): Argument #2 ($visibility) must be one of PGSQL_SHOW_CONTEXT_NEVER, PGSQL_SHOW_CONTEXT_ERRORS or PGSQL_SHOW_CONTEXT_ALWAYS
pg_set_error_context_visibility(): Argument #2 ($visibility) must be one of PGSQL_SHOW_CONTEXT_NEVER, PGSQL_SHOW_CONTEXT_ERRORS or PGSQL_SHOW_CONTEXT_ALWAYS
