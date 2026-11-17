--TEST--
stream_select() keeps iterating its arrays when a user wrapper cast replaces them
--ENV--
USE_ZEND_ALLOC=0
--FILE--
<?php
class Wrapper
{
    public $context;
    public static int $calls = 0;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        return true;
    }

    public function stream_read($count)
    {
        return '';
    }

    public function stream_eof()
    {
        return false;
    }

    public function stream_cast($castAs)
    {
        global $read;
        if (self::$calls++ === 0) {
            $read = [];
            for ($i = 0; $i < 200; $i++) {
                $read[] = 1;
            }
            unset($read);
            $read = null;
        }
        return false;
    }
}

stream_wrapper_register('replace', 'Wrapper');
$stream = fopen('replace://x', 'r');
$read = [$stream, $stream, $stream, $stream, $stream, $stream, $stream, $stream];
$write = null;
$except = null;
try {
    var_dump(@stream_select($read, $write, $except, 0));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
ValueError: No stream arrays were passed
