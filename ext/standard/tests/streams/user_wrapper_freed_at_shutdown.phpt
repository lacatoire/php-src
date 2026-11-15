--TEST--
A user wrapper registered after a stream was opened is not used by the close handler of that stream at shutdown
--FILE--
<?php
class FirstWrapper
{
    public $context;

    public function stream_open(string $path, string $mode, int $options, ?string &$openedPath): bool
    {
        return true;
    }

    public function stream_close(): void
    {
        echo "FirstWrapper::stream_close\n";
        var_dump(@fopen('second://y', 'r'));
    }
}

class SecondWrapper
{
    public $context;

    public function stream_open(string $path, string $mode, int $options, ?string &$openedPath): bool
    {
        echo "SecondWrapper::stream_open\n";
        return true;
    }
}

stream_wrapper_register('first', 'FirstWrapper');
$stream = fopen('first://x', 'r');
stream_wrapper_register('second', 'SecondWrapper');
echo "end\n";

?>
--EXPECT--
end
FirstWrapper::stream_close
bool(false)
