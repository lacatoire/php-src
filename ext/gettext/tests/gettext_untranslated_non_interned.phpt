--TEST--
gettext(), dgettext() and dcgettext() return a shared string when no translation is found
--EXTENSIONS--
gettext
--FILE--
<?php

function test(string $part) {
    // Built at runtime, so the message is not interned
    $message = $part . $part;

    var_dump(gettext($message));
    var_dump(dgettext("php-test-no-such-domain", $message));
    var_dump(dcgettext("php-test-no-such-domain", $message, LC_MESSAGES));
}

test("untranslated");

?>
--EXPECT--
string(24) "untranslateduntranslated"
string(24) "untranslateduntranslated"
string(24) "untranslateduntranslated"
