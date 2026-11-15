--TEST--
Stream context setters keep the private context of a stream alive when user code closes the stream
--FILE--
<?php
class ClosesStream
{
    public $stream;

    public function __destruct()
    {
        fclose($this->stream);
    }
}

$stream = fopen('php://memory', 'r');
$value = new ClosesStream();
$value->stream = $stream;
stream_context_set_option($stream, 'wrapper', 'key', $value);
unset($value);
var_dump(stream_context_set_option($stream, 'wrapper', 'key', 'new'));
echo "done\n";

class ClosesStreamKeepingOptions
{
    public $stream;

    public function __destruct()
    {
        $GLOBALS['snapshot'] = stream_context_get_options($this->stream);
        fclose($this->stream);
    }
}

$stream = fopen('php://memory', 'r');
$value = new ClosesStreamKeepingOptions();
$value->stream = $stream;
stream_context_set_option($stream, 'wrapper', 'key', $value);
unset($value);
var_dump(stream_context_set_options($stream, ['wrapper' => ['key' => 'new', 'second' => 1]]));

spl_autoload_register(function (string $class) {
    global $stream;
    if ($class === 'LateNotifier') {
        fclose($stream);
        eval('class LateNotifier { public static function notify() {} }');
    }
});
$stream = fopen('php://memory', 'r');
var_dump(stream_context_set_params($stream, ['notification' => ['LateNotifier', 'notify']]));
echo "done\n";

?>
--EXPECT--
bool(true)
done
bool(true)
bool(true)
done
