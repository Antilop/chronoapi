<?php

namespace Antilop\ChronoApi;

use SoapClient;
use SoapParam;
use SoapHeader;

class resultGetReservedSkybillWithTypeValue
{
	public $errorCode; // int
	public $errorMessage; // string
	public $skybill; // string
	public $type; // string
}

class esdValue
{
	public $closingDateTime; // dateTime
	public $height; // float
	public $length; // float
	public $retrievalDateTime; // dateTime
	public $shipperBuildingFloor; // string
	public $shipperCarriesCode; // string
	public $shipperServiceDirection; // string
	public $specificInstructions; // string
	public $width; // float
	public $refEsdClient;
	public $nombreDePassageMaximum = 1;
	public $ltAImprimerParChronopost = 1;
}

class headerValue
{
	public $accountNumber; // int
	public $idEmit; // string
	public $identWebPro; // string
	public $subAccount; // int
}

class shipperValue
{
	public $shipperAdress1; // string
	public $shipperAdress2; // string
	public $shipperCity; // string
	public $shipperCivility; // string
	public $shipperContactName; // string
	public $shipperCountry; // string
	public $shipperCountryName; // string
	public $shipperEmail; // string
	public $shipperMobilePhone; // string
	public $shipperName; // string
	public $shipperName2; // string
	public $shipperPhone; // string
	public $shipperPreAlert; // int
	public $shipperZipCode; // string
}

class customerValue
{
	public $customerAdress1; // string
	public $customerAdress2; // string
	public $customerCity; // string
	public $customerCivility; // string
	public $customerContactName; // string
	public $customerCountry; // string
	public $customerCountryName; // string
	public $customerEmail; // string
	public $customerMobilePhone; // string
	public $customerName; // string
	public $customerName2; // string
	public $customerPhone; // string
	public $customerPreAlert; // int
	public $customerZipCode; // string
}

class recipientValue
{
	public $recipientAdress1; // string
	public $recipientAdress2; // string
	public $recipientCity; // string
	public $recipientContactName; // string
	public $recipientCountry; // string
	public $recipientCountryName; // string
	public $recipientEmail; // string
	public $recipientMobilePhone; // string
	public $recipientName; // string
	public $recipientName2; // string
	public $recipientPhone; // string
	public $recipientPreAlert; // int
	public $recipientZipCode; // string
}

class refValue
{
	public $customerSkybillNumber; // string
	public $PCardTransactionNumber; // string
	public $recipientRef; // string
	public $shipperRef; // string
}

class skybillValue
{
	public $bulkNumber; // string
	public $codCurrency; // string
	public $codValue; // float
	public $content1; // string
	public $content2; // string
	public $content3; // string
	public $content4; // string
	public $content5; // string
	public $customsCurrency; // string
	public $customsValue; // float
	public $evtCode; // string
	public $insuredCurrency; // string
	public $insuredValue; // float
	public $objectType; // string
	public $portCurrency; // string
	public $portValue; // float
	public $productCode; // string
	public $service; // string
	public $shipDate; // dateTime
	public $shipHour; // int
	public $skybillRank; // string
	public $weight; // float
	public $weightUnit; // string
}

class skybillParamsValue
{
	public $mode; // string
}

class appointementParamsValue
{
	public $appointmentValue;
	public $expirationDate;
	public $sellByDate;
}

class scheduledParamsValue
{
	public $timeSlotEndDate;
	public $timeSlotStartDate;
	public $timeSlotTariffLevel;
}

class resultExpeditionValue
{
	public $ESDNumber; // string
	public $errorCode; // int
	public $errorMessage; // string
	public $pickupDate; // dateTime
	public $skybill; // base64Binary
	public $skybillNumber; // string
}

class resultGetReservedSkybillValue
{
	public $errorCode; // int
	public $errorMessage; // string
	public $skybill; // string
}

