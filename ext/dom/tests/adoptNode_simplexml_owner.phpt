--TEST--
adoptNode() refuses a subtree that a SimpleXMLElement is attached to
--EXTENSIONS--
dom
simplexml
--FILE--
<?php

echo "Legacy, DOM wrapper freed\n";
$d1 = new DOMDocument;
$d1->loadXML('<r><a><b>hello</b></a></r>');
$d2 = new DOMDocument;
$d2->loadXML('<q/>');
$a = $d1->documentElement->firstChild;
$b = $a->firstChild;
$s = simplexml_import_dom($b);
unset($b);
gc_collect_cycles();
var_dump($d2->adoptNode($a));

echo "Legacy, DOM wrapper alive\n";
$e = $d1->documentElement->firstChild;
$s2 = simplexml_import_dom($e);
var_dump($d2->adoptNode($e));
unset($d1, $e, $a);
gc_collect_cycles();
echo $s->getName(), "\n";
echo $s2->getName(), "\n";

echo "Modern\n";
$m1 = Dom\XMLDocument::createFromString('<r><a><b>hello</b></a></r>');
$m2 = Dom\XMLDocument::createFromString('<q/>');
$a = $m1->documentElement->firstChild;
$s3 = simplexml_import_dom($a->firstChild);
try {
    $m2->adoptNode($a);
} catch (DOMException $e) {
    echo $e->getMessage(), "\n";
}
echo $s3->getName(), "\n";

echo "Without SimpleXMLElement\n";
$d3 = new DOMDocument;
$d3->loadXML('<r><a/></r>');
$d4 = new DOMDocument;
$d4->loadXML('<q/>');
var_dump($d4->adoptNode($d3->documentElement->firstChild) instanceof DOMElement);

?>
--EXPECT--
Legacy, DOM wrapper freed
bool(false)
Legacy, DOM wrapper alive
bool(false)
b
a
Modern
Invalid State Error
b
Without SimpleXMLElement
bool(true)
