--TEST--
stream_set_chunk_size() on the stream returned by pg_socket()
--EXTENSIONS--
pgsql
--FILE--
<?php

$db = pg_connect("host=127.0.0.1 port=1 connect_timeout=1", PGSQL_CONNECT_FORCE_NEW | PGSQL_CONNECT_ASYNC);
$socket = pg_socket($db);
var_dump(stream_set_chunk_size($socket, 100));
var_dump(stream_set_chunk_size($socket, 200));

?>
--EXPECT--
int(8192)
int(100)
