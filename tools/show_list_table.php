<?php
include_once ($_SERVER["DOCUMENT_ROOT"]."/msergeev_config.php");
MSergeev\Core\Lib\Loader::IncludePackage("icar");
use \MSergeev\Core\Exception;
use \MSergeev\Packages\Icar\Lib;

$arParams = $arReturn = array();
$arReturn['status'] = 'ok';
$bStatus = true;

//Проверка переданных полей
if (true)
{

}



header('Content-Type: application/json');
echo json_encode($arReturn);



