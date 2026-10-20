--TEST--
session_register_shutdown(), session_set_save_handler(): the functions they register can be disabled
--INI--
disable_functions=session_write_close,session_register_shutdown
output_buffering=4096
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
class H implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface {
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false { return ''; }
    public function write(string $id, string $data): bool { return true; }
    public function destroy(string $id): bool { return true; }
    public function gc(int $max_lifetime): int|false { return 0; }
    public function create_sid(): string { return 'sid'; }
    public function validateId(string $id): bool { return true; }
    public function updateTimestamp(string $id, string $data): bool { return true; }
}

var_dump(function_exists('session_write_close'));
var_dump(function_exists('session_register_shutdown'));
var_dump(session_set_save_handler(new H));
echo "set\n";
session_start();
$_SESSION['a'] = 1;
echo "end\n";
?>
--EXPECT--
bool(false)
bool(false)
bool(true)
set
end
