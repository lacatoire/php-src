/*
   +----------------------------------------------------------------------+
   | Copyright © The PHP Group and Contributors.                          |
   +----------------------------------------------------------------------+
   | This source file is subject to the Modified BSD License that is      |
   | bundled with this package in the file LICENSE, and is available      |
   | through the World Wide Web at <https://www.php.net/license/>.        |
   |                                                                      |
   | SPDX-License-Identifier: BSD-3-Clause                                |
   +----------------------------------------------------------------------+
   | Authors: Shane Caraveo             <shane@caraveo.com>               |
   |          Colin Viebrock            <colin@easydns.com>               |
   |          Hartmut Holzgraefe        <hholzgra@php.net>                |
   +----------------------------------------------------------------------+
 */

#include "php.h"
#include "php_calendar.h"
#include "sdncal.h"
#include "ext/date/php_date.h"
#include <time.h>

#define SECS_PER_DAY (24 * 3600)

/* {{{ Convert UNIX timestamp to Julian Day */
PHP_FUNCTION(unixtojd)
{
	time_t ts;
	zend_long tl = 0;
	bool tl_is_null = true;
	timelib_tzinfo *tzi;
	timelib_time_offset *offset;
	zend_long local_ts;

	if (zend_parse_parameters(ZEND_NUM_ARGS(), "|l!", &tl, &tl_is_null) == FAILURE) {
		RETURN_THROWS();
	}

	if (tl_is_null) {
		ts = time(NULL);
	} else if (tl >= 0) {
		ts = (time_t) tl;
	} else {
		zend_argument_value_error(1, "must be greater than or equal to 0");
		RETURN_THROWS();
	}

	/* Use the time zone of PHP (date_default_timezone_set(), date.timezone), as date() does */
	tzi = get_timezone_info();
	if (!tzi) {
		RETURN_THROWS();
	}

	offset = timelib_get_time_zone_info((timelib_sll) ts, tzi);
	local_ts = (zend_long) ts + offset->offset;
	timelib_time_offset_dtor(offset);

	/* Days since the epoch, rounded down for a local time before it */
	zend_long days = local_ts / SECS_PER_DAY;
	if (local_ts % SECS_PER_DAY < 0) {
		days--;
	}

	RETURN_LONG(days + 2440588 /* J.D. of 1.1.1970 */);
}
/* }}} */

/* {{{ Convert Julian Day to UNIX timestamp */
PHP_FUNCTION(jdtounix)
{
	zend_long uday;

	if (zend_parse_parameters(ZEND_NUM_ARGS(), "l", &uday) == FAILURE) {
		RETURN_THROWS();
	}
	if (uday < 2440588 || (uday - 2440588) > (ZEND_LONG_MAX / SECS_PER_DAY)) { /* before beginning of unix epoch or greater than representable */
		zend_argument_value_error(1, "jday must be between 2440588 and " ZEND_LONG_FMT, ZEND_LONG_MAX / SECS_PER_DAY + 2440588);
		RETURN_THROWS();
	}

	uday -= 2440588 /* J.D. of 1.1.1970 */;

	RETURN_LONG(uday * SECS_PER_DAY);
}
/* }}} */
