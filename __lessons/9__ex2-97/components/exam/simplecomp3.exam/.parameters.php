<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("NEWS_IBLOCK_ID"),
			"TYPE" => "STRING",
		),
        "AUTHOR_CODE" => array(
            "NAME" => GetMessage("AUTHOR_CODE"),
            "TYPE" => "STRING",
        ),
        "USER_AUTHOR_TYPE_CODE" => array(
            "NAME" => GetMessage("USER_AUTHOR_TYPE_CODE"),
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>3600000),
	),
);