<?php
/* Generated on 14.02.18 14:28 by globalsync
 * $Id: $
 * $Log: $
 */

require_once 'EbatNs_FacetType.php';

class ListingStatusCodeType extends EbatNs_FacetType
{
	const CodeType_Active = 'Active';
	const CodeType_Ended = 'Ended';
	const CodeType_Completed = 'Completed';
	const CodeType_CustomCode = 'CustomCode';
	const CodeType_Custom = 'Custom';

	/**
	 * @return 
	 **/
	function __construct()
	{
		parent::__construct('ListingStatusCodeType', 'urn:ebay:apis:eBLBaseComponents');
	}
}
$Facet_ListingStatusCodeType = new ListingStatusCodeType();
?>