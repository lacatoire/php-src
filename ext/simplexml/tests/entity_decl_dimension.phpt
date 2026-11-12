--TEST--
SimpleXMLElement: attribute access on an entity declaration node
--EXTENSIONS--
simplexml
--FILE--
<?php
$xml = '<?xml version="1.0"?><!DOCTYPE r [<!ENTITY e "<b>1</b>text">]><r><x>&e;</x></r>';
$r = simplexml_load_string($xml);
$ref = ((array) $r->x)['e'];
$decl = ((array) $ref)['e'];

var_dump($decl['a']);
var_dump(isset($decl['a']));
$decl['a'] = 'x';
unset($decl['a']);
foreach ($decl->attributes() as $k => $v) {
    echo $k, "\n";
}
echo "done\n";
?>
--EXPECT--
NULL
bool(false)
done
