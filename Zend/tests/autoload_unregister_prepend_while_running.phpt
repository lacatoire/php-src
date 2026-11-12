--TEST--
An autoloader that unregisters itself or prepends another one does not change which autoloaders run next
--FILE--
<?php
$log = [];
$prepend = false;

spl_autoload_register($self = function ($class) use (&$self, &$log) {
    $log[] = 'a';
    spl_autoload_unregister($self);
});
spl_autoload_register(function ($class) use (&$log, &$prepend) {
    $log[] = 'b';
});
spl_autoload_register(function ($class) use (&$log, &$prepend) {
    $log[] = 'c';
    if ($prepend) {
        $prepend = false;
        spl_autoload_register(function ($class) use (&$log) {
            $log[] = 'p';
        }, true, true);
    }
});

spl_autoload_call('First');
echo implode(',', $log), "\n";

$log = [];
spl_autoload_call('Second');
echo implode(',', $log), "\n";

spl_autoload_register(function ($class) use (&$log) {
    $log[] = 'q';
}, true, true);

$log = [];
$prepend = true;
spl_autoload_call('Third');
echo implode(',', $log), "\n";
?>
--EXPECT--
a,b,c
b,c
q,b,c
