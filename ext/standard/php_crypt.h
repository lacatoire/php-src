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
   | Authors: Stig Bakken <ssb@php.net>                                   |
   |          Zeev Suraski <zeev@php.net>                                 |
   |          Rasmus Lerdorf <rasmus@php.net>                             |
   +----------------------------------------------------------------------+
*/

#ifndef PHP_CRYPT_H
#define PHP_CRYPT_H

PHPAPI zend_string *php_crypt(const char *password, const int pass_len, const char *salt, int salt_len, bool quiet);
PHP_MINIT_FUNCTION(crypt);
PHP_MSHUTDOWN_FUNCTION(crypt);
PHP_RINIT_FUNCTION(crypt);

/* sha512 crypt has the maximal salt length of 123 characters */
#define PHP_MAX_SALT_LEN 123

/* Maximum length of a $6$ (SHA-512) crypt() output string, including the
 * terminating NUL: "$6$" (3) + "rounds=<9 digits>$" (17) + salt (up to
 * PHP_MAX_SALT_LEN - 20, i.e. 16) + "$" (1) + the 86-character hash + NUL. */
#define PHP_MAX_SHA512_CRYPT_LEN (PHP_MAX_SALT_LEN + 1)

#endif
