<?php

require_once(dirname(__FILE__) . '/../../../../../../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../../../../../../init.php');
require_once(dirname(__FILE__) . '/../ChronoDeliverySlot.php');
require_once(dirname(__FILE__) . '/../ShippingServiceWSService.php');
require_once(dirname(__FILE__) . '/../Request/searchDeliverySlot.php');
require_once(dirname(__FILE__) . '/../Request/confirmDeliverySlot.php');

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;

$account = Configuration::get('CHRONOAPI_ACCOUNT');
$passwd = Configuration::get('CHRONOAPI_PASSWD');

if (empty($account) || empty($passwd)) {
	die('Paramètres de connexion manquant');
}

$sql = 'SELECT * FROM `' . _DB_PREFIX_ .'order_carrier` '
	. 'WHERE `id_carrier` = 23 '
	. 'AND `delivery_status` = ""'
	. 'AND `is_return` = 1';

$order_carrier = Db::getInstance()->getRow($sql);
$time_slot_wished = json_decode($order_carrier['details']);
if ($order_carrier['id_order'] > 0 && isset($time_slot_wished)) {
	$order = new Order((int)$order_carrier['id_order']);

	$time_slot_start = date('c', strtotime($time_slot_wished->delivery_date . ' ' . $time_slot_wished->time_slot->delivery_hour_first . ':00'));
	$time_slot_end = date('c', strtotime($time_slot_wished->delivery_date . ' ' . $time_slot_wished->time_slot->delivery_hour_second . ':00'));

	if (Validate::isLoadedObject($order)) {
		$customer = new Customer($order->id_customer);

		if (Validate::isLoadedObject($customer)) {
			$address = new Address($order->id_address_return);
			/********************************************************************
			* Rechercher un créneau de livraison
			*********************************************************************/
			$service = new ChronoDeliverySlot();

			echo 'Fonctions du service : <br/>';
			echo '<pre>'; var_dump($service->__getFunctions()); echo '</pre>';

			$params = new searchDeliverySlot();
			$params->accountNumber = (int)$account;
			$params->password = $passwd;
			$params->shipperAdress1 = $address->address1;
			$params->shipperAdress2 = $address->address2;
			$params->shipperZipCode = $address->postcode;
			$params->shipperCity = $address->city;
			$params->shipperCountry = $address->country;
			$params->recipientZipCode = '75009';
			$params->dateBegin = $time_slot_start;
			$params->dateEnd = $time_slot_end;
			$res = $service->searchDeliverySlot($params)->return;

			echo "REQUEST:\n" . print_r(htmlentities($service->__getLastRequest())) . "\n";
			echo '<pre>'; print_r($res); echo '</pre>';
			
			$slots = '';
			if (isset($res->errorCode)) {
				if ($res->errorCode == 0) {
					$slots = $res->slotList;
				} else {
					echo $res->errorMessage;
				}
			}

			$transaction_id = '';
			$delivery_slot_code = '';
			$rank = '';
			if (is_array($slots) && count($slots) > 0) {
				foreach($slots as $slot) {
					if ($slot['deliveryDate'] == $time_slot_wished->delivery_date) {
						$transaction_id = $slot['transactionID'];
						$delivery_slot_code = $slot['deliverySlotCode'];
						$rank = $slot['rank'];
					}
				}
			}

			if (isset($transaction_id) && isset($delivery_slot_code)) {
				/********************************************************************
				 * Confirmation du créneau de livraison
				 *********************************************************************/
				$confirm_delivery_slot = new confirmDeliverySlot();
				$confirm_delivery_slot->accountNumber = $account;
				$confirm_delivery_slot->password = $passwd;
				$confirm_delivery_slot->transactionID = $transaction_id;
				$confirm_delivery_slot->codeSlot = $delivery_slot_code;
				$confirm_delivery_slot->rank = $rank;
				$confirm_delivery_slot->dateSelected = $time_slot_wished->delivery_date;
				
				$res = $service->confirmDeliverySlot($confirm_delivery_slot)->return;

				echo 'Confirmation du créneau de livraison : <br/>';
				echo '<pre>'; var_dump($res->message); echo '</pre>';

				if (isset($res->errorCode)) {
					if ($res->errorCode == 0) {
						/********************************************************************
						* Génération de l'étiquette retour
						*********************************************************************/
					       $res = ChronoDeliverySlot::generateLabel($customer, $address, $order);
					       echo 'Génération de l\'étiquette retour : <br/>';
					       var_dump($res);
					} else {
						echo $res->errorMessage;
					}
				}
			}
		}
	}
}

