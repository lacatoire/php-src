--TEST--
simplexml_load_*() and simplexml_import_dom() reject an abstract class_name
--EXTENSIONS--
simplexml
dom
--FILE--
<?php

abstract class Ab extends SimpleXMLElement {}

$d = new DOMDocument;
$d->loadXML('<a/>');
$file = __DIR__ . '/abstract_class_name.xml';
file_put_contents($file, '<a/>');

$calls = [
    fn() => simplexml_import_dom($d, 'Ab'),
    fn() => simplexml_load_string('<a/>', 'Ab'),
    fn() => simplexml_load_file($file, 'Ab'),
];
foreach ($calls as $call) {
    try {
        $call();
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/abstract_class_name.xml');
?>
--EXPECT--
ValueError: simplexml_import_dom(): Argument #2 ($class_name) must not be an abstract class
ValueError: simplexml_load_string(): Argument #2 ($class_name) must not be an abstract class
ValueError: simplexml_load_file(): Argument #2 ($class_name) must not be an abstract class
