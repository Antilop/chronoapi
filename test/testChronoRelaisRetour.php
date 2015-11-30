<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/ShippingServiceWSService.php');

use Antilop\ChronoApi\ChronoDeliverySlot;

$account = $argv[1];
$passwd = $argv[2];

if (empty($account) || empty($passwd)) {
	var_dump('Paramètres de connexion manquants.');
}

$now = new DateTime('now', new DateTimeZone('Europe/Paris'));

$customer = array(
	'civility' => 'M',
	'name' => 'John Doe',
	'name2' => 'societe John Doe',
	'contact_name' => 'John Doe',
	'address1' => '27 rue du départ',
	'address2' => 'App 1',
	'zip_code' => 75013,
	'city' => 'Paris',
	'iso_code' => 'FR',
	'country' => 'FRANCE',
	'phone' => '0606060606',
	'email' => 'stephanie@antilop.fr'
);

$recipient = array(
	'civility' => 'M',
	'name' => 'Entreprise X',
	'name2' => 'societe Entreprise X',
	'address1' => '30 rue de l\'arrivée',
	'address2' => 'Bâtiment A',
	'zip_code' => 75009,
	'city' => 'Paris',
	'iso_code' => 'FR',
	'country' => 'FRANCE',
	'phone' => '0606060606',
	'email' => 'stephanie@antilop.fr'
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
	'recipient_ref' => 'CHRONORELAIS' . $now->format('His'),
	'shipper_ref' => 'CHRONORELAIS' . $now->format('His'),
);

$res = ChronoDeliverySlot::shippingReturnBooking(array('account_number' => $account, 'password' => $passwd), $customer, $recipient, $skybill, $ref);
$shipping_return = $res['shipping_return'];
print_r($shipping_return);

echo "------------------- Result -----------------------\n";
echo "Code : " . $shipping_return->errorCode ."\n";
echo "Message : " . $shipping_return->errorMessage ."\n";
echo "Reservation number : " . $shipping_return->reservationNumber ."\n";
echo "Tracking number : " . $shipping_return->skybillNumber ."\n";
echo "---------------------------------------------------";
echo "\n\n";

$etiquette = ChronoDeliverySlot::getEtiquette(array('reservation_number' => $shipping_return->reservationNumber));

if (!$etiquette['result']) {
	die("Error ".$etiquette['error']."\n");
}

echo "------------------- Récupération étiquette ------------\n";
echo "Url étiquette : " . $etiquette['url_etiquette'] ."\n";
echo "-------------------------------------------------------";
echo "\n\n";