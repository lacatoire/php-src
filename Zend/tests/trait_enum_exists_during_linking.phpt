--TEST--
trait_exists() and enum_exists() return false for a trait or enum still being linked
--FILE--
<?php
spl_autoload_register(function ($name) {
    if ($name === 'T1') {
        var_dump(trait_exists('T2', false));
        eval('trait T1 {}');
    } elseif ($name === 'I1') {
        var_dump(enum_exists('E2', false));
        eval('interface I1 {}');
    }
});
eval('trait T2 { use T1; }');
eval('enum E2 implements I1 { case A; }');
var_dump(trait_exists('T2', false), enum_exists('E2', false));
?>
--EXPECT--
bool(false)
bool(false)
bool(true)
bool(true)
