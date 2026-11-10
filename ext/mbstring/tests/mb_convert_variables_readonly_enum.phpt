--TEST--
mb_convert_variables() does not modify readonly properties or enum cases
--EXTENSIONS--
mbstring
--FILE--
<?php

final class P {
    public function __construct(
        public readonly string $name,
        public readonly array $tags,
        public string $other,
    ) {}
}

enum Suit: string {
    case Hearts = 'H';
}

$p = new P("caf\xe9", ["t\xe9"], "\xe9");
mb_convert_variables('UTF-8', 'ISO-8859-1', $p);
var_dump(bin2hex($p->name), bin2hex($p->tags[0]), bin2hex($p->other));

$c = Suit::Hearts;
mb_convert_variables('UTF-16BE', 'ASCII', $c);
var_dump(Suit::Hearts->name, Suit::Hearts->value);

?>
--EXPECTF--
Deprecated: mb_convert_variables(): Passing an object for argument #3 $vars to mb_convert_variables() is deprecated, call get_object_vars() first instead in %s on line %d
string(8) "636166e9"
string(4) "74e9"
string(4) "c3a9"

Deprecated: mb_convert_variables(): Passing an object for argument #3 $vars to mb_convert_variables() is deprecated, call get_object_vars() first instead in %s on line %d
string(6) "Hearts"
string(1) "H"
