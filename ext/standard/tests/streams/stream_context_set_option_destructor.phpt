--TEST--
stream_context_set_option() when the destructor of the replaced value changes the options
--FILE--
<?php

class AddsOptions
{
    public function __destruct()
    {
        global $context;
        for ($i = 0; $i < 200; $i++) {
            stream_context_set_option($context, 'wrapper', "name$i", $i);
        }
    }
}

$context = stream_context_create();
stream_context_set_option($context, 'wrapper', 'key', new AddsOptions());
stream_context_set_option($context, 'wrapper', 'key', 'new');
$options = stream_context_get_options($context)['wrapper'];
var_dump(count($options), $options['key']);

class SetsOptionOnSnapshot
{
    public function __destruct()
    {
        global $context;
        $snapshot = stream_context_get_options($context);
        stream_context_set_option($context, 'wrapper', 'other', 1);
        $snapshot = null;
    }
}

$context = stream_context_create();
stream_context_set_option($context, 'wrapper', 'key', new SetsOptionOnSnapshot());
stream_context_set_option($context, 'wrapper', 'key', 'new');
var_dump(stream_context_get_options($context));

class SetsSameOption
{
    public function __destruct()
    {
        global $context;
        stream_context_set_option($context, 'wrapper', 'key', 'from destructor');
    }
}

$context = stream_context_create();
stream_context_set_option($context, 'wrapper', 'key', new SetsSameOption());
stream_context_set_option($context, 'wrapper', 'key', 'new');
var_dump(stream_context_get_options($context));

class ReleasesArray
{
    public $array;

    public function __destruct()
    {
        $this->array = null;
    }
}

$value = 'v';
$inner = [];
$inner['a'] = &$value;
for ($i = 0; $i < 5; $i++) {
    $inner["p$i"] = $i;
}
$object = new ReleasesArray();
$object->array = &$inner;
stream_context_get_default(['http' => ['a' => $object]]);
unset($object);
$options = ['http' => &$inner];
stream_context_get_default($options);
var_dump(count(stream_context_get_options(stream_context_get_default())['http']));

?>
--EXPECT--
int(201)
string(3) "new"
array(1) {
  ["wrapper"]=>
  array(2) {
    ["key"]=>
    string(3) "new"
    ["other"]=>
    int(1)
  }
}
array(1) {
  ["wrapper"]=>
  array(1) {
    ["key"]=>
    string(15) "from destructor"
  }
}
int(6)
