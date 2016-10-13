<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Запчасти"); ?>
<?
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

	$carID = Lib\MyCar::getDefaultCarID();
?>
<p>В данный раздел вносите запчасти, <b>технические жидкости (Моторные масла, антифризы, тормозные жидкости)</b> и
	прочие расходные материалы. Указывайте каталожный номер если Вы его знаете. Потом будет легче искать нужную деталь
	для покупки.<br>Стоимость плановых ТО указывайте в разделе <a href="<?=$path?>ts/">ТО</a>, а стоимость прочего
	ремонта в разделе <a href="<?=$path?>other/">Прочее</a>.</p>
<p>Информация для: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
Общие затраты на запчасти: <? echo Lib\RepairParts::getTotalRepairPartsCostsFormatted(); ?> руб.<br><br></p>
<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>

<? Lib\RepairParts::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
