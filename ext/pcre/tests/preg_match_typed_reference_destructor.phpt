--TEST--
preg_match(): a destructor that retypes the typed reference of $matches does not corrupt the call
--FILE--
<?php
class Holder {
    public mixed $m = null;
}

class Retype {
    public function __construct(private Holder $holder, private mixed $value) {}

    public function __destruct() {
        $this->holder->m = $this->value;
    }
}

foreach ([5, null, 'text', new stdClass()] as $value) {
    $holder = new Holder();
    $holder->m = new Retype($holder, $value);
    var_dump(preg_match('/a(b)/', 'ab', $holder->m));
    var_dump($holder->m);
}

// A destructor that shares the array does not make the call modify it
$holder = new Holder();
$copy = null;
$holder->m = new class($holder, $copy) {
    public function __construct(private Holder $holder, private &$copy) {}

    public function __destruct() {
        $this->copy = $this->holder->m;
    }
};
var_dump(preg_match('/a(b)/', 'ab', $holder->m));
var_dump($copy);
?>
--EXPECT--
int(1)
array(2) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(1) "b"
}
int(1)
array(2) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(1) "b"
}
int(1)
array(2) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(1) "b"
}
int(1)
array(2) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(1) "b"
}
int(1)
array(0) {
}
