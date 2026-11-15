--TEST--
stream_bucket_new(), stream_filter_append() and stream_filter_prepend() with a stream closed while converting another argument
--FILE--
<?php
class ClosingString
{
    public $stream;

    public function __toString(): string
    {
        fclose($this->stream);
        return 'string.rot13';
    }
}

foreach (['stream_bucket_new', 'stream_filter_append', 'stream_filter_prepend'] as $function) {
    $closing = new ClosingString();
    $closing->stream = fopen('php://memory', 'w+');
    try {
        var_dump($function($closing->stream, $closing));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

?>
--EXPECT--
TypeError: stream_bucket_new(): supplied resource is not a valid stream resource
TypeError: stream_filter_append(): supplied resource is not a valid stream resource
TypeError: stream_filter_prepend(): supplied resource is not a valid stream resource
