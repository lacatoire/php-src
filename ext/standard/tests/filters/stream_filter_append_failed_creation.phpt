--TEST--
stream_filter_append()/stream_filter_prepend(): failed filter creation leaves the stream chains unchanged
--FILE--
<?php

class ThrowingFilter extends php_user_filter
{
    public static int $calls = 0;

    public function onCreate(): bool
    {
        throw new Exception('boom');
    }

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        self::$calls++;
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

class SecondCallFailsFilter extends php_user_filter
{
    public static int $created = 0;

    public function onCreate(): bool
    {
        return ++self::$created < 2;
    }

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = strtoupper($bucket->data);
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

stream_filter_register('throwing', 'ThrowingFilter');
stream_filter_register('second_fails', 'SecondCallFailsFilter');

foreach (['stream_filter_append', 'stream_filter_prepend'] as $function) {
    $stream = fopen('php://memory', 'w+');
    for ($i = 0; $i < 3; $i++) {
        try {
            $function($stream, 'throwing', STREAM_FILTER_WRITE);
        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }
    fwrite($stream, 'x');
    fclose($stream);
    var_dump(ThrowingFilter::$calls);
}

foreach (['stream_filter_append', 'stream_filter_prepend'] as $function) {
    SecondCallFailsFilter::$created = 0;
    $stream = fopen('php://memory', 'w+');
    var_dump(@$function($stream, 'second_fails', STREAM_FILTER_ALL));
    fwrite($stream, 'abc');
    rewind($stream);
    var_dump(stream_get_contents($stream));
    fclose($stream);
}

?>
--EXPECT--
boom
boom
boom
int(0)
boom
boom
boom
int(0)
bool(false)
string(3) "abc"
bool(false)
string(3) "abc"
