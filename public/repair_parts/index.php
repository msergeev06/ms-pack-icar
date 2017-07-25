<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','rp_title')); ?>
<?
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Lib\Loc;

	$carID = Lib\MyCar::getDefaultCarID();
?>
<?=Loc::getPackMessage('icar','rp_notice',array(
	'TS_URL'=>$path.'ts/',
	'TS_TEXT'=>Loc::getPackMessage('icar','rp_ts'),
	'OTHER_URL'=>$path.'other/',
	'OTHER_TEXT'=>Loc::getPackMessage('icar','rp_other')
))?>
<p><?=Loc::getPackMessage('icar','all_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
<?=Loc::getPackMessage('icar','rp_total_cost')?>: <? echo Lib\Statistics::getRepairPartsTotalCostsFormatted($carID); ?> <?=Loc::getPackMessage('icar','rp_rub')?><br><br></p>
<p><a href="add.php?car=<?=$carID?>"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','all_add')?></button></a><br><br></p>

<? Lib\RepairParts::showListTable($carID); ?>

<p><br><a href="add.php?car=<?=$carID?>"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','all_add')?></button></a><br><br></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
