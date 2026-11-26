--TEST--
tempnam() does not leave a file behind when the fallback notice handler throws
--FILE--
<?php
set_error_handler(function ($errno, $message) {
    throw new ErrorException($message);
});

$pattern = sys_get_temp_dir() . '/tempnam_notice_exception*';
foreach (glob($pattern) as $file) {
    unlink($file);
}

try {
    tempnam('/nonexistent', 'tempnam_notice_exception');
} catch (ErrorException $e) {
    echo $e->getMessage(), "\n";
}
var_dump(count(glob($pattern)));
?>
--CLEAN--
<?php
foreach (glob(sys_get_temp_dir() . '/tempnam_notice_exception*') as $file) {
    unlink($file);
}
?>
--EXPECT--
tempnam(): file created in the system's temporary directory
int(0)
