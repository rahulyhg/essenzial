<?php
/* Generated on 14.02.18 14:28 by globalsync
 * $Id: $
 * $Log: $
 */

require_once 'EbatNs_FacetType.php';

class GetAllBiddersModeCodeType extends EbatNs_FacetType
{
	const CodeType_ViewAll = 'ViewAll';
	const CodeType_EndedListing = 'EndedListing';
	const CodeType_SecondChanceEligibleEndedListing = 'SecondChanceEligibleEndedListing';
	const CodeType_CustomCode = 'CustomCode';

	/**
	 * @return 
	 **/
	function __construct()
	{
		parent::__construct('GetAllBiddersModeCodeType', 'urn:ebay:apis:eBLBaseComponents');
	}
}
$Facet_GetAllBiddersModeCodeType = new GetAllBiddersModeCodeType();
?>