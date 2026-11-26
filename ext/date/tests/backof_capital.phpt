--TEST--
"Back of" is case-insensitive like "back of"
--FILE--
<?php
date_default_timezone_set('UTC');
foreach (['back of 7pm', 'Back of 7pm', 'BACK OF 7pm', 'front of 7pm', 'Front of 7pm'] as $string) {
    echo $string, ': ', gmdate('H:i', strtotime($string, 1)), "\n";
}
?>
--EXPECT--
back of 7pm: 19:15
Back of 7pm: 19:15
BACK OF 7pm: 19:15
front of 7pm: 18:45
Front of 7pm: 18:45
