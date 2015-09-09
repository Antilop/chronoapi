<?php

require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/PointRelaisServiceWSService.php');
require_once(dirname(__FILE__) . '/../src/Antilop/ChronoApi/Request/recherchePointChronopost.php');

use Antilop\ChronoApi\Request\recherchePointChronopost;

$account = $argv[1];
$passwd = $argv[2];

if (empty($account) || empty($passwd)) {
	die('Paramètres de connexion manquant');
}

$now = new DateTime('now', new DateTimeZone('Europe/Paris'));

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
$params->shippingDate = $now->format('d/m/Y');
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
