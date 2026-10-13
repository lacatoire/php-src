--TEST--
The deprecation for mbstring pseudo-encodings is emitted on every use
--EXTENSIONS--
mbstring
--FILE--
<?php

$count = 0;
set_error_handler(function ($errno) use (&$count) {
    $count++;
    return true;
}, E_DEPRECATED);

function deprecations(callable $callback): int {
    global $count;
    $count = 0;
    $callback();
    return $count;
}

var_dump(deprecations(function () {
    for ($i = 0; $i < 3; $i++) {
        mb_strlen("abc", "HTML-ENTITIES");
    }
}));
var_dump(deprecations(function () {
    mb_strlen("abc", "html-entities");
    mb_strlen("abc", "HTML-ENTITIES");
}));
var_dump(deprecations(function () {
    mb_strlen("abc", "HTML-ENTITIES");
    mb_strlen("abc", "UTF-8");
    mb_strlen("abc", "HTML-ENTITIES");
}));
var_dump(deprecations(function () {
    for ($i = 0; $i < 3; $i++) {
        mb_convert_encoding("abc", "BASE64", "UTF-8");
    }
}));
var_dump(deprecations(function () {
    for ($i = 0; $i < 3; $i++) {
        mb_strlen("abc", "UTF-8");
    }
}));

?>
--EXPECT--
int(3)
int(2)
int(2)
int(3)
int(0)
