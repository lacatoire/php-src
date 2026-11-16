--TEST--
stream_set_timeout(): negative microseconds are normalized
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip not for Windows');
}
?>
--FILE--
<?php

// A negative timeout means no timeout, whether it comes from the seconds or
// from the microseconds: the read waits for the child, which writes after 0.4 s.
function read_after_delay(int $seconds, int $microseconds): array
{
    [$reader, $writer] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
    $child = proc_open([PHP_BINARY, '-n', '-r', 'usleep(400000); echo "x";'], [1 => $writer], $pipes);
    fclose($writer);

    stream_set_timeout($reader, $seconds, $microseconds);
    $start = hrtime(true);
    $data = fread($reader, 1);
    $waited = (hrtime(true) - $start) > 300_000_000;

    proc_close($child);
    return [$data, $waited];
}

foreach ([[0, -999], [0, -1000], [0, -500000], [-1, 0], [1, -1500000]] as [$seconds, $microseconds]) {
    echo "$seconds, $microseconds: ", json_encode(read_after_delay($seconds, $microseconds)), "\n";
}

// A positive total is a real timeout: 0.1 s is shorter than the 0.4 s of the child
[$reader, $writer] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
stream_set_timeout($reader, 1, -900000);
$start = hrtime(true);
var_dump(fread($reader, 1));
var_dump((hrtime(true) - $start) < 600_000_000);

?>
--EXPECT--
0, -999: ["x",true]
0, -1000: ["x",true]
0, -500000: ["x",true]
-1, 0: ["x",true]
1, -1500000: ["x",true]
bool(false)
bool(true)
