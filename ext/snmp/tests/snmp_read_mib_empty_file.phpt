--TEST--
snmp_read_mib() - the warning for an empty file does not name an unrelated error
--EXTENSIONS--
snmp
--FILE--
<?php
$file = __DIR__ . '/snmp_read_mib_empty_file.mib';
touch($file);

@file_get_contents('/nonexistent');
var_dump(snmp_read_mib($file));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/snmp_read_mib_empty_file.mib');
?>
--EXPECTF--
Warning: snmp_read_mib(): Error while reading MIB file '%s': not a valid MIB file in %s on line %d
bool(false)
