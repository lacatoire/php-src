--TEST--
A filter appended to a persistent stream does not keep its request resource after the request
--FILE--
<?php

$server = stream_socket_server('tcp://127.0.0.1:0');
[$host, $port] = explode(':', stream_socket_get_name($server, false));
$client = pfsockopen($host, (int) $port, $errno, $errstr, 2);
var_dump(stream_filter_append($client, 'string.rot13', STREAM_FILTER_ALL));
var_dump(stream_filter_prepend($client, 'string.toupper', STREAM_FILTER_READ));
echo "done\n";

?>
--EXPECTF--
resource(%d) of type (stream filter)
resource(%d) of type (stream filter)
done
