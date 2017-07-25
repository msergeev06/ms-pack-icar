<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','add_title'));

$carID = Lib\MyCar::getDefaultCarID();
?>
<p><?=CoreLib\Loc::getPackMessage('icar','add_info')?></p>
<?/*=CoreLib\Loc::getPackMessage('icar','rp_notice',array(
	'TS_URL'=>$path.'ts/',
	'TS_TEXT'=>Loc::getPackMessage('icar','rp_ts'),
	'OTHER_URL'=>$path.'other/',
	'OTHER_TEXT'=>Loc::getPackMessage('icar','rp_other')
))*/?>
<p><?=CoreLib\Loc::getPackMessage('icar','all_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
	<?=CoreLib\Loc::getPackMessage('icar','add_sum')?>: <? echo Lib\Statistics::getOptionalEquipTotalCostsFormatted(); ?> <?=CoreLib\Loc::getPackMessage('icar','rp_rub')?><br><br></p>
<p><a href="add.php?car=<?=$carID?>"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></a><br><br></p>

<? Lib\OptionalEquip::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></a><br><br></p>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
