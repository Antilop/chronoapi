<?php

use Antilop\ChronoApi\Request\pointChronopost;
use Antilop\ChronoApi\Request\bureauDeTabac;
use Antilop\ChronoApi\Request\bureauDeTabacAvecCoord;
use Antilop\ChronoApi\Request\bureauDeTabacAvecPF;
use Antilop\ChronoApi\Request\pointCHRResult;
use Antilop\ChronoApi\Request\pointCHR;
use Antilop\ChronoApi\Request\listeHoraireOuverturePourUnJour;
use Antilop\ChronoApi\Request\horaireOuverture;
use Antilop\ChronoApi\Request\periodeFermeture;
use Antilop\ChronoApi\Request\tourneeResult;
use Antilop\ChronoApi\Request\tournee;
use Antilop\ChronoApi\Request\recherchePointChronopostParId;
use Antilop\ChronoApi\Request\recherchePointChronopostParIdResponse;
use Antilop\ChronoApi\Request\rechercheBtParIdChronopostA2Pas;
use Antilop\ChronoApi\Request\rechercheBtParIdChronopostA2PasResponse;
use Antilop\ChronoApi\Request\rechercheBtAvecPFParIdChronopostA2Pas;
use Antilop\ChronoApi\Request\rechercheBtAvecPFParIdChronopostA2PasResponse;
use Antilop\ChronoApi\Request\rechercheDetailPointChronopost;
use Antilop\ChronoApi\Request\rechercheDetailPointChronopostResponse;
use Antilop\ChronoApi\Request\rechercheTournee;
use Antilop\ChronoApi\Request\rechercheTourneeResponse;
use Antilop\ChronoApi\Request\recherchePointChronopostParCoordonneesGeographiques;
use Antilop\ChronoApi\Request\recherchePointChronopostParCoordonneesGeographiquesResponse;
use Antilop\ChronoApi\Request\rechercheBtAvecPFParCodeproduitEtCodepostalEtDate;
use Antilop\ChronoApi\Request\rechercheBtAvecPFParCodeproduitEtCodepostalEtDateResponse;
use Antilop\ChronoApi\Request\recherchePointChronopost;
use Antilop\ChronoApi\Request\recherchePointChronopostResponse;
use Antilop\ChronoApi\Request\rechercheBtParCodeproduitEtCodepostalEtDate;
use Antilop\ChronoApi\Request\rechercheBtParCodeproduitEtCodepostalEtDateResponse;

class PointRelaisServiceWSService extends SoapClient
{

	public function PointRelaisServiceWSService($wsdl = "https://www.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl", $options = array())
	{
		parent::__construct($wsdl, $options);
	}

	/**
	 * @param recherchePointChronopostParId $parameters
	 * @return recherchePointChronopostParIdResponse
	 */
	public function recherchePointChronopostParId(recherchePointChronopostParId $parameters)
	{
		return $this->__call(
			'recherchePointChronopostParId',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}

	/**
	 * @param rechercheBtAvecPFParIdChronopostA2Pas $parameters
	 * @return rechercheBtAvecPFParIdChronopostA2PasResponse
	 */
	public function rechercheBtAvecPFParIdChronopostA2Pas(rechercheBtAvecPFParIdChronopostA2Pas $parameters)
	{
		return $this->__call(
			'rechercheBtAvecPFParIdChronopostA2Pas',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}

	/**
	 * @param rechercheBtParIdChronopostA2Pas $parameters
	 * @return rechercheBtParIdChronopostA2PasResponse
	 */
	public function rechercheBtParIdChronopostA2Pas(rechercheBtParIdChronopostA2Pas $parameters)
	{
		return $this->__call(
			'rechercheBtParIdChronopostA2Pas',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
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

	/**
	 * @param rechercheTournee $parameters
	 * @return rechercheTourneeResponse
	 */
	public function rechercheTournee(rechercheTournee $parameters)
	{
		return $this->__call(
			'rechercheTournee',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}

	/**
	 * @param recherchePointChronopostParCoordonneesGeographiques $parameters
	 * @return recherchePointChronopostParCoordonneesGeographiquesResponse
	 */
	public function recherchePointChronopostParCoordonneesGeographiques(recherchePointChronopostParCoordonneesGeographiques $parameters)
	{
		return $this->__call(
			'recherchePointChronopostParCoordonneesGeographiques',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}

	/**
	 * @param rechercheBtAvecPFParCodeproduitEtCodepostalEtDate $parameters
	 * @return rechercheBtAvecPFParCodeproduitEtCodepostalEtDateResponse
	 */
	public function rechercheBtAvecPFParCodeproduitEtCodepostalEtDate(rechercheBtAvecPFParCodeproduitEtCodepostalEtDate $parameters)
	{
		return $this->__call(
			'rechercheBtAvecPFParCodeproduitEtCodepostalEtDate',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}

	/**
	 * @param rechercheBtParCodeproduitEtCodepostalEtDate $parameters
	 * @return rechercheBtParCodeproduitEtCodepostalEtDateResponse
	 */
	public function rechercheBtParCodeproduitEtCodepostalEtDate(rechercheBtParCodeproduitEtCodepostalEtDate $parameters)
	{
		return $this->__call(
			'rechercheBtParCodeproduitEtCodepostalEtDate',
			array(
				new SoapParam($parameters, 'parameters')
			),
			array(
				'uri' => 'http://cxf.rechercheBt.soap.chronopost.fr/',
				'soapaction' => ''
			)
		);
	}

	/**
	 * @param recherchePointChronopost $parameters
	 * @return recherchePointChronopostResponse
	 */
	public function recherchePointChronopost(recherchePointChronopost $parameters)
	{
		return $this->__call(
			'recherchePointChronopost',
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
