<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
        "CATALOG_IBLOCK_ID"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("CATALOG_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "MANUF_IBLOCK_ID"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("MANUF_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "DETAIL_LINK"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("DETAIL_LINK"),
            "TYPE" => "STRING",
        ),
        "MANUF_PROPERTY_CODE"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("MANUF_PROPERTY_CODE"),
            "TYPE" => "STRING",
        ),
		"CACHE_TIME"  =>  Array("DEFAULT"=>300),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);
?>
