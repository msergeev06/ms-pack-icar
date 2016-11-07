<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

if (isset($_REQUEST['car']) && intval($_REQUEST['car'])>0)
{
	$carID = intval($_REQUEST['car']);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}

if (!isset($_REQUEST['page']))
{
	$page = 1;
}
else
{
	$page = intval($_REQUEST['page']);
}
?>
<p><?=Loc::getPackMessage('icar','fuel_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
<?=Loc::getPackMessage('icar','fuel_total_cost')?>: <? echo Lib\Fuel::getTotalFuelCostsFormatted($carID); ?> руб.<br>
<?=Loc::getPackMessage('icar','fuel_average')?>: <? echo Lib\MyCar::getCarAverageFuelFormatted($carID); ?> л./100км.<br>
<?=Loc::getPackMessage('icar','fuel_total')?>: <? echo Lib\MyCar::getCarTotalSpentFuelFormatted($carID); ?> л.<br><br></p>
<p><a href="add.php?car=<?=$carID?>"><?=Loc::getPackMessage('icar','fuel_add')?></a><br><br></p>

<? Lib\Fuel::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>"><?=Loc::getPackMessage('icar','fuel_add')?></a><br><br></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
