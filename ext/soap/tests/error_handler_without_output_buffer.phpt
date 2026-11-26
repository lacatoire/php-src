--TEST--
SOAP error handler does not try to discard a missing output buffer
--EXTENSIONS--
soap
--INI--
output_buffering=0
--FILE--
<?php
set_error_handler(function ($errno, $errstr) {
    echo $errstr, "\n";
    return true;
}, E_NOTICE);
new SoapServer('/nonexistent.wsdl');
?>
--EXPECTF--
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Body><SOAP-ENV:Fault><faultcode>WSDL</faultcode><faultstring>SOAP-ERROR: Parsing WSDL: Couldn't load from '/nonexistent.wsdl' : failed to load %s
</faultstring></SOAP-ENV:Fault></SOAP-ENV:Body></SOAP-ENV:Envelope>
