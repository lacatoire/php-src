--TEST--
Dom\import_simplexml() rejects a document already bound to the legacy DOM
--EXTENSIONS--
dom
simplexml
--FILE--
<?php

function test(string $label, DOMNode $node) {
    echo $label, ": ";
    try {
        var_dump(Dom\import_simplexml($node)::class);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

$doc = new DOMDocument;
$doc->loadXML('<r><c/></r>');
test("DOMDocument::__construct()", $doc->documentElement);

$sxe = simplexml_import_dom($doc->documentElement);
echo "Through SimpleXML: ";
try {
    Dom\import_simplexml($sxe->c);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

// The document is still usable by the legacy DOM after a reload
$doc->loadXML('<s/>');
$doc->documentElement->setAttributeNS('urn:y', 'y:z', '1');
echo $doc->saveXML();

$doc = (new DOMImplementation)->createDocument(null, 'r');
test("DOMImplementation::createDocument()", $doc->documentElement);

$doc = new DOMDocument;
$doc->loadXML('<r/>');
test("DOMNode::cloneNode()", $doc->cloneNode(true)->documentElement);

?>
--EXPECT--
DOMDocument::__construct(): TypeError: Dom\import_simplexml(): Argument #1 ($node) must not be already imported as a DOMNode
Through SimpleXML: TypeError: Dom\import_simplexml(): Argument #1 ($node) must not be already imported as a DOMNode
<?xml version="1.0"?>
<s xmlns:y="urn:y" y:z="1"/>
DOMImplementation::createDocument(): TypeError: Dom\import_simplexml(): Argument #1 ($node) must not be already imported as a DOMNode
DOMNode::cloneNode(): TypeError: Dom\import_simplexml(): Argument #1 ($node) must not be already imported as a DOMNode
