--TEST--
Random: Randomizer::shuffleArray() with a throwing engine and a packed array with holes
--FILE--
<?php

class ThrowingEngine implements Random\Engine
{
    public function generate(): string
    {
        throw new Exception('boom');
    }
}

class Tracked
{
    public static array $destroyed = [];

    public function __construct(public int $tag) {}

    public function __destruct()
    {
        self::$destroyed[] = $this->tag;
    }
}

$array = [];
for ($i = 0; $i < 6; $i++) {
    $array[] = new Tracked($i);
}
unset($array[0]);

$randomizer = new Random\Randomizer(new ThrowingEngine());
try {
    $randomizer->shuffleArray($array);
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

echo json_encode(Tracked::$destroyed), "\n";
foreach ($array as $key => $value) {
    echo "$key => {$value->tag}\n";
}

?>
--EXPECT--
boom
[0]
1 => 1
2 => 2
3 => 3
4 => 4
5 => 5
