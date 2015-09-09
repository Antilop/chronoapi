<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ShippingServiceWSService.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/confirmDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/searchDeliverySlot.php');

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;

$account = $argv[1];
$passwd = $argv[2];

if (empty($account) || empty($passwd)) {
	var_dump('Paramètres de connexion manquants.');
}

$transaction_id = '';
$delivery_slot_code = '';
$rank = '';
$date_selected = '';
$mesh_code = '';

$service = new ChronoDeliverySlot();

$time_slot = '2015-09-15T15:00:00';
$start_hour = 10;
$end_hour = 12;

//Get transaction ID
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
$params->dateBegin = $time_slot;
$params->dateEnd = '2015-09-17T15:00:00';
$params->productType = 'RDV';

$res = $service->searchDeliverySlot($params)->return;

if ($res->errorCode == 0) {
	$transaction_id = $res->transactionID;
	$mesh_code = $res->meshCode;
	$slots = $res->slotList;
	if (is_array($slots) && count($slots) > 0) {
		foreach($slots as $slot) {
			if ($slot->deliveryDate >= $time_slot && $slot->startHour == $start_hour && $slot->endHour == $end_hour) {
				$delivery_slot_code = $slot->deliverySlotCode;
				$rank = $slot->rank;
				$date_selected = $slot->deliveryDate;
				break;
			}
		}

		$params = new confirmDeliverySlot();
		$params->accountNumber = $account;
		$params->password = $passwd;
		$params->transactionID = $transaction_id;
		$params->codeSlot = $delivery_slot_code;
		$params->rank = $rank;
		$params->dateSelected = $date_selected;
		$params->meshCode = $mesh_code;
		$params->productType = 'RDV';

		$res = $service->confirmDeliverySlot($params)->return;
		echo $res->message;
	}
} else {
	echo $res->errorMessage;
}