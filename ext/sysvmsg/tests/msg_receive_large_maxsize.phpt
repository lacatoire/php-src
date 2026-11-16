--TEST--
msg_receive() with a very large max_message_size does not exhaust memory
--EXTENSIONS--
sysvmsg
--FILE--
<?php
$queue = msg_get_queue(ftok(__FILE__, 'q'));

var_dump(msg_send($queue, 1, "abc", false));
var_dump(msg_receive($queue, 0, $type, PHP_INT_MAX, $message, false, MSG_IPC_NOWAIT));
var_dump($type, $message);

var_dump(msg_send($queue, 2, "def", false));
var_dump(msg_receive($queue, 0, $type, 200000000, $message, false, MSG_IPC_NOWAIT));
var_dump($type, $message);

msg_remove_queue($queue);
?>
--EXPECT--
bool(true)
bool(true)
int(1)
string(3) "abc"
bool(true)
bool(true)
int(2)
string(3) "def"
