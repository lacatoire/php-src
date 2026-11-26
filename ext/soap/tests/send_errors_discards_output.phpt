--TEST--
SoapServer with send_errors=false discards the output of a failing service method
--EXTENSIONS--
soap
--FILE--
<?php
class Svc {
    function hello() {
        echo "SERVICE-OUTPUT";
        trigger_error("boom", E_USER_ERROR);
    }
}
$server = new SoapServer(null, ['uri' => 'urn:t', 'send_errors' => false]);
$server->setClass('Svc');
$server->handle('<?xml version="1.0"?><SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:t"><SOAP-ENV:Body><ns1:hello/></SOAP-ENV:Body></SOAP-ENV:Envelope>');
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Body><SOAP-ENV:Fault><faultcode>SOAP-ENV:Server</faultcode><faultstring>Internal Error</faultstring></SOAP-ENV:Fault></SOAP-ENV:Body></SOAP-ENV:Envelope>
