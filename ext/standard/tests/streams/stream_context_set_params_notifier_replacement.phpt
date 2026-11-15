--TEST--
stream_context_set_params() when the destructor of the previous notifier sets a notifier on the same context
--FILE--
<?php
class Notifier
{
    public function __destruct()
    {
        global $context;
        stream_context_set_params($context, ['notification' => static function () {}]);
    }

    public function notify()
    {
    }
}

$context = stream_context_create([], ['notification' => [new Notifier(), 'notify']]);
var_dump(stream_context_set_params($context, ['notification' => static function () {}]));
echo "done\n";

/* An autoloader that sets a notifier while the new callable is resolved */
spl_autoload_register(function (string $class) {
    global $context;
    stream_context_set_params($context, ['notification' => static function () {}]);
});
try {
    stream_context_set_params($context, ['notification' => ['MissingClass', 'method']]);
} catch (Throwable $e) {
    echo $e::class, "\n";
}
echo "done\n";

?>
--EXPECT--
bool(true)
done
TypeError
done
