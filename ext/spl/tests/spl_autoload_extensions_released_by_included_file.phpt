--TEST--
spl_autoload() keeps the extensions list alive while an included file replaces it
--FILE--
<?php
$dir = __DIR__ . '/spl_autoload_extensions_released_by_included_file';
mkdir($dir);
set_include_path($dir);

file_put_contents($dir . '/uafcls.aaa', '<?php
spl_autoload_extensions(".zzz");
$first = ".aaa";
$second = ",.ddd";
$GLOBALS["keep"] = $first . $second;
');
file_put_contents($dir . '/uafcls.bbb', '<?php echo "LOADED bbb\n"; class UafCls {}');
file_put_contents($dir . '/uafcls.ddd', '<?php echo "LOADED ddd\n"; class UafCls {}');

/* Built at run time, so that the string is not interned and is held only by the global list. */
$first = '.aaa';
$second = ',.bbb';
$extensions = $first . $second;
spl_autoload_extensions($extensions);
unset($extensions, $first, $second);

spl_autoload('UafCls');
var_dump(class_exists('UafCls', false));
?>
--CLEAN--
<?php
$dir = __DIR__ . '/spl_autoload_extensions_released_by_included_file';
foreach (['uafcls.aaa', 'uafcls.bbb', 'uafcls.ddd'] as $file) {
    @unlink($dir . '/' . $file);
}
@rmdir($dir);
?>
--EXPECT--
LOADED bbb
bool(true)
