<?php

use Antilop\ChronoApi\Request\recherchePointChronopost;
use Antilop\ChronoApi\Request\recherchePointChronopostResponse;

class PointRelaisServiceWSService extends SoapClient
{

	public function PointRelaisServiceWSService($wsdl = "https://www.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl", $options = array())
	{
		parent::__construct($wsdl, $options);
	}

	/**
	 * @param rechercheDetailPointChronopost $parameters
	 * @return rechercheDetailPointChronopostResponse
	 */
	public function rechercheDetailPointChronopost(rechercheDetailPointChronopost $parameters)
	{
		return $this->__call(
			'rechercheDetailPointChronopost',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}
}
