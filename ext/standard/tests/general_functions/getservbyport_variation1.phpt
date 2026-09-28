--TEST--
Test function getservbyport() by calling it more than or less than its expected arguments
--DESCRIPTION--
Test function passing invalid port number and invalid protocol name
--CREDITS--
Italian PHP TestFest 2009 Cesena 19-20-21 june
Fabio Fabbrucci (fabbrucci@grupporetina.com)
Michele Orselli (mo@ideato.it)
Simone Gentili (sensorario@gmail.com)
--SKIPIF--
<?php
if(in_array(PHP_OS_FAMILY, ['BSD', 'Darwin', 'Solaris', 'Linux'])){
    if (!file_exists("/etc/services")) die("skip reason: missing /etc/services");
}
if (getenv('SKIP_MSAN')) die('skip msan missing interceptor for getservbyport()');
?>
--FILE--
<?php
try {
    var_dump(getservbyport( -1, "tcp" ));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(getservbyport( 80, "ppp" ));
var_dump(getservbyport( 2, 2));
var_dump(getservbyport( "80", "tcp"));
?>
--EXPECTF--
ValueError: getservbyport(): Argument #1 ($port) must be between 0 and 65535
bool(false)
bool(false)
string(%d) "%s"
