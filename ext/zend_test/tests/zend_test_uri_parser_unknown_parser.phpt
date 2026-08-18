--TEST--
zend_test_uri_parser(): an unknown parser is reported as argument #2
--EXTENSIONS--
zend_test
--FILE--
<?php

try {
    zend_test_uri_parser("https://example.com", "nosuchparser");
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}

?>
--EXPECT--
zend_test_uri_parser(): Argument #2 ($parser) Unknown parser
