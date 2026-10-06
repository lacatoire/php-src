--TEST--
session_reset(): returns false when the stored data cannot be decoded
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

class H implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface {
    public $broken = false;
    public function open(string $path, string $name): bool { return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false { return $this->broken ? 'broken' : 'a|i:1;'; }
    public function write(string $id, string $data): bool { return true; }
    public function destroy(string $id): bool { return true; }
    public function gc(int $max_lifetime): int|false { return 0; }
    public function create_sid(): string { return 'sid'; }
    public function validateId(string $id): bool { return true; }
    public function updateTimestamp(string $id, string $data): bool { return true; }
}

$h = new H;
session_set_save_handler($h, false);
session_start();
$_SESSION['a'] = 2;
$h->broken = true;
var_dump(session_reset());
var_dump(session_status() === PHP_SESSION_NONE);
var_dump($_SESSION);
?>
--EXPECTF--
Warning: session_reset(): Failed to decode session object. Session has been destroyed in %s on line %d
bool(false)
bool(true)
array(0) {
}
