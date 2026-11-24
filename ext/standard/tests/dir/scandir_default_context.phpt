--TEST--
scandir() uses the default stream context when $context is omitted or null
--FILE--
<?php
class ContextWrapper
{
    public $context;

    public function dir_opendir(string $path, int $options): bool
    {
        var_dump(is_resource($this->context) ? stream_context_get_options($this->context) : null);
        return true;
    }

    public function dir_readdir()
    {
        return false;
    }

    public function dir_closedir(): bool
    {
        return true;
    }
}

stream_wrapper_register('ctx', 'ContextWrapper');
stream_context_set_default(['ctx' => ['key' => 1]]);

scandir('ctx://a');
scandir('ctx://a', SCANDIR_SORT_ASCENDING, null);
scandir('ctx://a', SCANDIR_SORT_ASCENDING, stream_context_create(['ctx' => ['key' => 2]]));

/* A failed call does not affect the next ones */
var_dump(@scandir(__DIR__ . '/scandir_default_context_missing'));
var_dump(@scandir(__DIR__ . '/scandir_default_context_missing'));

?>
--EXPECT--
array(1) {
  ["ctx"]=>
  array(1) {
    ["key"]=>
    int(1)
  }
}
array(1) {
  ["ctx"]=>
  array(1) {
    ["key"]=>
    int(1)
  }
}
array(1) {
  ["ctx"]=>
  array(1) {
    ["key"]=>
    int(2)
  }
}
bool(false)
bool(false)
