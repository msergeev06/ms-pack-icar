<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','fuel_title')." - ".CoreLib\Loc::getPackMessage('icar','fuel_title_edit'));

$fuelCostsID = intval($_REQUEST["id"]);

if(isset($_POST["action"])) {
	if ($res = Lib\Fuel::updateFuelFromPost($_POST)) {
		?><div class="text-success"><?=CoreLib\Loc::getPackMessage('icar','all_edit_success')?></div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'fuel/',3);
	}
	else {
		?><div class="text-danger"><?=CoreLib\Loc::getPackMessage('icar','all_edit_error')?></div><?
	}
}
	$arFuel = Lib\Fuel::getList(null,$fuelCostsID);
	$arFuel = $arFuel[0];
	//msDebug($arFuel);
?>
<form class="form-horizontal" role="form" name="fuel_edit" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?=$fuelCostsID?>">
	<?=Lib\Fields::showCarIdField($arFuel['MY_CAR_ID'])?>
	<?=Lib\Fields::showDateField($arFuel['DATE'])?>
	<?=Lib\Fields::showOdoField($arFuel['ODO'])?>
	<?=Lib\Fields::showFuelMarksField($arFuel['FUELMARK_ID'])?>
	<?=Lib\Fields::showLitersField($arFuel['LITER'])?>
	<?=Lib\Fields::showLiterCostField($arFuel['LITER_COST'])?>
	<?=Lib\Fields::showFullTankField($arFuel['FULL'])?>
	<?=Lib\Fields::showMissingPrevFuel($arFuel['MISSING'])?>
	<?=Lib\Fields::showStartPointField($arFuel['POINTS_ID'])?>
	<?=Lib\Fields::showCommentField($arFuel['INFO'])?>
	<?=Lib\Fields::showCheckField($arFuel['CHECK'])?>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_save')?></button>
		</div>
	</div>
</form>


<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
