<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ShippingServiceWSService.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/searchDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/confirmDeliverySlot.php');

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;

$account = $argv[1];
$passwd = $argv[2];

$service = new ChronoDeliverySlot();

$params = new searchDeliverySlot();
$params->accountNumber = $account;
$params->password = $passwd;
$params->shipperAdress1 = '82 Rue test';
$params->shipperAdress2 = '';
$params->shipperZipCode = '75017';
$params->shipperCity = 'Paris';
$params->shipperCountry = 'France';
$params->recipientZipCode = '75009';
//$params->dateBegin = '2015-09-15 10:00:00';
//$params->dateEnd = '2015-09-15 12:00:00';

$res = $service->searchDeliverySlot($params)->return;

echo "REQUEST:\n" . print_r($service->__getLastRequest()) . "\n";
echo '<pre>'; print_r($res); echo '</pre>';
