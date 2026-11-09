--TEST--
session_start(): a destructor restarting a failed session does not run on the table being cleaned
--INI--
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

class D {
    public static $go = false;
    public function __destruct() {
        if (self::$go) {
            self::$go = false;
            var_dump(session_start());
        }
    }
}

class H implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface {
    public static $fail = false;
    public function open(string $path, string $name): bool { return !self::$fail; }
    public function close(): bool { return true; }
    public function read(string $id): string|false { return ''; }
    public function write(string $id, string $data): bool { return true; }
    public function destroy(string $id): bool { return true; }
    public function gc(int $max_lifetime): int|false { return 0; }
    public function create_sid(): string { return 'sid'; }
    public function validateId(string $id): bool { return true; }
    public function updateTimestamp(string $id, string $data): bool { return true; }
}

session_set_save_handler(new H);
session_start();
$_SESSION['o'] = new D;
$_SESSION['p'] = [1, 2, 3];
$_SESSION['q'] = new stdClass;
session_write_close();

D::$go = true;
H::$fail = true;
var_dump(session_start());
var_dump($_SESSION);
echo "end\n";
?>
--EXPECTF--
Warning: session_start(): Failed to initialize storage module: user (path: ) in %s on line %d

Warning: session_start(): Failed to initialize storage module: user (path: ) in %s on line %d
bool(false)
bool(false)
array(0) {
}
end
