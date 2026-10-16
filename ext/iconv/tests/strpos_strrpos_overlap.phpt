--TEST--
iconv_strpos(), iconv_strrpos(): overlapping occurrences agree with strpos()/strrpos()
--EXTENSIONS--
iconv
--FILE--
<?php
var_dump(iconv_strrpos("aaa", "aa", "UTF-8"), iconv_strrpos("xaaay", "aa", "UTF-8"), iconv_strrpos("aaaaaaaé", "aaa", "UTF-8"));
var_dump(iconv_strpos("bbaababbabab", "baba", 0, "UTF-8"), iconv_strpos("bbaababbabab", "baba", 8, "UTF-8"));

mt_srand(7);
$bad = 0;
for ($t = 0; $t < 3000; $t++) {
    $h = '';
    for ($i = mt_rand(1, 12); $i > 0; $i--) {
        $h .= "ab"[mt_rand(0, 1)];
    }
    $n = '';
    for ($i = mt_rand(1, 4); $i > 0; $i--) {
        $n .= "ab"[mt_rand(0, 1)];
    }
    $o = mt_rand(0, strlen($h));
    if (strrpos($h, $n) !== iconv_strrpos($h, $n, "UTF-8") || strpos($h, $n, $o) !== iconv_strpos($h, $n, $o, "UTF-8")) {
        $bad++;
    }
}
var_dump($bad);
?>
--EXPECT--
int(1)
int(2)
int(4)
int(7)
bool(false)
int(0)
