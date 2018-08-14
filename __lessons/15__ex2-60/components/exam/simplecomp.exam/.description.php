<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("MYCOMP_NAME"),
	"DESCRIPTION" => GetMessage("MYCOMP_DESCRIPTION"),
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "exam",
        "NAME" => GetMessage("MYCOMP_PATH"),
        "SORT" => 1,
	),
);

?>