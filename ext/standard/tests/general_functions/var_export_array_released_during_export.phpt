--TEST--
var_export() frees an array whose last outside reference is dropped during the export
--FILE--
<?php
$holder = new stdClass;
$list = [str_repeat('x', 100), null];
$holder->list = &$list;
$self = new stdClass;
$self->self = $self;
$list[1] = $self;
set_error_handler(function ($errno, $errstr) use ($holder) {
    echo $errstr, "\n";
    $holder->list = null;
    gc_collect_cycles();
    return true;
});
var_export($holder, true);
unset($holder, $list, $self);
gc_collect_cycles();
echo "Done\n";
?>
--EXPECT--
var_export does not handle circular references
Done
