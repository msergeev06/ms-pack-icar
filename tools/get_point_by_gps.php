<?php
include_once ($_SERVER["DOCUMENT_ROOT"]."/msergeev_config.php");
MSergeev\Core\Lib\Loader::IncludePackage("icar");
use \MSergeev\Core\Exception;
use \MSergeev\Packages\Icar\Lib;

$arParams = $arReturn = array();
$arReturn['status'] = 'new';
$bStatus = true;
//Проверка переданных полей
if (true)
{
	try
	{
		if (isset($_POST['gps']) && strlen($_POST['gps'])>0)
		{
			$arParams['GPS'] = htmlspecialchars($_POST['gps']);
		}
		else
		{
			throw new Exception\ArgumentNullException('gps');
		}
		if (!isset($_POST['pointType']))
		{
			throw new Exception\ArgumentNullException('pointType');
		}
		else
		{
			$arParams['pointType'] = htmlspecialchars($_POST['pointType']);
		}
	}
	catch (Exception\ArgumentNullException $e)
	{
		$e->showException();
		$arReturn['status'] = 'error';
		$bStatus = false;
	}
}

if ($bStatus)
{
	if ($arPoint = Lib\Points::getGpsFromString($arParams['GPS'],$arParams['pointType']))
	{
		$arReturn['point_id'] = $arPoint['ID'];
		$arReturn['status'] = 'point';
	}
	else
	{
		list($gps,$net) = explode('/',$arParams['GPS']);
		if ($gps!='%LOC')
		{
			list($lat,$lon) = explode(',',$gps);
			$arReturn['lat'] = $lat;
			$arReturn['lon'] = $lon;
		}
		elseif ($net!='%LOCN')
		{
			list($lat,$lon) = explode(',',$net);
			$arReturn['lat'] = $lat;
			$arReturn['lon'] = $lon;
		}
		else
		{
			$arReturn['status'] = 'error';
		}
	}
}

header('Content-Type: application/json');
echo json_encode($arReturn);



