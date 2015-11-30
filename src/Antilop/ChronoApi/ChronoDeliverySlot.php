<?php

namespace Antilop\ChronoApi;

use SoapClient;
use SoapParam;
use SoapHeader;
use DateTime;
use DateTimeZone;

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;
use Antilop\ChronoApi\Request\cancelSkybill;
use Antilop\ChronoApi\ShippingServiceWSService;

class ChronoDeliverySlot extends SoapClient
{
	protected $password = false;
	protected $accountNumber = false;

	public function __construct($wsdl = 'https://www.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl', $options = array('soap_version' => SOAP_1_1, 'trace' => 1, 'encoding' => 'UTF-8'))
	{
		parent::__construct($wsdl, $options);
	}

	public function __doRequest($request, $location, $action, $version)
	{
		return parent::__doRequest($request, $location, $action, $version);
	}

	public function searchDeliverySlot(searchDeliverySlot $parameters)
	{
		$this->password = $parameters->password;
		$this->accountNumber = $parameters->accountNumber;

		return $this->__soapCall(
			'searchDeliverySlot',
			array(
				new SoapParam($parameters, 'parameters'),
			),
			array(),
			array(
				new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr/', 'password', $this->password, false),
				new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr/', 'accountNumber', $this->accountNumber, false)
			)
		);
	}

	public function confirmDeliverySlot(confirmDeliverySlot $parameters)
	{
		$this->password = $parameters->password;
		$this->accountNumber = $parameters->accountNumber;

		return $this->__soapCall(
			'confirmDeliverySlot',
			array(
				new SoapParam($parameters, 'parameters'),
			),
			array(),
			array(
				new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr/', 'password', $this->password, false),
				new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr/', 'accountNumber', $this->accountNumber, false)
			)
		);
	}