class resultReservationExpeditionValue
{
	public $codeDepot; // string
	public $codeService; // string
	public $DSort; // string
	public $destinationDepot; // string
	public $ESDNumber; // string
	public $errorCode; // int
	public $errorMessage; // string
	public $geoPostCodeBarre; // string
	public $geoPostNumeroColis; // string
	public $groupingPriorityLabel; // string
	public $OSort; // string
	public $pickupDate; // dateTime
	public $reservationNumber; // string
	public $serviceMark; // string
	public $serviceName; // string
	public $signaletiqueProduit; // string
	public $skybillNumber; // string
}

class esdWithRefClientValue
{
	public $ltAImprimerParChronopost; // boolean
	public $nombreDePassageMaximum; // int
	public $refEsdClient; // string
}

class getReservedSkybillWithType
{
	public $reservationNumber; // string
}

class getReservedSkybillWithTypeResponse
{
	public $return; // resultGetReservedSkybillWithTypeValue
}

class shipping
{
	public $esdValue; // esdValue
	public $headerValue; // headerValue
	public $shipperValue; // shipperValue
	public $customerValue; // customerValue
	public $recipientValue; // recipientValue
	public $refValue; // refValue
	public $skybillValue; // skybillValue
	public $skybillParamsValue; // skybillParamsValue
	public $password; // string
}

class shippingResponse
{
	public $return; // resultExpeditionValue
}

class getReservedSkybill
{
	public $reservationNumber; // string
}

class getReservedSkybillResponse
{
	public $return; // resultGetReservedSkybillValue
}

class shippingWithReservation
{
	public $esdValue; // esdValue
	public $headerValue; // headerValue
	public $shipperValue; // shipperValue
	public $customerValue; // customerValue
	public $recipientValue; // recipientValue
	public $refValue; // refValue
	public $skybillValue; // skybillValue
	public $skybillParamsValue; // skybillParamsValue
	public $password; // string
	public $modeRetour; // string
}

class shippingWithReservationResponse
{

	public $return; // resultReservationExpeditionValue

}

class shippingWithReservationAndESDWithRefClient
{
	public $esdValue; // esdWithRefClientValue
	public $headerValue; // headerValue
	public $shipperValue; // shipperValue
	public $customerValue; // customerValue
	public $recipientValue; // recipientValue
	public $refValue; // refValue
	public $skybillValue; // skybillValue
	public $skybillParamsValue; // skybillParamsValue
	public $appointmentParamsValue; // appointmentParamsValue
	public $scheduledParamsValue; // scheduledParamsValue
	public $password; // string
	public $modeRetour; // string
}

class shippingWithReservationAndESDWithRefClientResponse
{
	public $return; // resultReservationExpeditionValue
}

class shippingWithReservationAndESDWithRefClientPC
{
	public $refEsdClient; // string
	public $retrievalDateTime; // string
	public $closingDateTime; // string
	public $specificInstructions; // string
	public $height; // string
	public $width; // string
	public $length; // string
	public $shipperCarriesCode; // string
	public $shipperBuildingFloor; // string
	public $shipperServiceDirection; // string
	public $nombreDePassageMaximum; // string
	public $ltAImprimerParChronopost; // string
	public $header_idEmit; // string
	public $accountNumber; // string
	public $subAccount; // string
	public $header_identWebPro; // string
	public $shipperCivility; // string
	public $shipperName; // string
	public $shipperName2; // string
	public $shipperAdress1; // string
	public $shipperAdress2; // string
	public $shipperZipCode; // string
	public $shipperCity; // string
	public $shipperCountry; // string
	public $shipperCountryName; // string
	public $shipperContactName; // string
	public $shipperEmail; // string
	public $shipperPhone; // string
	public $shipperMobilePhone; // string
	public $customerCivility; // string
	public $customerName; // string
	public $customerName2; // string
	public $customerAdress1; // string
	public $customerAdress2; // string
	public $customerZipCode; // string
	public $customerCity; // string
	public $customerCountry; // string
	public $customerCountryName; // string
	public $customerContactName; // string
	public $customerEmail; // string
	public $customerPhone; // string
	public $customerMobilePhone; // string
	public $customerPreAlert; // string
	public $recipientCivility; // string
	public $recipientName; // string
	public $recipientName2; // string
	public $recipientAdress1; // string
	public $recipientAdress2; // string
	public $recipientZipCode; // string
	public $recipientCity; // string
	public $recipientCountry; // string
	public $recipientCountryName; // string
	public $recipientContactName; // string
	public $recipientEmail; // string
	public $recipientPhone; // string
	public $recipientMobilePhone; // string
	public $recipientPreAlert; // string
	public $shipperRef; // string
	public $recipientRef; // string
	public $customerSkybillNumber; // string
	public $evtCode; // string
	public $productCode; // string
	public $shipDate; // string
	public $shipHour; // string
	public $weight; // string
	public $weightUnit; // string
	public $insuredValue; // string
	public $insuredCurrency; // string
	public $codValue; // string
	public $codCurrency; // string
	public $customsValue; // string
	public $customsCurrency; // string
	public $service; // string
	public $objectType; // string
	public $content1; // string
	public $content2; // string
	public $content3; // string
	public $content4; // string
	public $content5; // string
	public $portValue; // string
	public $portCurrency; // string
	public $skybillRank; // string
	public $bulkNumber; // string
	public $mode; // string
	public $password; // string
	public $modeRetour; // string

}

