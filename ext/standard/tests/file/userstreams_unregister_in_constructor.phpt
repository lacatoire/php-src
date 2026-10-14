--TEST--
User wrapper operations survive a constructor that unregisters the wrapper
--FILE--
<?php

class Wrapper {
    public $context;

    public function __construct() {
        @stream_wrapper_unregister('tt');
    }
}

$operations = [
    'unlink' => fn() => unlink('tt://x'),
    'rename' => fn() => rename('tt://x', 'tt://y'),
    'mkdir' => fn() => mkdir('tt://x'),
    'rmdir' => fn() => rmdir('tt://x'),
    'touch' => fn() => touch('tt://x'),
    'file_exists' => fn() => file_exists('tt://x'),
];

foreach ($operations as $name => $operation) {
    stream_wrapper_register('tt', 'Wrapper');
    var_dump($operation());
    @stream_wrapper_unregister('tt');
}

?>
--EXPECTF--
Warning: unlink(): Wrapper::unlink is not implemented! in %s on line %d
bool(false)

Warning: rename(): Wrapper::rename is not implemented! in %s on line %d
bool(false)

Warning: mkdir(): Wrapper::mkdir is not implemented! in %s on line %d
bool(false)

Warning: rmdir(): Wrapper::rmdir is not implemented! in %s on line %d
bool(false)

Warning: touch(): Wrapper::stream_metadata is not implemented! in %s on line %d
bool(false)

Warning: file_exists(): Wrapper::url_stat is not implemented! in %s on line %d
bool(false)
