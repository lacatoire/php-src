--TEST--
session_regenerate_id(): session functions called from the handler callbacks are refused
--INI--
session.use_cookies=0
session.use_strict_mode=1
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

class H implements SessionHandlerInterface, SessionIdInterface, SessionUpdateTimestampHandlerInterface {
    public $armed = false;
    public function __construct(private string $cb, private string $fn) {}
    private function hit(string $name): void {
        if ($this->armed && $name === $this->cb) {
            $this->armed = false;
            var_dump(($this->fn)());
        }
    }
    public function open(string $path, string $name): bool { $this->hit('open'); return true; }
    public function close(): bool { return true; }
    public function read(string $id): string|false { return ''; }
    public function write(string $id, string $data): bool { return true; }
    public function destroy(string $id): bool { return true; }
    public function gc(int $max_lifetime): int|false { return 0; }
    public function create_sid(): string { $this->hit('create_sid'); return bin2hex(random_bytes(8)); }
    public function validateId(string $id): bool { return false; }
    public function updateTimestamp(string $id, string $data): bool { return true; }
}

foreach ([['open', 'session_regenerate_id'], ['create_sid', 'session_write_close'],
          ['create_sid', 'session_destroy'], ['create_sid', 'session_regenerate_id']] as [$cb, $fn]) {
    echo "$cb $fn\n";
    $h = new H($cb, $fn);
    session_set_save_handler($h, false);
    session_start();
    $h->armed = true;
    var_dump(session_regenerate_id());
    var_dump(session_status() === PHP_SESSION_ACTIVE);
    session_write_close();
}

echo "end\n";
?>
--EXPECTF--
open session_regenerate_id

Warning: session_regenerate_id(): Session cannot be modified while its ID is being regenerated in %s on line %d
bool(false)
bool(true)
bool(true)
create_sid session_write_close

Warning: session_write_close(): Session cannot be modified while its ID is being regenerated in %s on line %d
bool(false)
bool(true)
bool(true)
create_sid session_destroy

Warning: session_destroy(): Session cannot be modified while its ID is being regenerated in %s on line %d
bool(false)
bool(true)
bool(true)
create_sid session_regenerate_id

Warning: session_regenerate_id(): Session cannot be modified while its ID is being regenerated in %s on line %d
bool(false)
bool(true)
bool(true)
end
