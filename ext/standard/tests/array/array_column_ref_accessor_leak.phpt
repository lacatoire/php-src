--TEST--
array_column(): does not leak a zend_reference when the column accessor returns by reference
--FILE--
<?php
class RefMagicGet {
    public function &__get($name) {
        $v = "val_$name";
        return $v;
    }
    public function __isset($name) {
        return true;
    }
}

class RefPropertyHook {
    public string $x = 'v' {
        &get {
            return $this->x;
        }
    }
}

$rows = array_column([new RefMagicGet, new RefMagicGet, new RefMagicGet], 'x');
print_r($rows);

$rows = array_column([new RefPropertyHook, new RefPropertyHook, new RefPropertyHook], 'x');
print_r($rows);

// Same accessors used as the index_key fetch (array_column_fetch_prop call site
// at line 4717), to exercise that path explicitly rather than relying on it
// sharing code with the column fetch.
$rows = array_column([new RefMagicGet, new RefMagicGet, new RefMagicGet], 'x', 'x');
print_r($rows);

$rows = array_column([new RefPropertyHook, new RefPropertyHook, new RefPropertyHook], 'x', 'x');
print_r($rows);

?>
--EXPECT--
Array
(
    [0] => val_x
    [1] => val_x
    [2] => val_x
)
Array
(
    [0] => v
    [1] => v
    [2] => v
)
Array
(
    [val_x] => val_x
)
Array
(
    [v] => v
)
