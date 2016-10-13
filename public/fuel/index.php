<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Топливо");

use MSergeev\Packages\Icar\Lib;
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
<p>Статистика для: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
Общие затраты на топливо: <? echo Lib\Fuel::getTotalFuelCostsFormatted($carID); ?> руб.<br>
Средний расход топлива: <? echo Lib\MyCar::getCarAverageFuelFormatted($carID); ?> л./100км.<br>
Всего израсходованно топлива: <? echo Lib\MyCar::getCarTotalSpentFuelFormatted($carID); ?> л.<br><br></p>
<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>

<? Lib\Fuel::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
