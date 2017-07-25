<?php
include_once ($_SERVER["DOCUMENT_ROOT"]."/msergeev_config.php");
MSergeev\Core\Lib\Loader::IncludePackage("icar");
header('Content-type: text/html; charset=utf-8');

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Lib;

CoreLib\Buffer::start("page");
?>
	<!DOCTYPE html>
<html>
	<head>
		<title>Расходы на авто - <?=CoreLib\Buffer::showTitle("Пробег");?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?=CoreLib\Buffer::showCSS()?>
		<?=CoreLib\Buffer::showJS()?>
	</head>
	<body>
<?

CoreLib\Buffer::addCSS(CoreLib\Loader::getTemplate("icar")."css/style.css");
CoreLib\Buffer::addJS(CoreLib\Config::getConfig("CORE_ROOT")."js/jquery-1.11.3.min.js");
CoreLib\Buffer::addJS(CoreLib\Loader::getTemplate("icar")."js/script.js");
$path=CoreLib\Loader::getSitePublic('icar');

?>

	<?
	$dateHelper = new CoreLib\DateHelper();
	if (isset($_GET['sel']))
	{
		if ($_GET['sel'] == 1)
		{
			$from = '01.'.date('m.Y');
			$to = date('d.m.Y');
		}
		elseif ($_GET['sel']== 2)
		{
			$from = '01.'.date('m.Y',$dateHelper->strToTime('01.'.date('m.Y'),'-1 month','site','time'));
			$to = date('t').'.'.date('m.Y',$dateHelper->strToTime('01.'.date('m.Y'),'-1 month','site','time'));
		}
		else
		{
			$from = '01.01.'.date('Y');
			$to = date('d.m.Y');
		}
	}
	else
	{
		$from = $dateHelper->convertDateFromDB($_GET['from']);
		$to = $dateHelper->convertDateFromDB($_GET['to']);
	}
	if ($echo = Lib\Odo::showChartsOdo($from, $to, intval($_GET['car']))) echo $echo;


echo CoreLib\Buffer::showWebixJS();
?>
	</body>
</html>

<?

CoreLib\Buffer::end();

?>


