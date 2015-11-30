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

$now = new DateTime('now', new DateTimeZone('Europe/Paris'));

$date = '2015-11-30';
$date_start = new DateTime($date, new DateTimeZone('Europe/Paris'));
$start_hour = 14;
$end_hour = 16;

$service = new ChronoDeliverySlot();

$search = new searchDeliverySlot();
$search->accountNumber = $account;
$search->password = $passwd;
$search->shipperAdress1 = '82 Rue test';
$search->shipperAdress2 = 'complement addr';
$search->shipperZipCode = '75017';
$search->shipperCity = 'Paris';
$search->shipperCountry = 'FR';
$search->recipientCountry = 'FR';
$search->recipientZipCode = '75009';
$search->dateBegin = $now->format('Y-m-d\TH:i:s.uZ');
$search->productType = 'RDV';

$res_search = $service->searchDeliverySlot($search)->return;

if ($res_search->code != 0) {
	die("Error code ".$res_search->message."\n");
}

echo "\n";
echo "----------------1 : Recherche créneaux-----------------\n";
echo "Recherche créneau à partir du : " . $now->format('Y-m-d') ."\n";
echo "Date de livraison souhaité : " . $date_start->format('Y-m-d') ."\n";
echo $res_search->message . "\n";
echo "-------------------------------------------------------";
echo "\n\n";

$transaction_id = $res_search->transactionID;
$mesh_code = $res_search->meshCode;
$slots = $res_search->slotList;

if (!is_array($slots) && count($slots) === 0) {
	die("Pas de  créneaux disponible.\n");
}

$date_selected = '';
$slot_start = '';
$slot_end = '';
$rank = '';
$tariff = '';

$find_slot = false;
foreach($slots as $slot) {
	$delivery_date = new DateTime($slot->deliveryDate, new DateTimeZone('Europe/Paris'));
	if ($delivery_date->format('Y-m-d') == $date_start->format('Y-m-d') && $slot->startHour == $start_hour && $slot->endHour == $end_hour) {
		$find_slot = true;
		$delivery_slot_code = $slot->deliverySlotCode;
		$rank = $slot->rank;
		$date_selected = $slot->deliveryDate;
		$slot_start = $slot->startHour;
		$slot_end = $slot->endHour;
		$tariff = $slot->tariffLevel;
		break;
	}
}

if (!$find_slot) {
	die("Le créneau choisi n'est pas disponible.\n");
}

$confirm = new confirmDeliverySlot();
$confirm->accountNumber = $account;
$confirm->password = $passwd;
$confirm->transactionID = $transaction_id;
$confirm->codeSlot = $delivery_slot_code;
$confirm->rank = $rank;
$confirm->dateSelected = $date_selected;
$confirm->meshCode = $mesh_code;
$confirm->productType = 'RDV';
$res_confirm = $service->confirmDeliverySlot($confirm)->return;

echo "\n";
echo "----------------2 : Confirmation du créneau-----------------\n";
echo "Date sélectionné : " . $date_selected ."\n";
echo "Créneau start : " . $slot_start ."\n";
echo "Créneau end : " . $slot_end ."\n";
echo "ID transaction : " . $transaction_id ."\n";
echo $res_confirm->message . "\n";
echo "-------------------------------------------------------------";
echo "\n\n";

$customer = array(
	'civility' => 'M',
	'name' => 'John Doe',
	'name2' => 'societe JD',
	'contact_name' => 'John Doe',
	'address1' => '27 rue du départ',
	'address2' => 'complement addr',
	'zip_code' => 75013,
	'city' => 'Paris',
	'iso_code' => 'FR',
	'country' => 'FRANCE',
	'phone' => '0606060606',
	'email' => 'stephanie@antilop.fr'
);

$recipient = array(
	'civility' => 'M',
	'name' => 'Entreprise',
	'name2' => 'X',
	'address1' => '30 rue de l\'arrivée',
	'address2' => 'Bâtiment A',
	'zip_code' => 75009,
	'city' => 'Paris',
	'iso_code' => 'FR',
	'country' => 'FRANCE',
	'phone' => '0606060606',
	'email' => 'stephanie@antilop.fr'
);

$time_slot_start = new DateTime($date . ' ' . $slot_start . ':00:00', new DateTimeZone('Europe/Paris'));
$time_slot_end = new DateTime($date . ' ' . $slot_end . ':00:00', new DateTimeZone('Europe/Paris'));
$esd = array(
	'specific_instructions' => 'aucune',
	'height' => '50',
	'width' => '50',
	'length' => '50',
	'retrieval_datetime' => $time_slot_start->format('Y-m-d\TH:i:s.uZ'),
	'closing_datetime' => $time_slot_end->format('Y-m-d\TH:i:s.uZ'),
	'ref_esd' => 'ESD' . $now->format('His'),
	'lt_print_by_chrono' => 1,
	'nb_passage' => 1
);

$product_code = '16';
$service_code = '0';
$skybill = array(
	'evt_code' => 'DC',
	'product_code' => $product_code,
	'weight' => 1000 / 1000,
	'service' => $service_code,
	'object_type' => 'MAR'
);

$ref = array(
	'recipient_ref' => 'ESD' . $now->format('His'),
	'shipper_ref' => 'ESD' . $now->format('His'),
);

$res = ChronoDeliverySlot::esdBooking($confirm, $customer, $recipient, $esd, $skybill, $ref, 'THE');
$esd = $res['esd'];

if (!$esd) {
	die($res['message'] . "\n");
}

echo "----------------3 : ESD -----------------\n";
echo "Code : " . $esd->errorCode ."\n";
echo "Message : " . $esd->errorMessage ."\n";
echo "Pickup date : " . $esd->pickupDate ."\n";
echo "Esd number : " . $esd->ESDNumber ."\n";
echo "Esd full number : " . $esd->ESDFullNumber ."\n";
echo "Tracking number : " . $esd->skybillNumber ."\n";
echo "---------------------------------------------------";
echo "\n\n";

$etiquette = ChronoDeliverySlot::getEtiquette(array('reservation_number' => $esd->reservationNumber));
if (!$etiquette['result']) {
	die("Error ".$etiquette['error']."\n");
}

echo "----------------4 : Récupération étiquette ------------\n";
echo "Url étiquette : " . $etiquette['url_etiquette'] ."\n";
echo "-------------------------------------------------------";
echo "\n\n";