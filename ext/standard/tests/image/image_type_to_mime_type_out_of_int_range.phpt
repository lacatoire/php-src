--TEST--
image_type_to_mime_type(): a value outside the int range is not a known type
--FILE--
<?php
var_dump(image_type_to_mime_type(IMAGETYPE_GIF));
var_dump(image_type_to_mime_type(2**32 + IMAGETYPE_GIF));
var_dump(image_type_to_mime_type(-(2**32) + IMAGETYPE_GIF));
var_dump(image_type_to_mime_type(PHP_INT_MAX));
var_dump(image_type_to_mime_type(PHP_INT_MIN));
var_dump(image_type_to_mime_type(999));
?>
--EXPECT--
string(9) "image/gif"
string(24) "application/octet-stream"
string(24) "application/octet-stream"
string(24) "application/octet-stream"
string(24) "application/octet-stream"
string(24) "application/octet-stream"
