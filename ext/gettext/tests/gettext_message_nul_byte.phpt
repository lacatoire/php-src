--TEST--
gettext()/dgettext()/dcgettext() reject a $message containing a null byte
--EXTENSIONS--
gettext
--FILE--
<?php
$msgid = "orchverify_hello\0INJECTED";

try {
	gettext($msgid);
} catch (Throwable $e) {
	echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
	dgettext("messages", $msgid);
} catch (Throwable $e) {
	echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
	dcgettext("messages", $msgid, LC_MESSAGES);
} catch (Throwable $e) {
	echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
ValueError: gettext(): Argument #1 ($message) must not contain any null bytes
ValueError: dgettext(): Argument #2 ($message) must not contain any null bytes
ValueError: dcgettext(): Argument #2 ($message) must not contain any null bytes
