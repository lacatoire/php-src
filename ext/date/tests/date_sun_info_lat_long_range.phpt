--TEST--
date_sun_info(), date_sunrise(): latitude and longitude must be in range
--FILE--
<?php
date_default_timezone_set('UTC');
$ts = strtotime('2024-06-21');

foreach ([[91, 0], [-91, 0], [0, 181], [0, -181], [40, 400]] as [$lat, $long]) {
    try {
        date_sun_info($ts, $lat, $long);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
    var_dump(@date_sunrise($ts, SUNFUNCS_RET_STRING, $lat, $long));
}

// The bounds themselves are valid
var_dump(count(date_sun_info($ts, 90, 180)), count(date_sun_info($ts, -90, -180)));
?>
--EXPECT--
ValueError: date_sun_info(): Argument #2 ($latitude) must be between -90 and 90
bool(false)
ValueError: date_sun_info(): Argument #2 ($latitude) must be between -90 and 90
bool(false)
ValueError: date_sun_info(): Argument #3 ($longitude) must be between -180 and 180
bool(false)
ValueError: date_sun_info(): Argument #3 ($longitude) must be between -180 and 180
bool(false)
ValueError: date_sun_info(): Argument #3 ($longitude) must be between -180 and 180
bool(false)
int(9)
int(9)
