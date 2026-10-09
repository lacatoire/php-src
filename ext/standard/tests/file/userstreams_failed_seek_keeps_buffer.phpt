--TEST--
A failed seek keeps the unread buffered data of a user stream
--FILE--
<?php

class Wrapper {
    public $context;
    private $data = "0123456789ABCDEFGHIJ";
    private $position = 0;

    public function stream_open($path, $mode, $options, &$opened_path) {
        return true;
    }

    public function stream_read($count) {
        $result = substr($this->data, $this->position, $count);
        $this->position += strlen($result);
        return $result;
    }

    public function stream_eof() {
        return $this->position >= strlen($this->data);
    }

    public function stream_tell() {
        return $this->position;
    }

    public function stream_seek($offset, $whence) {
        return false;
    }
}

stream_wrapper_register('failingseek', 'Wrapper');
$stream = fopen('failingseek://x', 'r');

var_dump(fread($stream, 3));
var_dump(rewind($stream));
var_dump(fread($stream, 100));
var_dump(feof($stream));

?>
--EXPECT--
string(3) "012"
bool(false)
string(17) "3456789ABCDEFGHIJ"
bool(true)
