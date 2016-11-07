<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Ремонт");

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

$carID = (isset($_REQUEST['carID'])?intval($_REQUEST['carID']):Lib\MyCar::getDefaultCarID());
//msDebug();

?>
<p>В данном разделе Вы можете указывать все расходы на ремонтные работы (Покраска, диагностика, регулировка, шиномонтаж).<br>
	Запчасти для проведения ремонта указывайте в разделе "<a href="<?=$path?>repair_parts/">Запчасти</a>".</p>

<p>Информация для: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
Всего расходов на ремонт: <? echo Lib\Repair::getTotalRepairCostsFormatted(); ?> <?=Loc::getPackMessage('icar','rp_rub')?><br><br></p>
<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>

<?// Lib\RepairParts::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>



<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
