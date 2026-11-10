--TEST--
Filesystem functions do not perform their operation when the context argument is rejected
--FILE--
<?php

$dir = __DIR__ . '/invalid_context_no_side_effect';
mkdir($dir);
chdir($dir);
mkdir('directory');
file_put_contents('unlinked.txt', 'unlinked');
file_put_contents('renamed.txt', 'original');
file_put_contents('opened.txt', 'original');

$notAContext = fopen('php://memory', 'r');

$calls = [
    'rmdir' => fn() => rmdir('directory', $notAContext),
    'mkdir' => fn() => mkdir('created', 0777, false, $notAContext),
    'unlink' => fn() => unlink('unlinked.txt', $notAContext),
    'rename' => fn() => rename('renamed.txt', 'new.txt', $notAContext),
    'copy' => fn() => copy('opened.txt', 'copy.txt', $notAContext),
    'file_put_contents' => fn() => file_put_contents('put.txt', 'x', 0, $notAContext),
    'fopen' => fn() => fopen('opened.txt', 'w', false, $notAContext),
    'file_get_contents' => fn() => file_get_contents('opened.txt', false, $notAContext),
    'file' => fn() => file('opened.txt', 0, $notAContext),
    'readfile' => fn() => readfile('opened.txt', false, $notAContext),
];

foreach ($calls as $name => $call) {
    try {
        $call();
        echo "$name: no exception\n";
    } catch (TypeError $e) {
        echo "$name: ", $e->getMessage(), "\n";
    }
}

var_dump(is_dir('directory'), file_exists('created'), file_exists('unlinked.txt'));
var_dump(file_exists('renamed.txt'), file_exists('new.txt'), file_exists('copy.txt'), file_exists('put.txt'));
var_dump(file_get_contents('opened.txt'));

?>
--CLEAN--
<?php
$dir = __DIR__ . '/invalid_context_no_side_effect';
foreach (glob("$dir/*") as $path) {
    is_dir($path) ? rmdir($path) : unlink($path);
}
rmdir($dir);
?>
--EXPECT--
rmdir: rmdir(): supplied resource is not a valid Stream-Context resource
mkdir: mkdir(): supplied resource is not a valid Stream-Context resource
unlink: unlink(): supplied resource is not a valid Stream-Context resource
rename: rename(): supplied resource is not a valid Stream-Context resource
copy: copy(): supplied resource is not a valid Stream-Context resource
file_put_contents: file_put_contents(): supplied resource is not a valid Stream-Context resource
fopen: fopen(): supplied resource is not a valid Stream-Context resource
file_get_contents: file_get_contents(): supplied resource is not a valid Stream-Context resource
file: file(): supplied resource is not a valid Stream-Context resource
readfile: readfile(): supplied resource is not a valid Stream-Context resource
bool(true)
bool(false)
bool(true)
bool(true)
bool(false)
bool(false)
bool(false)
string(8) "original"
