--TEST--
setcookie() and setrawcookie() do not send the cookie when converting an option throws
--FILE--
<?php

foreach (['setcookie', 'setrawcookie'] as $function) {
    foreach (['path', 'domain', 'samesite'] as $option) {
        try {
            $function('a', 'v', [$option => new stdClass]);
        } catch (Error $e) {
            echo "$function($option): ", $e->getMessage(), "\n";
        }
    }
}

var_dump(headers_list());

?>
--EXPECT--
setcookie(path): Object of class stdClass could not be converted to string
setcookie(domain): Object of class stdClass could not be converted to string
setcookie(samesite): Object of class stdClass could not be converted to string
setrawcookie(path): Object of class stdClass could not be converted to string
setrawcookie(domain): Object of class stdClass could not be converted to string
setrawcookie(samesite): Object of class stdClass could not be converted to string
array(0) {
}
