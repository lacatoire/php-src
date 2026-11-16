--TEST--
stripos() and stristr() agree with a lower-cased strpos() on mixed-case haystacks
--FILE--
<?php

mt_srand(12345);
$alphabet = 'aAbBxX';
$mismatches = 0;

for ($i = 0; $i < 3000; $i++) {
    $haystack = '';
    $length = mt_rand(0, 40);
    for ($j = 0; $j < $length; $j++) {
        $haystack .= $alphabet[mt_rand(0, 5)];
    }
    $needle = '';
    $needleLength = mt_rand(1, 4);
    for ($j = 0; $j < $needleLength; $j++) {
        $needle .= $alphabet[mt_rand(0, 5)];
    }
    $lowerHaystack = strtolower($haystack);
    $lowerNeedle = strtolower($needle);

    for ($offset = 0; $offset <= $length; $offset++) {
        if (stripos($haystack, $needle, $offset) !== strpos($lowerHaystack, $lowerNeedle, $offset)) {
            $mismatches++;
        }
    }

    $position = strpos($lowerHaystack, $lowerNeedle);
    $expected = $position === false ? false : substr($haystack, $position);
    if (stristr($haystack, $needle) !== $expected) {
        $mismatches++;
    }
}

var_dump($mismatches);

?>
--EXPECT--
int(0)
