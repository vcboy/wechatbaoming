<?php
phpinfo();
$ws = "http://125.64.92.82:81811/libservices.asmx?WSDL";
$client = new SoapClient($ws);
var_dump($client->__getFunctions());