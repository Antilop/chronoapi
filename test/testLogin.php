<?php

require_once(dirname(__FILE__) . '/../../../../../../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../../../../../../init.php');
require_once(dirname(__FILE__) . '/../PointRelaisServiceWSService.php');
require_once(dirname(__FILE__) . '/../Request/recherchePointChronopost.php');

use Antilop\ChronoApi\Request\recherchePointChronopost;

$account = Configuration::get('CHRONOAPI_ACCOUNT');
$passwd = Configuration::get('CHRONOAPI_PASSWD');

if (empty($account) || empty($passwd)) {
	die('Paramètres de connexion manquant');
}

$service = new PointRelaisServiceWSService();


$params = new recherchePointChronopost();
$params->zipCode = '75009';
$params->accountNumber = $account;
$params->password = $passwd;
$params->address = '46 rue de douai';
$params->city = 'paris';
$params->countryCode = 'FR';
$params->type = 'P';
$params->service = 'L';
$params->weight = 0;
$params->shippingDate = date('d/m/Y');
$params->maxPointChronopost = 5;
$params->maxDistanceSearch = 40;
$params->holidayTolerant = 1;

$res = $service->recherchePointChronopost($params)->return;

if ($res->errorCode == 0) {
	echo 'Connexion réussie !';
} elseif ($res->errorCode == 3){
	echo 'Le nom d\'utilisateur ou le mot de passe saisi est incorrect.';
} else {
	echo $res->errorMessage;
}
