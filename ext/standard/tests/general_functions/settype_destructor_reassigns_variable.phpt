--TEST--
settype() when the destructor of the old value reassigns the variable
--FILE--
<?php

class D
{
    public function __destruct()
    {
        global $g;
        $g = 'reassigned';
    }
}

foreach (['null', 'bool', 'int', 'float'] as $type) {
    echo "Object to $type\n";
    $g = new D;
    @settype($g, $type);
    var_dump($g);

    echo "Array holding an object to $type\n";
    $g = [new D];
    @settype($g, $type);
    var_dump($g);
}

echo "Object to array\n";
$g = new D;
settype($g, 'array');
var_dump($g);

?>
--EXPECT--
Object to null
string(10) "reassigned"
Array holding an object to null
string(10) "reassigned"
Object to bool
string(10) "reassigned"
Array holding an object to bool
string(10) "reassigned"
Object to int
string(10) "reassigned"
Array holding an object to int
string(10) "reassigned"
Object to float
string(10) "reassigned"
Array holding an object to float
string(10) "reassigned"
Object to array
string(10) "reassigned"
