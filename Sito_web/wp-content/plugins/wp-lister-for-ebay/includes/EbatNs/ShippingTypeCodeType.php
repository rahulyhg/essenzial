<?php
/* Generated on 14.02.18 14:28 by globalsync
 * $Id: $
 * $Log: $
 */

require_once 'EbatNs_FacetType.php';

class ShippingTypeCodeType extends EbatNs_FacetType
{
	const CodeType_Flat = 'Flat';
	const CodeType_Calculated = 'Calculated';
	const CodeType_Freight = 'Freight';
	const CodeType_Free = 'Free';
	const CodeType_NotSpecified = 'NotSpecified';
	const CodeType_FlatDomesticCalculatedInternational = 'FlatDomesticCalculatedInternational';
	const CodeType_CalculatedDomesticFlatInternational = 'CalculatedDomesticFlatInternational';
	const CodeType_FreightFlat = 'FreightFlat';
	const CodeType_CustomCode = 'CustomCode';

	/**
	 * @return 
	 **/
	function __construct()
	{
		parent::__construct('ShippingTypeCodeType', 'urn:ebay:apis:eBLBaseComponents');
	}
}
$Facet_ShippingTypeCodeType = new ShippingTypeCodeType();
?>