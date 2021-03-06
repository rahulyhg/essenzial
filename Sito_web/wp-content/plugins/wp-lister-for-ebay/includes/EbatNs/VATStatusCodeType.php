<?php
/* Generated on 14.02.18 14:28 by globalsync
 * $Id: $
 * $Log: $
 */

require_once 'EbatNs_FacetType.php';

class VATStatusCodeType extends EbatNs_FacetType
{
	const CodeType_NoVATTax = 'NoVATTax';
	const CodeType_VATTax = 'VATTax';
	const CodeType_VATExempt = 'VATExempt';
	const CodeType_CustomCode = 'CustomCode';

	/**
	 * @return 
	 **/
	function __construct()
	{
		parent::__construct('VATStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');
	}
}
$Facet_VATStatusCodeType = new VATStatusCodeType();
?>