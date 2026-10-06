--TEST--
session_start(): an exception thrown by the user gc() aborts the session
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.gc_probability=1
session.gc_divisor=1
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

class U {
    public $v = 'default';
    public function __serialize(): array { return ['v' => $this->v]; }
    public function __unserialize(array $data): void { $this->v = $data['v']; }
}

class H implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface {
    public static $stored = 'u|O:1:"U":1:{s:1:"v";s:6:"stored";}';
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false { return self::$stored; }
    public function write(string $id, string $data): bool { echo "write\n"; self::$stored = $data; return true; }
    public function destroy(string $id): bool { return true; }
    public function gc(int $max_lifetime): int|false { throw new RuntimeException('gc failed'); }
    public function create_sid(): string { return 'sid'; }
    public function validateId(string $id): bool { return true; }
    public function updateTimestamp(string $id, string $data): bool { return true; }
}

session_set_save_handler(new H, true);
try {
    session_start();
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
var_dump(session_status() === PHP_SESSION_NONE);
session_write_close();
var_dump(H::$stored);
?>
--EXPECT--
RuntimeException: gc failed
bool(true)
string(35) "u|O:1:"U":1:{s:1:"v";s:6:"stored";}"
