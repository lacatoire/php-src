--TEST--
soundex() does not depend on the LC_CTYPE locale
--SKIPIF--
<?php
if (setlocale(LC_CTYPE, 'tr_TR.UTF-8', 'tr_TR.utf8', 'tr_TR') === false) {
    die('skip tr_TR locale not available');
}
?>
--FILE--
<?php
setlocale(LC_CTYPE, 'tr_TR.UTF-8', 'tr_TR.utf8', 'tr_TR');
echo soundex('istanbul'), ' ', soundex('Istanbul'), ' ', soundex('Mimi'), "\n";
?>
--EXPECT--
I235 I235 M500
