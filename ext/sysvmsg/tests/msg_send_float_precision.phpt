--TEST--
msg_send() without serialization keeps the full precision of float messages
--EXTENSIONS--
sysvmsg
--FILE--
<?php
$q = msg_get_queue(ftok(__FILE__, 'f'));

foreach ([0.1234567891, 1.0 / 3, 123456789.123456789, -2.5, 1.0E+25] as $f) {
    msg_send($q, 1, $f, false);
    msg_receive($q, 1, $type, 100, $m, false);
    var_dump((float) $m === $f);
}

msg_remove_queue($q);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
