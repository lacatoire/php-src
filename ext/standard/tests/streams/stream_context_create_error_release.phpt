--TEST--
stream_context_create() releases the context when it throws
--FILE--
<?php

class Destructed
{
    public function __destruct()
    {
        echo "notifier released\n";
    }

    public function __invoke()
    {
    }
}

try {
    stream_context_create(['a' => 1]);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(count(get_resources('stream-context')));

try {
    stream_context_create(null, ['notification' => new Destructed(), 'options' => 'invalid']);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
/* The backtrace of the exception holds the notifier */
unset($e);
var_dump(count(get_resources('stream-context')));
echo "end\n";

?>
--EXPECT--
ValueError: Options should have the form ["wrappername"]["optionname"] = $value
int(0)
TypeError: Invalid stream/context parameter
notifier released
int(0)
end
