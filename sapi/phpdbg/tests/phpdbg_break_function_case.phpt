--TEST--
phpdbg_break_function() detects duplicates regardless of case and rejects empty names
--PHPDBG--
r
q
--EXPECTF--
[Successful compilation of %s]
prompt> [Breakpoint #0 added at Foo]
[Breakpoint exists at Foo]
[Breakpoint exists at foo]
[Invalid empty function name]
[Breakpoint exists at foo]
[Script ended normally]
prompt> 
--FILE--
<?php

phpdbg_break_function("Foo");
phpdbg_break_function("Foo");
phpdbg_break_function("foo");
phpdbg_break_function("");
phpdbg_break_function("\\foo");
