<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("MYCOMP2_NAME"),
	"DESCRIPTION" => GetMessage("MYCOMP2_DESCRIPTION"),
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "exam",
        "NAME" => GetMessage("MYCOMP2_PATH"),
        "SORT" => 2,
	),
);
?>