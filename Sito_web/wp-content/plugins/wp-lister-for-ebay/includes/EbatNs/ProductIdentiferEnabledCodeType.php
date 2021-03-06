<?php
/* Generated on 14.02.18 14:28 by globalsync
 * $Id: $
 * $Log: $
 */

require_once 'EbatNs_FacetType.php';

class ProductIdentiferEnabledCodeType extends EbatNs_FacetType
{
	const CodeType_Disabled = 'Disabled';
	const CodeType_Enabled = 'Enabled';
	const CodeType_Required = 'Required';
	const CodeType_CustomCode = 'CustomCode';

	/**
	 * @return 
	 **/
	function __construct()
	{
		parent::__construct('ProductIdentiferEnabledCodeType', 'urn:ebay:apis:eBLBaseComponents');
	}
}
$Facet_ProductIdentiferEnabledCodeType = new ProductIdentiferEnabledCodeType();
?>