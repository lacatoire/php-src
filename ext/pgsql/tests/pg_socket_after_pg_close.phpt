--TEST--
pg_socket() stream does not use the connection after pg_close()
--EXTENSIONS--
pgsql
--FILE--
<?php
$db = pg_connect('host=127.0.0.1 port=1 connect_timeout=1', PGSQL_CONNECT_FORCE_NEW | PGSQL_CONNECT_ASYNC);
$socket = pg_socket($db);
var_dump(stream_set_blocking($socket, false));
pg_close($db);
unset($db);
var_dump(stream_set_blocking($socket, false));
$read = [$socket];
$write = null;
$except = null;
try {
    var_dump(@stream_select($read, $write, $except, 0));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
fclose($socket);
echo "done\n";
?>
--EXPECT--
bool(true)
bool(false)
ValueError: No stream arrays were passed
done
