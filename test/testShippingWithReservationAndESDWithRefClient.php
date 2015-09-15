<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ShippingServiceWSService.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/confirmDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/searchDeliverySlot.php');

use Antilop\ChronoApi\ChronoDeliverySlot;
use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;

$account = $argv[1];
$passwd = $argv[2];

if (empty($account) || empty($passwd)) {
	var_dump('Paramètres de connexion manquants.');
}

$time_slot = '2015-10-01 14:00:00';
$date_selected = new DateTime($time_slot, new DateTimeZone('Europe/Paris'));

$dm = '2015-10-01 16:00:00';
$date_max = new DateTime($dm, new DateTimeZone('Europe/Paris'));

$params = new confirmDeliverySlot();
$params->accountNumber = $account;
$params->password = $passwd;
$params->productCode = 20;

$customer = array(
	'civility' => 'M',
	'firstname' => 'John',
	'lastname' => 'Doe',
	'address1' => '46 rue de douai',
	'address2' => '',
	'zip_code' => 75009,
	'city' => 'Paris',
	'company' => '',
	'iso_code' => 'FR'
);

$recipient = array(
	'civility' => 'M',
	'name' => 'X',
	'name2' => '',
	'address1' => '57 boulevard des batignolles',
	'address2' => '',
	'zip_code' => 75017,
	'city' => 'Paris',
	'iso_code' => 'FR'
);

$esd = array(
	'specific_instructions' => 'aucune',
	'height' => '50',
	'width' => '50',
	'length' => '50',
	'retrieval_datetime' => $date_selected->format('Y-m-d\TH:i:s.uZ'),
	'closing_datetime' => $date_max->format('Y-m-d\TH:i:s.uZ')
);

$skybill = array(
	'evt_code' => 'DC',
	'product_code' => $params->productCode,
	'weight' => 1000,
	'service' => 1,
	'object_type' => 'MAR'
);

$ref = array(
	'recipient_ref' => '_REF_R_1',
	'shipper_ref' => '_REF_S_2',
);

$scheduled = array(
	'time_slot_end' => $date_selected->format('Y-m-d\TH:i:s.uZ'),
	'time_slot_start' => $date_max->format('Y-m-d\TH:i:s.uZ'),
	'time_slot_level' => 'N3',
);

$res = ChronoDeliverySlot::generateLabel($params, $customer, $recipient, $esd, $skybill, $ref, $scheduled);
print_r($res);

