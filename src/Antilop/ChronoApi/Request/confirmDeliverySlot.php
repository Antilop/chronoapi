<?php

namespace Antilop\ChronoApi\Request;

class confirmDeliverySlot {

	public $transactionID;

	public $accountNumber;

	public $password;

	public $callerTool = 'RDVWS';

	public $productType;

	public $codeSlot;

	public $meshCode;

	public $rank;

	public $position;

	public $dateSelected;

}

