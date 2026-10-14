--TEST--
nl_langinfo() returns a single byte for numeric monetary items
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip Not for Windows');
if (!function_exists('nl_langinfo')) die('skip nl_langinfo() not available');
if (!setlocale(LC_MONETARY, 'C.UTF-8', 'C.utf8', 'en_US.UTF-8', 'en_US.utf8')) die('skip no UTF-8 locale available');
?>
--FILE--
<?php

setlocale(LC_MONETARY, 'C.UTF-8', 'C.utf8', 'en_US.UTF-8', 'en_US.utf8');

foreach (['INT_FRAC_DIGITS', 'FRAC_DIGITS', 'P_CS_PRECEDES', 'P_SEP_BY_SPACE',
          'N_CS_PRECEDES', 'N_SEP_BY_SPACE', 'P_SIGN_POSN', 'N_SIGN_POSN'] as $name) {
    if (!defined($name)) {
        continue;
    }
    var_dump(strlen(nl_langinfo(constant($name))));
}

?>
--EXPECT--
int(1)
int(1)
int(1)
int(1)
int(1)
int(1)
int(1)
int(1)
