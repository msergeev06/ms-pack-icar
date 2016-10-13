<?php
include_once ($_SERVER["DOCUMENT_ROOT"]."/msergeev_config.php");
MSergeev\Core\Lib\Loader::IncludePackage("icar");
use \MSergeev\Core\Exception;
use \MSergeev\Packages\Icar\Lib\CarModel;

$arParams = $arReturn = array();
$arReturn['status'] = 'ok';
$bStatus = true;
//Проверка переданных полей
if (true)
{
	try
	{
		if (isset($_REQUEST['brand_id']) && intval($_REQUEST['brand_id'])>0)
		{
			$arParams['BRAND_ID'] = intval($_REQUEST['brand_id']);
		}
		else
		{
			throw new Exception\ArgumentNullException('brand_id');
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
	$arReturn['select'] = CarModel::getHtmlSelect($arParams['BRAND_ID']);
}

header('Content-Type: application/json');
echo json_encode($arReturn);



