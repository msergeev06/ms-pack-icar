<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Options;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','fuel_title')." - ".CoreLib\Loc::getPackMessage('icar','fuel_title_add'));

if (isset($_REQUEST['car']) && intval($_REQUEST['car'])>0)
{
	$carID = intval($_REQUEST['car']);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}

if (!$fuelMarkSelected = Options::getOptionInt('icar_last_fuelmark_'.$carID))
{
	$fuelMarkSelected = 'null';
}

if (isset($_POST['date']))
{
	$date = $_POST['date'];
}
else
{
	$date = date('d.m.Y');
}


if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	if (Lib\Fuel::addFuelFromPost($_POST)) {
		?><div class="text-success"><?=CoreLib\Loc::getPackMessage('icar','all_add_success')?></div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'fuel/',3);
	}
	else {
		?><div class="text-danger"><?=CoreLib\Loc::getPackMessage('icar','all_add_error')?></div><?
	}
}
//msDebug(Lib\Odo::getMaxOdo($carID));
?>
<form class="form-horizontal" role="form" name="fuel_add" method="post" action="" enctype="multipart/form-data">
	<?=Lib\Fields::showCarIdField($carID)?>
	<?=Lib\Fields::showDateField($date)?>
	<?=Lib\Fields::showOdoField()?>
	<?=Lib\Fields::showFuelMarksField($fuelMarkSelected)?>
	<?=Lib\Fields::showLitersField()?>
	<?=Lib\Fields::showLiterCostField()?>
	<?=Lib\Fields::showFullTankField()?>
	<?=Lib\Fields::showMissingPrevFuel()?>
	<?//=Lib\Fields::showStartPointField('null',true,false,'','fuel')?>
	<?=Lib\Fields::showMobileGpsField('fuel')?>
	<?=Lib\Fields::showCommentField()?>
	<?=Lib\Fields::showCheckField()?>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button>
		</div>
	</div>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
