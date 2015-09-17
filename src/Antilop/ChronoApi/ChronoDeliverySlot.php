<?php

namespace Antilop\ChronoApi;

use SoapClient;
use SoapParam;
use SoapHeader;
use DateTime;
use DateTimeZone;

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;
use Antilop\ChronoApi\ShippingServiceWSService;

class ChronoDeliverySlot extends SoapClient
{
	protected $password = false;
	protected $accountNumber = false;

	public function __construct($wsdl = 'https://www.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl', $options = array('soap_version' => SOAP_1_1, 'trace' => 1))
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

	public static function esdBooking(confirmDeliverySlot $parameters, $customer = array(), $recipient = array(), $esd = array(), $skybill = array(), $ref = array(), $mode_retour = 2)
	{
		if (!is_array($customer) || !is_array($recipient) || !is_array($esd) || !is_array($skybill) || !is_array($ref)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService("https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", array('soap_version' => SOAP_1_1, 'trace' => 1));

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

		$header = new headerValue();
		$header->accountNumber = $parameters->accountNumber;
		$header->idEmit = 'CHRFR';

		//Informations expéditeur
		$shipper = new shipperValue();
		$shipper->shipperCivility = $customer['civility'];
		$shipper->shipperContactName = substr($customer['firstname'] . ' ' . $customer['lastname'], 0, 35);
		$shipper->shipperAdress1 = substr($customer['address1'], 0, 35);
		$shipper->shipperAdress2 = substr($customer['address2'], 0, 35);
		$shipper->shipperCity = substr($customer['city'], 0, 30);
		$shipper->shipperCountry = $customer['iso_code'];
		$shipper->shipperZipCode = $customer['zip_code'];
		$shipper->shipperName = $customer['company'];
		$shipper->shipperName2 = substr($customer['firstname'] . ' ' . $customer['lastname'], 0, 35);
		$shipper->shipperMobilePhone = isset($customer['mobile']) ? $customer['mobile'] : '';
		$shipper->shipperPhone = isset($customer['phone']) ? $customer['phone'] : '';

		$customer_value = new customerValue();
		$customer_value->customerCivility = $customer['civility'];
		$customer_value->customerContactName = substr($customer['firstname'] . ' ' . $customer['lastname'], 0, 35);
		$customer_value->customerAdress1 = substr($customer['address1'], 0, 35);
		$customer_value->customerAdress2 = substr($customer['address2'], 0, 35);
		$customer_value->customerCity = substr($customer['city'], 0, 30);
		$customer_value->customerCountry = $customer['iso_code'];
		$customer_value->customerZipCode = $customer['zip_code'];
		$customer_value->customerName = $customer['company'];
		$customer_value->customer2 = substr($customer['firstname'] . ' ' . $customer['lastname'], 0, 35);
		$customer_value->customerMobilePhone = isset($customer['mobile']) ? $customer['mobile'] : '';
		$customer_value->customerPhone = isset($customer['phone']) ? $customer['phone'] : '';

		//Informations destinataire
		$recipient_value = new recipientValue();
		$recipient_value->recipientCivility = isset($recipient['civility']) ? $recipient['civility'] : '';
		$recipient_value->recipientName = isset($recipient['name']) ? $recipient['name'] : '';
		$recipient_value->recipientName2 = isset($recipient['name2']) ? $recipient['name2'] : '';
		$recipient_value->recipientContactName = isset($recipient['contact_name']) ? substr($recipient['contact_name'], 0, 35) : '';
		$recipient_value->recipientAdress1 = isset($recipient['address1']) ? $recipient['address1'] : '';
		$recipient_value->recipientAdress2 = isset($recipient['address2']) ? $recipient['address2'] : '';
		$recipient_value->recipientCity = isset($recipient['city']) ? $recipient['city'] : '';
		$recipient_value->recipientCountry = isset($recipient['iso_code']) ? $recipient['iso_code'] : '';
		$recipient_value->recipientZipCode = isset($recipient['zip_code']) ? $recipient['zip_code'] : '';
		$recipient_value->recipientPhone = isset($recipient['mobile']) ? $recipient['mobile'] : '';
		$recipient_value->recipientMobilePhone = isset($recipient['phone']) ? $recipient['phone'] : '';

		$ref_value = new refValue();
		$ref_value->recipientRef = isset($ref['recipient_ref']) ? $ref['recipient_ref'] : '';
		$ref_value->shipperRef = isset($ref['shipper_ref']) ? $ref['shipper_ref'] : '';

		$skybill_value = new skybillValue();
		$skybill_value->productCode = isset($skybill['product_code']) ? $skybill['product_code'] : '';
		$skybill_value->evtCode = isset($skybill['evt_code']) ? $skybill['evt_code'] : '';
		$skybill_value->shipDate = $now->format('Y-m-d\TH:i:s');
		$skybill_value->shipHour = $now->format('H');
		$skybill_value->objectType = isset($skybill['object_type']) ? $skybill['object_type'] : ''; //Type du colis = marchandise
		$skybill_value->weight = isset($skybill['weight']) ? (float)$skybill['weight'] : '';
		$skybill_value->service = isset($skybill['service']) ? $skybill['service'] : '';

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = 'PDF';

		$esd_booking = new shippingWithReservationAndESDWithRefClient();
		$esd_booking->esdValue = $esd_value;
		$esd_booking->headerValue = $header;
		$esd_booking->shipperValue = $shipper;
		$esd_booking->customerValue = $customer_value;
		$esd_booking->recipientValue = $recipient_value;
		$esd_booking->refValue = $ref_value;
		$esd_booking->skybillValue = $skybill_value;
		$esd_booking->skybillParamsValue = $skybill_params;
		$esd_booking->password = $parameters->password;
		$esd_booking->modeRetour = $mode_retour;

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
}
