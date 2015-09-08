<?php

use Antilop\ChronoApi\Request\searchDeliverySlot;
use Antilop\ChronoApi\Request\confirmDeliverySlot;

class ChronoDeliverySlot extends SoapClient
{
	public function ChronoDeliverySlot($wsdl = 'https://www.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl', $options = array('trace' => true))
	{
		$auth = new stdClass();
		$auth->password = $this->getPasswd();
		$auth->accountNumber = $this->getAccount();

		$header = new SoapHeader('http://cxf.soap.ws.creneau.chronopost.fr', 'Header', $auth);
		$this->__setSoapHeaders($header);
		parent::__construct($wsdl, $options);
	}

	public function __doRequest($request, $location, $action, $version)
	{
		$domRequest = new DOMDocument();
		$domRequest->loadXML($request);

		$xp = new DOMXPath($domRequest);
		$xp->registerNamespace('s', 'http://schemas.xmlsoap.org/soap/envelope/');
		$header = $xp->query('/s:Envelope/s:Header')->item(0);

		$usernameToken = $domRequest->createElementNS('http://schemas.xmlsoap.org/ws/2002/07/secext', 'soapenv:Header');
		$password= $domRequest->createElementNS('http://schemas.xmlsoap.org/ws/2002/07/secext', 'cxf:password', $this->getPasswd());
		$username = $domRequest->createElementNS('http://schemas.xmlsoap.org/ws/2002/07/secext', 'cxf:accountNumber', $this->getAccount());

		$usernameToken->appendChild($username);
		$usernameToken->appendChild($password);
		$header->appendChild($usernameToken);

		$request = $domRequest->saveXML();

		return parent::__doRequest($request, $location, $action, $version);
	}

	protected function getAccount()
	{
		return Configuration::get('CHRONOAPI_ACCOUNT');
	}

	protected function getPasswd()
	{
		return Configuration::get('CHRONOAPI_PASSWD');
	}

	public function searchDeliverySlot(searchDeliverySlot $parameters)
	{
		return $this->__call(
			'searchDeliverySlot',
			array(
				new SoapParam($parameters, 'parameters'),
			),
			array(
				'uri' => 'http://cxf.soap.ws.creneau.chronopost.fr',
				'soapaction' => ''
			)
		);
	}

	public function confirmDeliverySlot(confirmDeliverySlot $parameters)
	{
		return $this->__call(
			'confirmDeliverySlot',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.soap.ws.creneau.chronopost.fr',
				'soapaction' => ''
			)
		);
	}

	public static function checkDeliverySlot(Address $address, $time_slot)
	{
		if (!Validate::isLoadedObject($address)) {
			return false;
		}

		$shipping_ws = new ShippingServiceWSService();
		$service = new ChronoDeliverySlot();
	}
	
	public static function generateLabel(Customer $customer, Address $address, Order $order)
	{
		if (!Validate::isLoadedObject($customer) || !Validate::isLoadedObject($address) || !Validate::isLoadedObject($order)) {
			return false;
		}

		$account = $this->getAccount();
		$passwd = $this->getPasswd();

		$shipping_ws = new ShippingServiceWSService();

		$esd = new esdValue();
		$esd->retrievalDateTime = date('Y-m-d');
		$esd->closingDateTime = '';
		$esd->specificInstructions = '';
		$esd->width = '';
		$esd->height = '';
		$esd->length = '';
		$esd->shipperCarriesCode = '';
		$esd->shipperBuildingFloor = '';
		$esd->shipperServiceDirection = '';
		$esd->refEsdClient = '';

		$header = new headerValue();
		$header->accountNumber = $account;
		$header->idEmit = 'CHRFR';

		//Informations expéditeur
		$shipper = new shipperValue();
		$gender = new Gender($customer->id_gender, $order->id_lang);
		$country_shipper = new Country($address->id_country);
		$shipper->shipperCivility = $gender->name;
		$shipper->shipperContactName = Tools::substr($customer->firstname . ' ' . $customer->lastname, 0, 35);
		$shipper->shipperAdress1 = Tools::substr($address->address1, 0, 35);
		$shipper->shipperAdress2 = Tools::substr($address->address2, 0, 35);
		$shipper->shipperCity = Tools::substr($address->city, 0, 30);
		$shipper->shipperCountry = $country_shipper->iso_code;
		$shipper->shipperZipCode = $address->postcode;
		$shipper->shipperName = $address->company;
		$shipper->shipperName2 = Tools::substr($customer->firstname . ' ' . $customer->lastname, 0, 35);

		//Informations destinataire
		$recipient = new recipientValue();
		$country_recipient = new Country(Configuration::get('PS_SHOP_COUNTRY_ID'));
		$recipient->recipientCivility = '';
		$recipient->recipientName = Configuration::get('PS_SHOP_NAME');
		$recipient->recipientName2 = Configuration::get('PS_SHOP_NAME');
		$recipient->recipientContactName = Configuration::get('PS_SHOP_NAME');
		$recipient->recipientAdress1 = Configuration::get('PS_SHOP_ADDR1');
		$recipient->recipientAdress2 = Configuration::get('PS_SHOP_ADDR2');
		$recipient->recipientCity = Configuration::get('PS_SHOP_CITY');
		$recipient->recipientCountry = $country_recipient->iso_code;
		$recipient->recipientZipCode = Configuration::get('PS_SHOP_CODE');

		$ref_value = new refValue();
		$ref_value->recipientRef = $address->postcode;
		$ref_value->shipperRef = '';

		$skybill = new skybillValue();
		$skybill->productCode = '44';
		$skybill->evtCode = 'DC';
		$skybill->shipDate = date('Y-m-d\TH:i:s');
		$skybill->shipHour = date('H');
		$skybill->objectType = 'MAR'; //Type du colis = marchandise
		$skybill->weight = $order->getTotalWeight();

		$skybill_params = new skybillParamsValue();
		$skybill_params->mode = 'PDF';

		$create_label = new shippingWithReservationAndESDWithRefClient();
		$create_label->password = '';
		$create_label->esdValue = $esd;
		$create_label->headerValue = $header;
		$create_label->shipperValue = $shipper;
		$create_label->recipientValue = $recipient;
		$create_label->refValue = $ref_value;
		$create_label->skybillValue = $skybill;
		$create_label->skybillParamsValue = $skybill_params;

		$res = $shipping_ws->shippingWithReservationAndESDWithRefClient($create_label)->return;

		if ($res->errorCode == 0) {
			return $res->return->productService;
		} else {
			return $res->errorMessage;
		}
	}
}
