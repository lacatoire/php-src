--TEST--
Include path resolution survives a user wrapper url_stat() changing include_path
--FILE--
<?php

class W
{
    public $context;

    function url_stat($path, $flags)
    {
        ini_set('include_path', '/nonexistent');
        return false;
    }
}

stream_wrapper_register('ww', 'W');
set_include_path('ww://a' . PATH_SEPARATOR . dirname(__DIR__) . PATH_SEPARATOR . '/' . str_repeat('u', 40));
$file = 'streams/' . basename(__FILE__);
var_dump(stream_resolve_include_path($file) === realpath(__FILE__));

?>
--EXPECT--
bool(true)