	public static function esdBooking(confirmDeliverySlot $parameters, $customer = array(), $recipient = array(), $esd = array(), $skybill = array(), $ref = array(), $mode = 'PDF', $mode_retour = 2)
	{
		if (!is_array($customer) || !is_array($recipient) || !is_array($esd) || !is_array($skybill) || !is_array($ref)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService("https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", array('soap_version' => SOAP_1_1, 'trace' => 1, 'encoding' => 'UTF-8'));

		$now = new DateTime('now', new DateTimeZone('Europe/Paris'));
		$esd_value = new esdValue();
		$esd_value->retrievalDateTime = isset($esd['retrieval_datetime']) ? $esd['retrieval_datetime'] : '';
		$esd_value->closingDateTime = isset($esd['closing_datetime']) ? $esd['closing_datetime'] : '';
		$esd_value->specificInstructions = isset($esd['specific_instructions']) ? $esd['specific_instructions'] : '';
		$esd_value->width = isset($esd['width']) ? (float)$esd['width'] : '';
		$esd_value->height = isset($esd['height']) ? (float)$esd['height'] : '';
		$esd_value->length = isset($esd['length']) ? (float)$esd['length'] : '';
		$esd_value->shipperCarriesCode = isset($esd['shipper_carries_code']) ? $esd['shipper_carries_code'] : '';
		$esd_value->shipperBuildingFloor = isset($esd['shipper_building_floor']) ? $esd['shipper_building_floor'] : '';
		$esd_value->shipperServiceDirection = isset($esd['shipper_service_direction']) ? $esd['shipper_service_direction'] : '';
		$esd_value->refEsdClient = isset($esd['ref_esd']) ? $esd['ref_esd'] : '';
		$esd_value->nombreDePassageMaximum = isset($esd['nb_passage']) ? $esd['nb_passage'] : '';
		$esd_value->ltAImprimerParChronopost = isset($esd['lt_print_by_chrono']) ? $esd['lt_print_by_chrono'] : '';

		$header = new headerValue();
		$header->accountNumber = $parameters->accountNumber;
		$header->idEmit = 'CHRFR';

		//Informations Client = entreprise
		$customer_value = new customerValue();
		$customer_value->customerCivility = isset($recipient['civility']) ? $recipient['civility'] : '';
		$customer_value->customerContactName = isset($recipient['contact_name']) ? substr($recipient['contact_name'], 0, 100) : '';
		$customer_value->customerAdress1 = isset($recipient['address1']) ? substr($recipient['address1'], 0, 38) : '';
		$customer_value->customerAdress2 = isset($recipient['address2']) ? substr($recipient['address2'], 0, 38) : '';
		$customer_value->customerZipCode = isset($recipient['zip_code']) ? $recipient['zip_code'] : '';
		$customer_value->customerCity = isset($recipient['city']) ? substr($recipient['city'], 0, 50) : '';
		$customer_value->customerCountry = isset($recipient['iso_code']) ? $recipient['iso_code'] : '';
		$customer_value->customerCountryName = isset($recipient['country']) ? $recipient['country'] : '';
		$customer_value->customerName = isset($recipient['name']) ? $recipient['name'] : '';
		$customer_value->customerName2 = isset($recipient['name2']) ? substr($recipient['name2'], 0, 100) : '';
		$customer_value->customerPhone = isset($recipient['phone']) ? substr($recipient['phone'], 0, 100) : '';
		$customer_value->customerMobilePhone = isset($recipient['mobile']) ? $recipient['mobile'] : '';
		$customer_value->customerEmail = isset($recipient['email']) ? $recipient['email'] : '';
		$customer_value->customerPreAlert = isset($recipient['pre_alert']) ? $recipient['pre_alert'] : '';

		//Informations expéditeur
		$shipper_value = new shipperValue();
		$shipper_value->shipperCivility = $customer['civility'];
		$shipper_value->shipperContactName = substr($customer['contact_name'], 0, 100);
		$shipper_value->shipperAdress1 = substr($customer['address1'], 0, 38);
		$shipper_value->shipperAdress2 = substr($customer['address2'], 0, 38);
		$shipper_value->shipperCity = substr($customer['city'], 0, 50);
		$shipper_value->shipperCountry = $customer['iso_code'];
		$shipper_value->shipperZipCode = $customer['zip_code'];
		$shipper_value->shipperName = substr($customer['name'], 0, 100);
		$shipper_value->shipperName2 = substr($customer['name2'], 0, 100);
		$shipper_value->shipperMobilePhone = isset($customer['mobile']) ? $customer['mobile'] : '';
		$shipper_value->shipperPhone = isset($customer['phone']) ? $customer['phone'] : '';
		$shipper_value->shipperCountryName = isset($customer['country']) ? $customer['country'] : '';
		$shipper_value->shipperEmail = isset($customer['email']) ? $customer['email'] : '';
		$shipper_value->shipperPreAlert = isset($customer['pre_alert']) ? $customer['pre_alert'] : '';

		//Informations destinataire
		$recipient_value = new recipientValue();
		$recipient_value->recipientCivility = isset($recipient['civility']) ? $recipient['civility'] : '';
		$recipient_value->recipientName = isset($recipient['name']) ? substr($recipient['name'], 0, 100) : '';
		$recipient_value->recipientName2 = isset($recipient['name2']) ? substr($recipient['name2'], 0, 100) : '';
		$recipient_value->recipientContactName = isset($recipient['contact_name']) ? substr($recipient['contact_name'], 0, 100) : '';
		$recipient_value->recipientAdress1 = isset($recipient['address1']) ? substr($recipient['address1'], 0, 38) : '';
		$recipient_value->recipientAdress2 = isset($recipient['address2']) ? substr($recipient['address2'], 0, 38) : '';
		$recipient_value->recipientCity = isset($recipient['city']) ? substr($recipient['city'], 0, 50) : '';
		$recipient_value->recipientCountry = isset($recipient['iso_code']) ? $recipient['iso_code'] : '';
		$recipient_value->recipientZipCode = isset($recipient['zip_code']) ? $recipient['zip_code'] : '';
		$recipient_value->recipientPhone = isset($recipient['phone']) ? $recipient['phone'] : '';
		$recipient_value->recipientMobilePhone = isset($recipient['mobile']) ? $recipient['mobile'] : '';
		$recipient_value->recipientCountryName = isset($customer['country']) ? $customer['country'] : '';
		$recipient_value->recipientEmail = isset($recipient['email']) ? $recipient['email'] : '';
		$recipient_value->recipientPreAlert = isset($recipient['pre_alert']) ? $recipient['pre_alert'] : '';

		$ref_value = new refValue();
		$ref_value->recipientRef = isset($ref['recipient_ref']) ? $ref['recipient_ref'] : '';
		$ref_value->shipperRef = isset($ref['shipper_ref']) ? $ref['shipper_ref'] : '';
		$ref_value->customerSkybillNumber = isset($ref['customer_skybill_number']) ? $ref['customer_skybill_number'] : '';
		$ref_value->PCardTransactionNumber = isset($ref['card_transaction_number']) ? $ref['card_transaction_number'] : '';

		$skybill_value = new skybillValue();
		$skybill_value->productCode = isset($skybill['product_code']) ? $skybill['product_code'] : '';
		$skybill_value->evtCode = isset($skybill['evt_code']) ? $skybill['evt_code'] : '';
		$skybill_value->shipDate = $now->format('Y-m-d\TH:i:s');
		$skybill_value->shipHour = $now->format('H');
		$skybill_value->objectType = isset($skybill['object_type']) ? $skybill['object_type'] : ''; //Type du colis = marchandise
		$skybill_value->weight = isset($skybill['weight']) ? (float)$skybill['weight'] : '';
		$skybill_value->service = isset($skybill['service']) ? $skybill['service'] : '';

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = $mode;

		$esd_booking = new shippingWithReservationAndESDWithRefClient();
		$esd_booking->esdValue = $esd_value;
		$esd_booking->headerValue = $header;
		$esd_booking->shipperValue = $shipper_value;
		$esd_booking->customerValue = $customer_value;
		$esd_booking->recipientValue = $recipient_value;
		$esd_booking->refValue = $ref_value;
		$esd_booking->skybillValue = $skybill_value;
		$esd_booking->skybillParamsValue = $skybill_params;
		$esd_booking->password = $parameters->password;
		$esd_booking->modeRetour = $mode_retour;
		$esd_booking->version = '2.0';

		$res = $shipping_ws->shippingWithReservationAndESDWithRefClient($esd_booking)->return;
		if ($res->errorCode == 0) {
			$result = array(
				'result' => true,
				'esd' => $res
			);
		} else {
			$result = array(
				'result' => false,
				'message' => $res->errorMessage,
				'code' => $res->errorCode
			);
		}

		return $result;
	}

	public static function shippingBooking(confirmDeliverySlot $parameters, $customer = array(), $shipper = array(), $skybill = array(), $ref = array(), $appointment = array(), $mode = 'THE')
	{
		if (!is_array($customer) || !is_array($shipper) || !is_array($skybill) || !is_array($ref) || !is_array($appointment)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService("https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", array('soap_version' => SOAP_1_1, 'trace' => 1, 'encoding' => 'UTF-8'));

		$now = new DateTime('now', new DateTimeZone('Europe/Paris'));

		$header = new headerValue();
		$header->accountNumber = $parameters->accountNumber;
		$header->idEmit = 'CHRFR';

		//Informations client = entreprise
		$customer_value = new customerValue();
		$customer_value->customerCivility = isset($shipper['civility']) ? $shipper['civility'] : '';
		$customer_value->customerContactName = isset($shipper['contact_name']) ? substr($shipper['contact_name'], 0, 100) : '';
		$customer_value->customerAdress1 = isset($shipper['address1']) ? substr($shipper['address1'], 0, 38) : '';
		$customer_value->customerAdress2 = isset($shipper['address2']) ? substr($shipper['address2'], 0, 38) : '';
		$customer_value->customerCity = isset($shipper['city']) ? substr($shipper['city'], 0, 50) : '';
		$customer_value->customerCountry = isset($shipper['iso_code']) ? $shipper['iso_code'] : '';
		$customer_value->customerCountryName = isset($shipper['country']) ? $shipper['country'] : '';
		$customer_value->customerZipCode = isset($shipper['zip_code']) ? $shipper['zip_code'] : '';
		$customer_value->customerName = isset($shipper['name']) ? substr($shipper['name'], 0, 100) : '';
		$customer_value->customerName2 = isset($shipper['name2']) ? substr($shipper['name2'], 0, 100) : '';
		$customer_value->customerMobilePhone = isset($shipper['mobile']) ? $shipper['mobile'] : '';
		$customer_value->customerPhone = isset($shipper['phone']) ? $shipper['phone'] : '';
		$customer_value->customerEmail = isset($shipper['email']) ? $shipper['email'] : '';
		$customer_value->customerPreAlert = isset($shipper['pre_alert']) ? $shipper['pre_alert'] : '';

		//Informations expéditeur
		$shipper_value = new shipperValue();
		$shipper_value->shipperCivility = isset($shipper['civility']) ? $shipper['civility'] : '';
		$shipper_value->shipperContactName = isset($shipper['contact_name']) ? substr($shipper['contact_name'], 0, 100) : '';
		$shipper_value->shipperAdress1 = isset($shipper['address1']) ? substr($shipper['address1'], 0, 38) : '';
		$shipper_value->shipperAdress2 = isset($shipper['address2']) ? substr($shipper['address2'], 0, 38) : '';
		$shipper_value->shipperCity = isset($shipper['city']) ? substr($shipper['city'], 0, 50) : '';
		$shipper_value->shipperCountry = isset($shipper['iso_code']) ? $shipper['iso_code'] : '';
		$shipper_value->shipperCountryName = isset($shipper['country']) ? $shipper['country'] : '';
		$shipper_value->shipperZipCode = isset($shipper['zip_code']) ? $shipper['zip_code'] : '';
		$shipper_value->shipperName = isset($shipper['name']) ? substr($shipper['name'], 0, 100) : '';
		$shipper_value->shipperName2 = isset($shipper['name2']) ? substr($shipper['name2'], 0, 100) : '';
		$shipper_value->shipperMobilePhone = isset($shipper['mobile']) ? $shipper['mobile'] : '';
		$shipper_value->shipperPhone = isset($shipper['phone']) ? $shipper['phone'] : '';
		$shipper_value->shipperEmail = isset($shipper['email']) ? $shipper['email'] : '';
		$shipper_value->shipperPreAlert = isset($shipper['pre_alert']) ? $shipper['pre_alert'] : '';

		//Informations destinataire
		$recipient_value = new recipientValue();
		$recipient_value->recipientCivility = isset($customer['civility']) ? $customer['civility'] : '';
		$recipient_value->recipientName = isset($customer['name']) ? substr($customer['name'], 0, 100) : '';
		$recipient_value->recipientName2 = isset($customer['name2']) ? substr($customer['name2'], 0, 100) : '';
		$recipient_value->recipientContactName = isset($customer['contact_name']) ? substr($customer['contact_name'], 0, 100) : '';
		$recipient_value->recipientAdress1 = isset($customer['address1']) ? substr($customer['address1'], 0, 38) : '';
		$recipient_value->recipientAdress2 = isset($customer['address2']) ? substr($customer['address2'], 0, 38) : '';
		$recipient_value->recipientCity = isset($customer['city']) ? substr($customer['city'], 0, 50) : '';
		$recipient_value->recipientCountry = isset($customer['iso_code']) ? $customer['iso_code'] : '';
		$recipient_value->recipientCountryName = isset($customer['country']) ? $customer['country'] : '';
		$recipient_value->recipientZipCode = isset($customer['zip_code']) ? $customer['zip_code'] : '';
		$recipient_value->recipientPhone = isset($customer['phone']) ? $customer['phone'] : '';
		$recipient_value->recipientMobilePhone = isset($customer['mobile']) ? $customer['mobile'] : '';
		$recipient_value->recipientEmail = isset($customer['email']) ? $customer['email'] : '';
		$recipient_value->recipientPreAlert = isset($customer['pre_alert']) ? $customer['pre_alert'] : '';

		$ref_value = new refValue();
		$ref_value->recipientRef = isset($ref['recipient_ref']) ? $ref['recipient_ref'] : '';
		$ref_value->shipperRef = isset($ref['shipper_ref']) ? $ref['shipper_ref'] : '';
		$ref_value->customerSkybillNumber = isset($ref['customer_skybill_number']) ? $ref['customer_skybill_number'] : '';
		$ref_value->PCardTransactionNumber = isset($ref['card_transaction_number']) ? $ref['card_transaction_number'] : '';

		$skybill_value = new skybillValue();
		$skybill_value->productCode = isset($skybill['product_code']) ? $skybill['product_code'] : '';
		$skybill_value->evtCode = isset($skybill['evt_code']) ? $skybill['evt_code'] : '';
		$skybill_value->shipDate = $now->format('Y-m-d\TH:i:s');
		$skybill_value->shipHour = $now->format('H');
		$skybill_value->objectType = isset($skybill['object_type']) ? $skybill['object_type'] : ''; //Type du colis = marchandise
		$skybill_value->weight = isset($skybill['weight']) ? (float)$skybill['weight'] : '';
		$skybill_value->service = isset($skybill['service']) ? $skybill['service'] : '';

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = $mode;

		$appointment_value = new appointementValue();
		$appointment_value->timeSlotTariffLevel = isset($appointment['time_slot_tariff']) ? $appointment['time_slot_tariff'] : '';
		$appointment_value->timeSlotStartDate = isset($appointment['time_slot_start']) ? $appointment['time_slot_start'] : '';
		$appointment_value->timeSlotEndDate  = isset($appointment['time_slot_end']) ? $appointment['time_slot_end'] : '';

		$scheduled_value = new scheduledValue();
		$scheduled_value->appointmentValue = $appointment_value;
		$scheduled_value->expirationDate = isset($appointment['expiration_date']) ? $appointment['expiration_date'] : '';
		$scheduled_value->sellByDate = isset($appointment['sell_by_date']) ? $appointment['sell_by_date'] : '';

		$shipping_booking = new shippingWithReservationAndESDWithRefClient();
		$shipping_booking->headerValue = $header;
		$shipping_booking->shipperValue = $shipper_value;
		$shipping_booking->customerValue = $customer_value;
		$shipping_booking->recipientValue = $recipient_value;
		$shipping_booking->refValue = $ref_value;
		$shipping_booking->skybillValue = $skybill_value;
		$shipping_booking->skybillParamsValue = $skybill_params;
		$shipping_booking->scheduledValue = $scheduled_value;
		$shipping_booking->password = $parameters->password;

		$res = $shipping_ws->shippingWithReservationAndESDWithRefClient($shipping_booking)->return;
		if ($res->errorCode == 0) {
			$result = array(
				'result' => true,
				'shipping' => $res
			);
		} else {
			$result = array(
				'result' => false,
				'message' => $res->errorMessage,
				'code' => $res->errorCode
			);
		}

		return $result;
	}

	public static function shippingDeliveryBooking(confirmDeliverySlot $parameters, $customer = array(), $shipper = array(), $skybill = array(), $ref = array(), $mode = 'THE')
	{
		if (!is_array($customer) || !is_array($shipper) || !is_array($skybill) || !is_array($ref)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService("https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", array('soap_version' => SOAP_1_1, 'trace' => 1, 'encoding' => 'UTF-8'));

		$now = new DateTime('now', new DateTimeZone('Europe/Paris'));

		$header = new headerValue();
		$header->accountNumber = $parameters->accountNumber;
		$header->idEmit = 'CHRFR';

		//Informations client = entreprise
		$customer_value = new customerValue();
		$customer_value->customerCivility = isset($shipper['civility']) ? $shipper['civility'] : '';
		$customer_value->customerContactName = isset($shipper['contact_name']) ? substr($shipper['contact_name'], 0, 100) : '';
		$customer_value->customerAdress1 = isset($shipper['address1']) ? substr($shipper['address1'], 0, 38) : '';
		$customer_value->customerAdress2 = isset($shipper['address2']) ? substr($shipper['address2'], 0, 38) : '';
		$customer_value->customerCity = isset($shipper['city']) ? substr($shipper['city'], 0, 50) : '';
		$customer_value->customerCountry = isset($shipper['iso_code']) ? $shipper['iso_code'] : '';
		$customer_value->customerCountryName = isset($shipper['country']) ? $shipper['country'] : '';
		$customer_value->customerZipCode = isset($shipper['zip_code']) ? $shipper['zip_code'] : '';
		$customer_value->customerName = isset($shipper['name']) ? substr($shipper['name'], 0, 100) : '';
		$customer_value->customerName2 = isset($shipper['name2']) ? substr($shipper['name2'], 0, 100) : '';
		$customer_value->customerMobilePhone = isset($shipper['mobile']) ? $shipper['mobile'] : '';
		$customer_value->customerPhone = isset($shipper['phone']) ? $shipper['phone'] : '';
		$customer_value->customerEmail = isset($shipper['email']) ? $shipper['email'] : '';
		$customer_value->customerPreAlert = isset($shipper['pre_alert']) ? $shipper['pre_alert'] : '';

		//Informations expéditeur
		$shipper_value = new shipperValue();
		$shipper_value->shipperCivility = isset($shipper['civility']) ? $shipper['civility'] : '';
		$shipper_value->shipperContactName = isset($shipper['contact_name']) ? substr($shipper['contact_name'], 0, 100) : '';
		$shipper_value->shipperAdress1 = isset($shipper['address1']) ? substr($shipper['address1'], 0, 38) : '';
		$shipper_value->shipperAdress2 = isset($shipper['address2']) ? substr($shipper['address2'], 0, 38) : '';
		$shipper_value->shipperCity = isset($shipper['city']) ? substr($shipper['city'], 0, 50) : '';
		$shipper_value->shipperCountry = isset($shipper['iso_code']) ? $shipper['iso_code'] : '';
		$shipper_value->shipperCountryName = isset($shipper['country']) ? $shipper['country'] : '';
		$shipper_value->shipperZipCode = isset($shipper['zip_code']) ? $shipper['zip_code'] : '';
		$shipper_value->shipperName = isset($shipper['name']) ? substr($shipper['name'], 0, 100) : '';
		$shipper_value->shipperName2 = isset($shipper['name2']) ? substr($shipper['name2'], 0, 100) : '';
		$shipper_value->shipperMobilePhone = isset($shipper['mobile']) ? $shipper['mobile'] : '';
		$shipper_value->shipperPhone = isset($shipper['phone']) ? $shipper['phone'] : '';
		$shipper_value->shipperEmail = isset($shipper['email']) ? $shipper['email'] : '';
		$shipper_value->shipperPreAlert = isset($shipper['pre_alert']) ? $shipper['pre_alert'] : '';

		//Informations destinataire
		$recipient_value = new recipientValue();
		$recipient_value->recipientCivility = isset($customer['civility']) ? $customer['civility'] : '';
		$recipient_value->recipientName = isset($customer['name']) ? substr($customer['name'], 0, 100) : '';
		$recipient_value->recipientName2 = isset($customer['name2']) ? substr($customer['name2'], 0, 100) : '';
		$recipient_value->recipientContactName = isset($customer['contact_name']) ? substr($customer['contact_name'], 0, 38) : '';
		$recipient_value->recipientAdress1 = isset($customer['address1']) ? substr($customer['address1'], 0, 38) : '';
		$recipient_value->recipientAdress2 = isset($customer['address2']) ? substr($customer['address2'], 0, 38) : '';
		$recipient_value->recipientCity = isset($customer['city']) ? substr($customer['city'], 0, 50) : '';
		$recipient_value->recipientCountry = isset($customer['iso_code']) ? $customer['iso_code'] : '';
		$recipient_value->recipientCountryName = isset($customer['country']) ? $customer['country'] : '';
		$recipient_value->recipientZipCode = isset($customer['zip_code']) ? $customer['zip_code'] : '';
		$recipient_value->recipientPhone = isset($customer['phone']) ? $customer['phone'] : '';
		$recipient_value->recipientMobilePhone = isset($customer['mobile']) ? $customer['mobile'] : '';
		$recipient_value->recipientEmail = isset($customer['email']) ? $customer['email'] : '';
		$recipient_value->recipientPreAlert = isset($customer['pre_alert']) ? $customer['pre_alert'] : '';

		$ref_value = new refValue();
		$ref_value->recipientRef = isset($ref['recipient_ref']) ? $ref['recipient_ref'] : '';
		$ref_value->shipperRef = isset($ref['shipper_ref']) ? $ref['shipper_ref'] : '';
		$ref_value->customerSkybillNumber = isset($ref['customer_skybill_number']) ? $ref['customer_skybill_number'] : '';
		$ref_value->PCardTransactionNumber = isset($ref['card_transaction_number']) ? $ref['card_transaction_number'] : '';

		$skybill_value = new skybillValue();
		$skybill_value->productCode = isset($skybill['product_code']) ? $skybill['product_code'] : '';
		$skybill_value->evtCode = isset($skybill['evt_code']) ? $skybill['evt_code'] : '';
		$skybill_value->shipDate = $now->format('Y-m-d\TH:i:s');
		$skybill_value->shipHour = $now->format('H');
		$skybill_value->objectType = isset($skybill['object_type']) ? $skybill['object_type'] : ''; //Type du colis = marchandise
		$skybill_value->weight = isset($skybill['weight']) ? (float)$skybill['weight'] : '';
		$skybill_value->service = isset($skybill['service']) ? $skybill['service'] : '';

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = $mode;

		$shipping_booking = new shippingWithReservationAndESDWithRefClient();
		$shipping_booking->headerValue = $header;
		$shipping_booking->shipperValue = $shipper_value;
		$shipping_booking->customerValue = $customer_value;
		$shipping_booking->recipientValue = $recipient_value;
		$shipping_booking->refValue = $ref_value;
		$shipping_booking->skybillValue = $skybill_value;
		$shipping_booking->skybillParamsValue = $skybill_params;
		$shipping_booking->password = $parameters->password;

		$res = $shipping_ws->shippingWithReservationAndESDWithRefClient($shipping_booking)->return;
		if ($res->errorCode == 0) {
			$result = array(
				'result' => true,
				'shipping' => $res
			);
		} else {
			$result = array(
				'result' => false,
				'message' => $res->errorMessage,
				'code' => $res->errorCode
			);
		}

		return $result;
	}

	public static function shippingReturnBooking($params = array(), $customer = array(), $recipient = array(), $skybill = array(), $ref = array(), $mode = 'PDF')
	{
		if (!is_array($customer) || !is_array($recipient) || !is_array($skybill) || !is_array($ref)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService("https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", array('soap_version' => SOAP_1_1, 'trace' => 1));

		$now = new DateTime('now', new DateTimeZone('Europe/Paris'));

		$header = new headerValue();
		$header->accountNumber = $params['account_number'];
		$header->idEmit = 'CHRFR';

		//Informations Client = entreprise
		$customer_value = new customerValue();
		$customer_value->customerCivility = isset($recipient['civility']) ? $recipient['civility'] : '';
		$customer_value->customerContactName = isset($recipient['contact_name']) ? substr($recipient['contact_name'], 0, 100) : '';
		$customer_value->customerAdress1 = isset($recipient['address1']) ? substr($recipient['address1'], 0 , 38) : '';
		$customer_value->customerAdress2 = isset($recipient['address2']) ? substr($recipient['address2'], 0, 38) : '';
		$customer_value->customerZipCode = isset($recipient['zip_code']) ? $recipient['zip_code'] : '';
		$customer_value->customerCity = isset($recipient['city']) ? substr($recipient['city'], 0, 50) : '';
		$customer_value->customerCountry = isset($recipient['iso_code']) ? $recipient['iso_code'] : '';
		$customer_value->customerCountryName = isset($customer['country']) ? $customer['country'] : '';
		$customer_value->customerName = isset($recipient['name']) ? substr($recipient['name'], 0, 100) : '';
		$customer_value->customerName2 = isset($recipient['name2']) ? substr($recipient['name2'], 0, 100) : '';
		$customer_value->customerPhone = isset($recipient['phone']) ? $recipient['phone'] : '';
		$customer_value->customerMobilePhone = isset($recipient['mobile']) ? $recipient['mobile'] : '';
		$customer_value->customerEmail = isset($recipient['email']) ? $recipient['email'] : '';
		$customer_value->customerPreAlert = isset($recipient['pre_alert']) ? $recipient['pre_alert'] : '';

		//Informations expéditeur
		$shipper_value = new shipperValue();
		$shipper_value->shipperCivility = $customer['civility'];
		$shipper_value->shipperContactName = substr($customer['contact_name'], 0, 100);
		$shipper_value->shipperAdress1 = substr($customer['address1'], 0, 38);
		$shipper_value->shipperAdress2 = substr($customer['address2'], 0, 38);
		$shipper_value->shipperCity = substr($customer['city'], 0, 50);
		$shipper_value->shipperCountry = $customer['iso_code'];
		$shipper_value->shipperZipCode = $customer['zip_code'];
		$shipper_value->shipperName = substr($customer['name'], 0, 100);
		$shipper_value->shipperName2 = substr($customer['name2'], 0, 100);
		$shipper_value->shipperMobilePhone = isset($customer['mobile']) ? $customer['mobile'] : '';
		$shipper_value->shipperPhone = isset($customer['phone']) ? $customer['phone'] : '';
		$shipper_value->shipperCountryName = isset($customer['country']) ? $customer['country'] : '';
		$shipper_value->shipperEmail = isset($customer['email']) ? $customer['email'] : '';
		$shipper_value->shipperPreAlert = isset($customer['pre_alert']) ? $customer['pre_alert'] : '';

		//Informations destinataire
		$recipient_value = new recipientValue();
		$recipient_value->recipientCivility = isset($recipient['civility']) ? $recipient['civility'] : '';
		$recipient_value->recipientName = isset($recipient['name']) ? substr($recipient['name'], 0, 100) : '';
		$recipient_value->recipientName2 = isset($recipient['name2']) ? substr($recipient['name2'], 0, 100) : '';
		$recipient_value->recipientContactName = isset($recipient['contact_name']) ? substr($recipient['contact_name'], 0, 100) : '';
		$recipient_value->recipientAdress1 = isset($recipient['address1']) ? substr($recipient['address1'], 0, 38) : '';
		$recipient_value->recipientAdress2 = isset($recipient['address2']) ? substr($recipient['address2'], 0, 38) : '';
		$recipient_value->recipientCity = isset($recipient['city']) ? substr($recipient['city'], 0, 50) : '';
		$recipient_value->recipientCountry = isset($recipient['iso_code']) ? $recipient['iso_code'] : '';
		$recipient_value->recipientZipCode = isset($recipient['zip_code']) ? $recipient['zip_code'] : '';
		$recipient_value->recipientPhone = isset($recipient['phone']) ? $recipient['phone'] : '';
		$recipient_value->recipientMobilePhone = isset($recipient['mobile']) ? $recipient['mobile'] : '';
		$recipient_value->recipientCountryName = isset($customer['country']) ? $customer['country'] : '';
		$recipient_value->recipientEmail = isset($recipient['email']) ? $recipient['email'] : '';
		$recipient_value->recipientPreAlert = isset($recipient['pre_alert']) ? $recipient['pre_alert'] : '';

		$ref_value = new refValue();
		$ref_value->recipientRef = isset($ref['recipient_ref']) ? $ref['recipient_ref'] : '';
		$ref_value->shipperRef = isset($ref['shipper_ref']) ? $ref['shipper_ref'] : '';
		$ref_value->customerSkybillNumber = isset($ref['customer_skybill_number']) ? $ref['customer_skybill_number'] : '';
		$ref_value->PCardTransactionNumber = isset($ref['card_transaction_number']) ? $ref['card_transaction_number'] : '';

		$skybill_value = new skybillValue();
		$skybill_value->productCode = isset($skybill['product_code']) ? $skybill['product_code'] : '';
		$skybill_value->evtCode = isset($skybill['evt_code']) ? $skybill['evt_code'] : '';
		$skybill_value->shipDate = $now->format('Y-m-d\TH:i:s');
		$skybill_value->shipHour = $now->format('H');
		$skybill_value->objectType = isset($skybill['object_type']) ? $skybill['object_type'] : ''; //Type du colis = marchandise
		$skybill_value->weight = isset($skybill['weight']) ? (float)$skybill['weight'] : '';
		$skybill_value->service = isset($skybill['service']) ? $skybill['service'] : '';

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = $mode;

		$shipping_return_booking = new shippingWithReservationAndESDWithRefClient();
		$shipping_return_booking->headerValue = $header;
		$shipping_return_booking->shipperValue = $shipper_value;
		$shipping_return_booking->customerValue = $customer_value;
		$shipping_return_booking->recipientValue = $recipient_value;
		$shipping_return_booking->refValue = $ref_value;
		$shipping_return_booking->skybillValue = $skybill_value;
		$shipping_return_booking->skybillParamsValue = $skybill_params;
		$shipping_return_booking->password = $params['password'];

		$res = $shipping_ws->shippingWithReservationAndESDWithRefClient($shipping_return_booking)->return;
		if ($res->errorCode == 0) {
			$result = array(
				'result' => true,
				'shipping_return' => $res
			);
		} else {
			$result = array(
				'result' => false,
				'message' => $res->errorMessage,
				'code' => $res->errorCode
			);
		}

		return $result;
	}

	public static function getEtiquette($params)
	{
		$etiquette = new getReservedSkybillWithType();
		$etiquette->reservationNumber = $params['reservation_number'];

		$shipping_ws = new ShippingServiceWSService("https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", array('soap_version' => SOAP_1_1, 'trace' => 1, 'encoding' => 'UTF-8'));
		$response = $shipping_ws->getReservedSkybillWithType($etiquette)->return;

		if (is_object($response)) {
			$error_code = $response->errorCode;
			if ($error_code === 0) {
				$result = array(
					'result' => true,
					'url_etiquette' => 'https://www.chronopost.fr/shipping-cxf/getReservedSkybill?reservationNumber=' . $params['reservation_number']
				);
			} else {
				$result = array(
					'result' => false,
					'error' => $response->errorMessage
				);
			}
		} else {
			$result = array(
				'result' => false,
				'error' => $response->errorMessage
			);
		}

		return $result;
	}

	public static function cancelSkybill($params)
	{
		if (!$params) {
			$result = array(
				'result' => false,
				'error' => 'Paramètres manquants.'
			);

			return $result;
		}

		$tracking_ws = new TrackingServiceWSService();
		$response = $tracking_ws->cancelSkybill($params)->return;

		if (is_object($response)) {
			$error_code = $response->errorCode;
			if ($error_code === 0) {
				$result = array(
					'result' => true,
					'code_error' => $error_code
				);
			} else {
				$result = array(
					'result' => false,
					'error' => $response->errorMessage,
					'code_error' => $response->errorCode
				);
			}
		} else {
			$result = array(
				'result' => false,
				'error' => 'Une erreur est survenue',
			);
		}

		return $result;
	}
}
