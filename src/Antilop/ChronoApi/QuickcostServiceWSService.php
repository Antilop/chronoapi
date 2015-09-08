<?php

/**
 * resultQuickCost class
 */
//require_once 'resultQuickCost.php';
/**
 * service class
 */
require_once 'service.php';
/**
 * resultQuickCostV2 class
 */
require_once 'resultQuickCostV2.php';
/**
 * assurance class
 */
//require_once 'assurance.php';
/**
 * resultCalculateProducts class
 */
require_once 'resultCalculateProducts.php';
/**
 * product class
 */
//require_once 'product.php';
/**
 * quickCost class
 */
require_once 'quickCost.php';
/**
 * quickCostResponse class
 */
require_once 'quickCostResponse.php';
/**
 * calculateProducts class
 */
require_once 'calculateProducts.php';
/**
 * calculateProductsResponse class
 */
require_once 'calculateProductsResponse.php';

/**
 * QuickcostServiceWSService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class QuickcostServiceWSService extends SoapClient {

  public function QuickcostServiceWSService($wsdl = "https://www.chronopost.fr/quickcost-cxf/QuickcostServiceWS?wsdl", $options = array()) {
	parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param quickCost $parameters
   * @return quickCostResponse
   */
  public function quickCost(quickCost $parameters) {
	return $this->__call('quickCost', array(
			new SoapParam($parameters, 'parameters')
	  ),
	  array(
			'uri' => 'http://cxf.quickcost.soap.chronopost.fr/',
			'soapaction' => ''
		   )
	  );
  }

  /**
   *  
   *
   * @param calculateProducts $parameters
   * @return calculateProductsResponse
   */
  public function calculateProducts(calculateProducts $parameters) {
	return $this->__call('calculateProducts', array(
			new SoapParam($parameters, 'parameters')
	  ),
	  array(
			'uri' => 'http://cxf.quickcost.soap.chronopost.fr/',
			'soapaction' => ''
		   )
	  );
  }

}

?>
