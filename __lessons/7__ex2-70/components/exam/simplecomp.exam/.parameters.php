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
        "NEWS_IBLOCK_ID"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("NEWS_IBLOCK_ID"),
            "TYPE" => "STRING",
        ),
        "PROPERTY_CODE"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("PROPERTY_CODE"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>300),
	),
);
?>
