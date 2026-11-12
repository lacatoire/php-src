--TEST--
date_sunrise(), date_sunset(): the implicit UTC offset is the one of the requested date
--FILE--
<?php
date_default_timezone_set('America/New_York');
$summer = mktime(12, 0, 0, 7, 1, 2024); // EDT, UTC-4
$winter = mktime(12, 0, 0, 1, 1, 2024); // EST, UTC-5

foreach ([$summer, $winter] as $ts) {
    $implicit = @date_sunrise($ts, SUNFUNCS_RET_STRING, 40.7, -74.0);
    $explicit = @date_sunrise($ts, SUNFUNCS_RET_STRING, 40.7, -74.0, null, date('Z', $ts) / 3600);
    var_dump($implicit === $explicit);
    $implicit = @date_sunset($ts, SUNFUNCS_RET_STRING, 40.7, -74.0);
    $explicit = @date_sunset($ts, SUNFUNCS_RET_STRING, 40.7, -74.0, null, date('Z', $ts) / 3600);
    var_dump($implicit === $explicit);
}

// Non-finite zenith and offset
var_dump(@date_sunrise($summer, SUNFUNCS_RET_TIMESTAMP, 40, -74, NAN));
var_dump(@date_sunrise($summer, SUNFUNCS_RET_TIMESTAMP, 40, -74, 90.83, NAN));
var_dump(@date_sunset($summer, SUNFUNCS_RET_STRING, 40, -74, INF));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)
bool(false)
bool(false)
