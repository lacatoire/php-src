--TEST--
socket_create_pair(): the descriptors are closed when $pair is a typed reference that rejects an array
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (!function_exists('socket_create_pair')) {
    die('skip socket_create_pair() is not available');
}
if (!is_dir('/proc/self/fd')) {
    die('skip /proc/self/fd is not available');
}
?>
--FILE--
<?php
class Holder {
    public int $count = 0;
}

function open_descriptors(): int {
    return count(scandir('/proc/self/fd')) - 2;
}

$holder = new Holder();
$before = open_descriptors();
for ($i = 0; $i < 5; $i++) {
    try {
        socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $holder->count);
    } catch (TypeError $e) {
        if ($i === 0) {
            echo $e->getMessage(), "\n";
        }
    }
}
var_dump(open_descriptors() - $before);

// A reference that accepts an array still gets the pair
$accepting = new class {
    public ?array $pair = null;
};
var_dump(socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $accepting->pair));
var_dump(count($accepting->pair));
?>
--EXPECT--
Cannot assign array to reference held by property Holder::$count of type int
int(0)
bool(true)
int(2)
