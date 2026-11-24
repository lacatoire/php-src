--TEST--
User wrappers named like a prefix of "file" are not handled as file://
--FILE--
<?php

class Wrapper
{
    public $context;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        echo "[$path]\n";
        return false;
    }
}

foreach (['fi', 'fil'] as $name) {
    stream_wrapper_register($name, 'Wrapper');
    @fopen("$name:///a", 'r');
    @fopen("$name://host/a", 'r');
}

?>
--EXPECT--
[fi:///a]
[fi://host/a]
[fil:///a]
[fil://host/a]
