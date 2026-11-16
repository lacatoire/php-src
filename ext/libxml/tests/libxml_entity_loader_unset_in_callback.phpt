--TEST--
libxml_set_external_entity_loader(): a callback that removes itself and then fails
--EXTENSIONS--
libxml
dom
--FILE--
<?php
$xml = '<?xml version="1.0"?><!DOCTYPE r [<!ENTITY x SYSTEM "file:///nonexistent/d.txt">]><r>&x;</r>';
libxml_use_internal_errors(true);

echo "Throwing callback\n";
libxml_set_external_entity_loader(function ($public, $system, $context) {
    libxml_set_external_entity_loader(null);
    throw new Exception("boom");
});
try {
    $d = new DOMDocument;
    $d->loadXML($xml, LIBXML_NOENT);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(libxml_get_external_entity_loader());

echo "Callback returning a closed stream\n";
libxml_set_external_entity_loader(function ($public, $system, $context) {
    libxml_set_external_entity_loader(null);
    $r = fopen("php://memory", "r");
    fclose($r);
    return $r;
});
try {
    $d = new DOMDocument;
    $d->loadXML($xml, LIBXML_NOENT);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(libxml_get_external_entity_loader());
?>
--EXPECTF--
Throwing callback
Exception: boom
NULL
Callback returning a closed stream
TypeError: DOMDocument::loadXML(): The user entity loader callback "{closure:%s:%d}" has returned a resource, but it is not a stream
NULL
