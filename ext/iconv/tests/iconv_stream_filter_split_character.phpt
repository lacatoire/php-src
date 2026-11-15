--TEST--
convert.iconv stream filter with a multibyte character split over three or more buckets
--EXTENSIONS--
iconv
--FILE--
<?php
class ChunkedWrapper
{
    public $context;
    public static array $chunks = [];
    private int $index = 0;

    public function stream_open(string $path, string $mode, int $options, ?string &$openedPath): bool
    {
        return true;
    }

    public function stream_read(int $count): string
    {
        return self::$chunks[$this->index++] ?? '';
    }

    public function stream_stat(): array
    {
        return [];
    }

    public function stream_eof(): bool
    {
        return $this->index >= count(self::$chunks);
    }
}

stream_wrapper_register('chunked', 'ChunkedWrapper');

function convert(string $filter, array $chunks): void
{
    ChunkedWrapper::$chunks = array_map('hex2bin', $chunks);
    $stream = fopen('chunked://x', 'r');
    stream_filter_append($stream, $filter, STREAM_FILTER_READ);
    var_dump(bin2hex(stream_get_contents($stream)));
}

/* "A", U+20AC, "B" */
convert('convert.iconv.utf-8/utf-16le', ['41e2', '82', 'ac42']);
convert('convert.iconv.utf-8/utf-16le', ['e2', '82', 'ac']);
/* U+1F600 */
convert('convert.iconv.utf-8/utf-16le', ['f0', '9f', '98', '80']);
convert('convert.iconv.utf-16le/utf-8', ['41', '00', 'ac', '20']);
/* Two reads still work */
convert('convert.iconv.utf-8/utf-16le', ['41e2', '82ac42']);

?>
--EXPECT--
string(12) "4100ac204200"
string(4) "ac20"
string(8) "3dd800de"
string(8) "41e282ac"
string(12) "4100ac204200"
