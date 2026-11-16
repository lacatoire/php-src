--TEST--
popen(): invalid modes are rejected with a ValueError
--FILE--
<?php

foreach (['bw', 'br', 'brb', 'rbb', 'wbb', '', 'b', 'bb', 'rw', 'r+', 'x'] as $mode) {
    try {
        popen('echo hi', $mode);
        echo "$mode: no error\n";
    } catch (ValueError $e) {
        echo "'$mode': ", $e->getMessage(), "\n";
    }
}

?>
--EXPECT--
'bw': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'br': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'brb': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'rbb': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'wbb': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'b': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'bb': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'rw': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'r+': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
'x': popen(): Argument #2 ($mode) must be one of "r", "rb", "w", or "wb"