class shippingWithReservationAndESDWithRefClientPCResponse
{
	public $return; // resultReservationExpeditionValue
}

class shippingWithESDOnly
{
	public $esdValue; // esdWithRefClientValue
	public $headerValue; // headerValue
	public $shipperValue; // shipperValue
	public $customerValue; // customerValue
	public $recipientValue; // recipientValue
	public $refValue; // refValue
	public $skybillValue; // skybillValue
	public $skybillParamsValue; // skybillParamsValue
	public $password; // string
	public $modeRetour; // string
}

class shippingWithESDOnlyResponse
{
	public $return; // resultReservationExpeditionValue
}

/**
 * ShippingServiceWSService class
 */
class ShippingServiceWSService extends SoapClient
{
	protected $password = false;
	protected $accountNumber = false;

	private static $classmap = array(
		'resultGetReservedSkybillWithTypeValue' => 'resultGetReservedSkybillWithTypeValue',
		'esdValue' => 'esdValue',
		'headerValue' => 'headerValue',
		'shipperValue' => 'shipperValue',
		'customerValue' => 'customerValue',
		'recipientValue' => 'recipientValue',
		'refValue' => 'refValue',
		'skybillValue' => 'skybillValue',
		'skybillParamsValue' => 'skybillParamsValue',
		'appointmentParamsValue' => 'appointmentParamsValue',
		'scheduledParamsValue' => 'scheduledParamsValue',
		'resultExpeditionValue' => 'resultExpeditionValue',
		'resultGetReservedSkybillValue' => 'resultGetReservedSkybillValue',
		'resultReservationExpeditionValue' => 'resultReservationExpeditionValue',
		'esdWithRefClientValue' => 'esdWithRefClientValue',
		'getReservedSkybillWithType' => 'getReservedSkybillWithType',
		'getReservedSkybillWithTypeResponse' => 'getReservedSkybillWithTypeResponse',
		'shipping' => 'shipping',
		'shippingResponse' => 'shippingResponse',
		'getReservedSkybill' => 'getReservedSkybill',
		'getReservedSkybillResponse' => 'getReservedSkybillResponse',
		'shippingWithReservation' => 'shippingWithReservation',
		'shippingWithReservationResponse' => 'shippingWithReservationResponse',
		'shippingWithReservationAndESDWithRefClient' => 'shippingWithReservationAndESDWithRefClient',
		'shippingWithReservationAndESDWithRefClientResponse' => 'shippingWithReservationAndESDWithRefClientResponse',
		'shippingWithReservationAndESDWithRefClientPC' => 'shippingWithReservationAndESDWithRefClientPC',
		'shippingWithReservationAndESDWithRefClientPCResponse' => 'shippingWithReservationAndESDWithRefClientPCResponse',
		'shippingWithESDOnly' => 'shippingWithESDOnly',
		'shippingWithESDOnlyResponse' => 'shippingWithESDOnlyResponse',
	);

