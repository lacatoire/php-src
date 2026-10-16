--TEST--
mail(): $to and $subject are sanitised without looking at the locale
--INI--
sendmail_path="tee mail_locale_independent.out >/dev/null"
mail.add_x_header=0
--SKIPIF--
<?php
if (substr(PHP_OS, 0, 3) == "WIN") die("skip Won't run on Windows");
if (!setlocale(LC_CTYPE, 'de_DE.ISO-8859-1', 'de_DE.ISO8859-1', 'fr_FR.ISO-8859-15', 'fr_FR.ISO8859-15')) {
    die("skip no single-byte locale available");
}
?>
--FILE--
<?php
setlocale(LC_CTYPE, 'de_DE.ISO-8859-1', 'de_DE.ISO8859-1', 'fr_FR.ISO-8859-15', 'fr_FR.ISO8859-15');

// 0x82 is a control character in ISO-8859-x but a byte of the euro sign in UTF-8
$subject = "5 \xe2\x82\xac \xc3\xa9t\xc3\xa9\r\n x\x01y\x7fz  ";
$to = "\xc3\xa9\xe2\x82\xac@example.org\t";

var_dump(mail($to, $subject, "body"));
preg_match_all('/^(?:To|Subject):[^\n]*(?:\n[ \t][^\n]*)*/m', file_get_contents('mail_locale_independent.out'), $m);
foreach ($m[0] as $header) {
    echo addcslashes($header, "\0..\37\177..\377"), "\n";
}
?>
--CLEAN--
<?php
@unlink('mail_locale_independent.out');
?>
--EXPECT--
bool(true)
To: \303\251\342\202\254@example.org\r
Subject: 5 \342\202\254 \303\251t\303\251\r\n x y z\r