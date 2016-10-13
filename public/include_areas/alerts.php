<?
use MSergeev\Core\Lib;
use MSergeev\Packages\Icar\Lib AS CarLib;
$path = Lib\Tools::getSitePath(Lib\Loader::getPublic("icar"));
//Lib\Loc::setModuleMessages('icar');
$arAlerts = CarLib\MyCar::checkAlerts();
//msDebug($arAlerts);
?>
<?foreach ($arAlerts as $alert):?>
	<div class="alert_<?=$alert['COLOR']?>"><?=$alert['TEXT']?></div>
<?endforeach;?>
<style>
	.alert_green {
		width: 95%;
		margin: 5px;
		padding: 10px;
		border: 1px solid darkgreen;
		background: lightgreen;
		color: darkgreen;
		text-align: justify;
	}
	.alert_yellow {
		width: 95%;
		margin: 5px;
		padding: 10px;
		border: 1px solid orangered;
		background: lightyellow;
		color: orangered;
		text-align: justify;
	}
	.alert_red {
		width: 95%;
		margin: 5px;
		padding: 10px;
		border: 1px solid darkred;
		background: lightpink;
		color: darkred;
		text-align: justify;
	}
</style>


