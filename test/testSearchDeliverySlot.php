<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ShippingServiceWSService.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/searchDeliverySlot.php');

use Antilop\ChronoApi\ChronoDeliverySlot;
use Antilop\ChronoApi\Request\searchDeliverySlot;

$account = $argv[1];
$passwd = $argv[2];

if (empty($account) || empty($passwd)) {
	var_dump('Paramètres de connexion');
}

$ds = '2015-09-17 14:00:00';
$date_start = new DateTime($ds, new DateTimeZone('Europe/Paris'));

$dm = '2015-09-18 16:00:00';
$date_end = new DateTime($dm, new DateTimeZone('Europe/Paris'));

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
$params->dateBegin = $date_start->format('Y-m-d\TH:i:s.uZ');
$params->dateEnd = $date_end->format('Y-m-d\TH:i:s.uZ');
$params->productType = 'RDV';

$res = $service->searchDeliverySlot($params)->return;

echo $res->message;