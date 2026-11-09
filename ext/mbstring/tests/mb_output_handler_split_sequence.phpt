--TEST--
mb_output_handler() keeps a multibyte character split across two flushes
--EXTENSIONS--
mbstring
--FILE--
<?php
mb_internal_encoding("UTF-8");

function run(string $encoding, array $chunks, ?int $chunk_size = null) {
    mb_http_output($encoding);
    ob_start(fn($s) => bin2hex($s) . "\n");
    ob_start("mb_output_handler", $chunk_size ?? 0);
    foreach ($chunks as $chunk) {
        echo $chunk;
        if ($chunk_size === null) {
            ob_flush();
        }
    }
    ob_end_flush();
    ob_end_flush();
}

run("UTF-8", ["ab", "\xe3\x81", "\x82c"], 4);
run("SJIS", ["a\xe3", "\x81\x82b"]);
run("UTF-8", ["a\xe3\x81", "\x82", "b"]);
/* An incomplete sequence at the very end is still emitted (as an error) */
run("UTF-8", ["a\xe3\x81"]);
?>
--EXPECT--
6162e3818263
6182a062
61e3818262
613f
