<?php

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;

class ChronoDeliverySlot extends SoapClient
{
	protected $password = false;
	protected $accountNumber = false;

	public function ChronoDeliverySlot($wsdl = 'https://www.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl', $options = array('soap_version' => SOAP_1_1, 'trace' => 1))
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

		return $this->__call(
			'confirmDeliverySlot',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(),
			array(
				new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr/', 'password', $this->password, false),
				new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr/', 'accountNumber', $this->accountNumber, false)
			)
		);
	}

	public static function generateLabel(confirmDeliverySlot $parameters, $customer = array(), $recipient = array(), $esd = array(), $skybill = array(), $ref = array())
	{
		if (!is_array($customer) || !is_array($recipient) || !is_array($esd) || !is_array($skybill) || !is_array($ref)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService();

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

		//Informations destinataire
		$recipient_value = new recipientValue();
		$recipient_value->recipientCivility = $recipient['civility'];
		$recipient_value->recipientName = $recipient['name'];
		$recipient_value->recipientName2 = $recipient['name2'];
		$recipient_value->recipientContactName = substr($recipient['contact_name'], 0, 35);
		$recipient_value->recipientAdress1 = $recipient['address1'];
		$recipient_value->recipientAdress2 = $recipient['address2'];
		$recipient_value->recipientCity = $recipient['city'];
		$recipient_value->recipientCountry = $recipient['iso_code'];
		$recipient_value->recipientZipCode = $recipient['zip_code'];

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
		$skybill_value->service = isset($skybill['service']) ? (int)$skybill['service'] : '';

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = 'PDF';

		$create_label = new shippingWithReservationAndESDWithRefClient();
		$create_label->password = $parameters->password;
		$create_label->esdValue = $esd_value;
		$create_label->headerValue = $header;
		$create_label->shipperValue = $shipper;
		$create_label->customerValue = $customer_value;
		$create_label->recipientValue = $recipient_value;
		$create_label->refValue = $ref_value;
		$create_label->skybillValue = $skybill_value;
		$create_label->skybillParamsValue = $skybill_params;

		$res = $shipping_ws->shippingWithReservationAndESDWithRefClient($create_label)->return;
		if ($res->errorCode == 0) {
			return $res;
		} else {
			return $res->errorMessage;
		}
	}
}
