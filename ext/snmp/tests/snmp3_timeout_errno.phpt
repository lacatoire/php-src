--TEST--
SNMPv3 timeout is reported as SNMP::ERRNO_TIMEOUT
--EXTENSIONS--
snmp
--FILE--
<?php
$s = new SNMP(SNMP::VERSION_3, '127.0.0.1:9', 'noAuthUser', 200000, 0);
$s->setSecurity('noAuthNoPriv');
$s->exceptions_enabled = SNMP::ERRNO_TIMEOUT;
try {
    @$s->get('.1.3.6.1.2.1.1.4.0');
    echo "no exception\n";
} catch (SNMPException $e) {
    echo "SNMPException code=", $e->getCode() === SNMP::ERRNO_TIMEOUT ? 'ERRNO_TIMEOUT' : $e->getCode(), "\n";
}

$s = new SNMP(SNMP::VERSION_3, '127.0.0.1:9', 'noAuthUser', 200000, 0);
$s->setSecurity('noAuthNoPriv');
var_dump(@$s->get('.1.3.6.1.2.1.1.4.0'), $s->getErrno() === SNMP::ERRNO_TIMEOUT);
?>
--EXPECT--
SNMPException code=ERRNO_TIMEOUT
bool(false)
bool(true)
