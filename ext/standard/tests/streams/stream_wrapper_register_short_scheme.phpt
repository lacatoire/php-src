--TEST--
stream_wrapper_register() refuses schemes that are too short to be used
--FILE--
<?php

class W
{
    public $context;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }
}

var_dump(stream_wrapper_register('', 'W'));
var_dump(stream_wrapper_register('x', 'W'));
var_dump(in_array('', stream_get_wrappers()));
var_dump(in_array('x', stream_get_wrappers()));
var_dump(stream_wrapper_register('xy', 'W'));
var_dump(in_array('xy', stream_get_wrappers()));

?>
--EXPECTF--
Warning: stream_wrapper_register(): Invalid protocol scheme specified. Unable to register wrapper class W to :// in %s on line %d
bool(false)

Warning: stream_wrapper_register(): Invalid protocol scheme specified. Unable to register wrapper class W to x:// in %s on line %d
bool(false)
bool(false)
bool(false)
bool(true)
bool(true)
