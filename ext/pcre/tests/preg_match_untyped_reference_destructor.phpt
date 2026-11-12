--TEST--
preg_match(): a value stored by the destructor of the previous $matches is released
--FILE--
<?php
class Probe {
}

class Store {
    public function __destruct() {
        global $matches, $probe_ref;
        $probe = new Probe();
        $probe_ref = WeakReference::create($probe);
        $matches = $probe;
    }
}

$matches = new Store();
var_dump(preg_match('/a(b)/', 'ab', $matches));
var_dump($matches);
var_dump($probe_ref->get());
?>
--EXPECT--
int(1)
array(2) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(1) "b"
}
NULL
