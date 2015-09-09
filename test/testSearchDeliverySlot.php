<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ShippingServiceWSService.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/searchDeliverySlot.php');

use Antilop\ChronoApi\Request\searchDeliverySlot;

$account = $argv[1];
$passwd = $argv[2];

if (empty($account) || empty($passwd)) {
	var_dump('Paramètres de connexion');
}

$service = new ChronoDeliverySlot();

$params = new searchDeliverySlot();
$params->accountNumber = $account;
$params->password = $passwd;
$params->shipperAdress1 = '82 Rue test';
$params->shipperAdress2 = '';
$params->shipperZipCode = '75017';
$params->shipperCity = 'Paris';
$params->shipperCountry = 'FR';
$params->recipientCountry = 'FR';
$params->recipientZipCode = '75009';
$params->dateBegin = '2015-09-15T09:00:00';
$params->dateEnd = '2015-09-15T11:00:00';
$params->productType = 'RDV';

$res = $service->searchDeliverySlot($params)->return;

echo $res->message;