	public function ShippingServiceWSService($wsdl = "https://www.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl", $options = array('soap_version' => SOAP_1_1, 'trace' => 1))
	{
		foreach (self::$classmap as $key => $value) {
			if (!isset($options['classmap'][$key])) {
				$options['classmap'][$key] = $value;
			}
		}
		parent::__construct($wsdl, $options);
	}

	/**
	 *
	 *
	 * @param getReservedSkybillWithType $parameters
	 * @return getReservedSkybillWithTypeResponse
	 */
	public function getReservedSkybillWithType(getReservedSkybillWithType $parameters)
	{
		return $this->__soapCall('getReservedSkybillWithType', array($parameters), array(
				'uri' => 'http://cxf.shipping.soap.chronopost.fr/',
				'soapaction' => ''
				)
		);
	}

	/**
	 *
	 *
	 * @param shipping $parameters
	 * @return shippingResponse
	 */
	public function shipping(shipping $parameters)
	{
		$this->password = $parameters->password;
		$this->accountNumber = $parameters->accountNumber;

		return $this->__soapCall(
			'shipping',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(),
			array(
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'password', $this->password, false),
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'accountNumber', $this->accountNumber, false)
			)
		);
	}

	/**
	 *
	 *
	 * @param getReservedSkybill $parameters
	 * @return getReservedSkybillResponse
	 */
	public function getReservedSkybill(getReservedSkybill $parameters)
	{
		return $this->__soapCall('getReservedSkybill', array($parameters), array(
				'uri' => 'http://cxf.shipping.soap.chronopost.fr/',
				'soapaction' => ''
				)
		);
	}

	/**
	 *
	 *
	 * @param shippingWithReservation $parameters
	 * @return shippingWithReservationResponse
	 */
	public function shippingWithReservation(shippingWithReservation $parameters)
	{
		return $this->__soapCall('shippingWithReservation', array($parameters), array(
				'uri' => 'http://cxf.shipping.soap.chronopost.fr/',
				'soapaction' => ''
				)
		);
	}

	/**
	 *
	 *
	 * @param shippingWithReservationAndESDWithRefClient $parameters
	 * @return shippingWithReservationAndESDWithRefClientResponse
	 */
	public function shippingWithReservationAndESDWithRefClient(shippingWithReservationAndESDWithRefClient $parameters)
	{
		$this->password = $parameters->password;

		return $this->__soapCall(
			'shippingWithReservationAndESDWithRefClient',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(),
			array(
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'password', $this->password, false)
			)
		);
	}

	/**
	 *
	 *
	 * @param shippingWithReservationAndESDWithRefClientPC $parameters
	 * @return shippingWithReservationAndESDWithRefClientPCResponse
	 */
	public function shippingWithReservationAndESDWithRefClientPC(shippingWithReservationAndESDWithRefClientPC $parameters)
	{
		$this->password = $parameters->password;
		$this->accountNumber = $parameters->accountNumber;

		return $this->__call(
			'shippingWithReservationAndESDWithRefClientPC',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(),
			array(
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'password', $this->password, false),
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'accountNumber', $this->accountNumber, false)
			)
		);
	}

	/**
	 *
	 *
	 * @param shippingWithESDOnly $parameters
	 * @return shippingWithESDOnlyResponse
	 */
	public function shippingWithESDOnly(shippingWithESDOnly $parameters)
	{
		$this->password = $parameters->password;
		$this->accountNumber = $parameters->accountNumber;

		return $this->__soapCall(
			'shippingWithESDOnly',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(),
			array(
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'password', $this->password, false),
				new SoapHeader('http://cxf.shipping.soap.chronopost.fr/', 'accountNumber', $this->accountNumber, false)
			)
		);
	}

}
