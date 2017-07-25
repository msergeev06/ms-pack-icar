<? include_once(__DIR__."/../include/header.php");

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Icar\Lib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','other_title'));

$carID = (isset($_REQUEST['carID'])?intval($_REQUEST['carID']):Lib\MyCar::getDefaultCarID());

?>
<p><?=CoreLib\Loc::getPackMessage('icar','other_info1')?></p>
<p><?=CoreLib\Loc::getPackMessage('icar','other_info2')?></p>

<p><?=CoreLib\Loc::getPackMessage('icar','all_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
	<?=CoreLib\Loc::getPackMessage('icar','other_sum')?>: <? echo Lib\Statistics::getOtherExpenseTotalCostsFormatted(); ?> <?=CoreLib\Loc::getPackMessage('icar','all_rub')?><br><br></p>
<p><a href="add.php?car=<?=$carID?>"><button type="button" class="btn btn-primary"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button></a><br><br></p>

<? Lib\OtherExpense::showListTable($carID); ?>

<p><br><a href="add.php?car=<?=$carID?>"><button type="button" class="btn btn-primary"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button></a><br><br></p>




<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
