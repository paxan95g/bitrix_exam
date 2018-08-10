<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("MYCOMP3_NAME"),
    "DESCRIPTION" => GetMessage("MYCOMP3_DESCRIPTION"),
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "exam",
        "NAME" => GetMessage("MYCOMP3_PATH"),
        "SORT" => 3,
    ),
);
?>
