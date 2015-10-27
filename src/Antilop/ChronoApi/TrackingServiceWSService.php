<?php

namespace Antilop\ChronoApi;

use SoapClient;
use SoapParam;
use SoapHeader;

use Antilop\ChronoApi\Request\cancelSkybill;
use Antilop\ChronoApi\Request\cancelSkybillResponse;

/**
 * TrackingServiceWSService class
 */
class TrackingServiceWSService extends SoapClient
{
	public function __construct($wsdl = 'https://www.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl', $options = array('soap_version' => SOAP_1_1, 'trace' => 1, 'encoding' => 'UTF-8'))
	{
		parent::__construct($wsdl, $options);
	}

	/**
	 *
	 *
	 * @param cancelSkybill $parameters
	 * @return cancelSkybillResponse
	 */
	public function cancelSkybill(cancelSkybill $parameters)
	{
		$this->password = $parameters->password;
		$this->accountNumber = $parameters->accountNumber;

		return $this->__soapCall(
			'cancelSkybill',
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
