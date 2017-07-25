<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Ремонт");

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

$carID = (isset($_REQUEST['carID'])?intval($_REQUEST['carID']):Lib\MyCar::getDefaultCarID());
//msDebug();

?>
<p><?=Loc::getPackMessage('icar','repair_info')?></p>

<p><?=Loc::getPackMessage('icar','all_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
<?=Loc::getPackMessage('icar','repair_sum')?>: <? echo Lib\Statistics::getRepairTotalCostsFormatted($carID); ?> <?=Loc::getPackMessage('icar','all_rub')?><br><br></p>
<p><a href="add.php?car=<?=$carID?>"><?=Loc::getPackMessage('icar','all_add')?></a><br><br></p>

<? Lib\Repair::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>"><?=Loc::getPackMessage('icar','all_add')?></a><br><br></p>



<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
