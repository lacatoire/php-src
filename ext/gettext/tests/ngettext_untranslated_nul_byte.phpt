--TEST--
ngettext(), dngettext() and dcngettext() return untranslated messages containing NUL bytes whole
--EXTENSIONS--
gettext
--FILE--
<?php
$id = "a\0b";
var_dump(strlen(gettext($id)));
var_dump(ngettext($id, "c\0de", 1) === $id);
var_dump(ngettext("x", "c\0de", 2) === "c\0de");
var_dump(dngettext("d", $id, "y", 1) === $id);
var_dump(dngettext("d", "y", "c\0de", 2) === "c\0de");
var_dump(dcngettext("d", $id, "y", 1, LC_MESSAGES) === $id);
var_dump(dcngettext("d", "y", "c\0de", 2, LC_MESSAGES) === "c\0de");
?>
--EXPECT--
int(3)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
