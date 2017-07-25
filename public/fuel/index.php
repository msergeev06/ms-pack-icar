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
<p><?=Loc::getPackMessage('icar','all_statistic_for')?>: <?=Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"')?><br>
<?=Loc::getPackMessage('icar','fuel_total_cost')?>: <?=Lib\Statistics::getCarTotalCostsFormatted($carID,array('fuel'))?><br>
<?=Loc::getPackMessage('icar','fuel_average')?>: <?=Lib\Statistics::getCarAverageFuelFormatted($carID)?><br>
<?=Loc::getPackMessage('icar','fuel_total')?>: <?=Lib\Statistics::getCarTotalSpentFuelFormatted($carID)?><br><br></p>
<p><a href="add.php?car=<?=$carID?>"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','all_add')?></button></a><br><br></p>

<? Lib\Fuel::showListTable($carID); ?>

<p><br><br><a href="add.php?car=<?=$carID?>"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','all_add')?></button></a><br><br></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
