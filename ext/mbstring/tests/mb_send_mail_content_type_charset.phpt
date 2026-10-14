--TEST--
mb_send_mail(): charset parameter of a Content-Type header is found in any position
--EXTENSIONS--
mbstring
--INI--
sendmail_path=cat
--FILE--
<?php
mb_language("uni");
mb_send_mail("a@example.org", "Sujet été", "corps é", "Content-Type: text/plain; charset=UTF-8; format=flowed");
mb_language("Japanese");
mb_send_mail("a@example.org", "日本語", "本文", "Content-Type: text/plain; format=flowed; charset=UTF-8\r\nContent-Transfer-Encoding: base64");
mb_send_mail("a@example.org", "日本語", "本文", "Content-Type: text/plain; format=flowed; charset=\"UTF-8\"; x=y\r\nContent-Transfer-Encoding: base64");
?>
--EXPECT--
To: a@example.org
Subject: Sujet =?UTF-8?B?w6l0w6k=?=
Content-Type: text/plain; charset=UTF-8; format=flowed
MIME-Version: 1.0
Content-Transfer-Encoding: BASE64

Y29ycHMgw6k=
To: a@example.org
Subject: =?UTF-8?B?5pel5pys6Kqe?=
Content-Type: text/plain; format=flowed; charset=UTF-8
Content-Transfer-Encoding: base64
MIME-Version: 1.0

5pys5paH
To: a@example.org
Subject: =?UTF-8?B?5pel5pys6Kqe?=
Content-Type: text/plain; format=flowed; charset="UTF-8"; x=y
Content-Transfer-Encoding: base64
MIME-Version: 1.0

5pys5paH
