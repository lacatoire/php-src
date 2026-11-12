--TEST--
sodium_memzero() on a typed property reference
--EXTENSIONS--
sodium
--FILE--
<?php

class Key
{
    public string $value;
}

$key = new Key();
$key->value = str_repeat('k', 32);

try {
    sodium_memzero($key->value);
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}
var_dump(is_string($key->value));

class NullableKey
{
    public ?string $value = null;
}

$nullableKey = new NullableKey();
$nullableKey->value = str_repeat('k', 32);
sodium_memzero($nullableKey->value);
var_dump($nullableKey->value);

$plain = str_repeat('k', 32);
sodium_memzero($plain);
var_dump($plain);
?>
--EXPECT--
Cannot assign null to reference held by property Key::$value of type string
bool(true)
NULL
NULL
