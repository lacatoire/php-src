--TEST--
getopt(): a Stringable element in the symbol-table $argv must not corrupt memory when its __toString() reentrantly grows $argv
--INI--
variables_order=GP
register_argc_argv=On
--FILE--
<?php
class Grower implements Stringable {
    public function __toString(): string {
        global $argv;
        for ($i = 0; $i < 5000; $i++) {
            $argv[] = "extra$i";
        }
        return "grower";
    }
}

$argv = array_merge(["prog"], array_fill(0, 64, "pad"), [new Grower()]);

var_dump(getopt(""));
echo "done\n";
?>
--EXPECT--
array(0) {
}
done
