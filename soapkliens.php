<?php

$options = array(
    "location" => "http://localhost/zh/soapserver.php",
    "uri" => "http://localhost/zh/soapserver.php",
    'keep_alive' => false,);
    try {
        $kliens = new SoapClient(null, $options);
        echo $kliens->list();

    } catch (SoapFault $e) {
		var_dump($e);
   }
?> 