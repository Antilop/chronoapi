<?php

namespace Antilop\ChronoApi\Request;

class searchDeliverySlot {

	public $accountNumber;

	public $password;

	public $callerTool = 'RDVWS';

	public $productType;

	public $shipperAdress1;

	public $shipperAdress2;

	public $shipperZipCode;

	public $shipperCity;

	public $shipperCountry;

	public $recipientAdress1;

	public $recipientAdress2;

	public $recipientZipCode;

	public $recipientCountry;

	public $dateBegin;

	public $dateEnd;

	public $weight;

	public $shipperDeliverySlotClosed;

	public $currency;

	public $rateN1;

	public $rateN2;

	public $rateN3;

	public $rateN4;

	public $rateLevelsNotShow;

	public $isDeliveryDate;
}
