--TEST--
stream_is_local() follows the resource= target of a php://filter URL
--FILE--
<?php
class RemoteWrapper
{
    public $context;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        return true;
    }
}

stream_wrapper_register('remote', 'RemoteWrapper', STREAM_IS_URL);

echo "String:\n";
var_dump(stream_is_local('remote://x'));
var_dump(stream_is_local('php://filter/read=string.rot13/resource=remote://x'));
var_dump(stream_is_local('php://filter/read=string.rot13/resource=php://filter/read=string.toupper/resource=remote://x'));
var_dump(stream_is_local('php://filter/read=string.rot13/resource=' . __FILE__));
var_dump(stream_is_local('php://filter/read=string.rot13/resource=php://memory'));
var_dump(stream_is_local('php://filter/read=string.rot13'));

echo "Resource:\n";
$remote = fopen('php://filter/read=string.rot13/resource=remote://x', 'r');
var_dump(stream_is_local($remote));
$local = fopen('php://filter/read=string.rot13/resource=' . __FILE__, 'r');
var_dump(stream_is_local($local));
?>
--EXPECT--
String:
bool(false)
bool(false)
bool(false)
bool(true)
bool(true)
bool(true)
Resource:
bool(false)
bool(true)
