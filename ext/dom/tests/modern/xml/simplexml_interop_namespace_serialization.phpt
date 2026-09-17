--TEST--
Dom\import_simplexml() serializes namespaces created after the import
--EXTENSIONS--
dom
simplexml
--FILE--
<?php

$sxe = simplexml_load_string('<container xmlns="urn:a">foo</container>');
$element = Dom\import_simplexml($sxe);

$element->appendChild($element->ownerDocument->createElementNS('urn:b', 'x:child'));
$element->setAttributeNS('urn:c', 'y:attr', 'value');

echo $element->ownerDocument->saveXml($element), "\n";
echo $sxe->asXML();

?>
--EXPECT--
<container xmlns="urn:a" xmlns:y="urn:c" y:attr="value">foo<x:child xmlns:x="urn:b"/></container>
<?xml version="1.0"?>
<container xmlns="urn:a" xmlns:y="urn:c" y:attr="value">foo<x:child xmlns:x="urn:b"/></container>
