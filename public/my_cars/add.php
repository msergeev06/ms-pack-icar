<? include_once(__DIR__."/../include/header.php");
use \MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(
	CoreLib\Loc::getPackMessage('icar','mycars_my_cars')." - ".CoreLib\Loc::getPackMessage('icar','mycars_adding_car')
);


if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	$res = Lib\MyCar::addNewCarFromPost($_POST);
	if ($res)
	{
		?><div class="text-success"><?=CoreLib\Loc::getPackMessage('icar','all_add_success')?></div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'my_cars/',3);
	}
	else
	{
		?><div class="text-danger"><?=CoreLib\Loc::getPackMessage('icar','all_add_error')?></div><?
	}
}
?>
<form class="form-horizontal" role="form" name="car_add" method="post" action="">
	<?=Lib\Fields::showCarNameField()?>
	<?=Lib\Fields::showCarActiveField()?>
	<?=Lib\Fields::showCarSortField()?>
	<?=Lib\Fields::showCarBrandAndModelField()?>
	<?=Lib\Fields::showCarYearField()?>
	<?=Lib\Fields::showCarVinField()?>
	<?=Lib\Fields::showCarNumberField()?>
	<?=Lib\Fields::showCarEngineField()?>
	<?=Lib\Fields::showCarGearboxField()?>
	<?=Lib\Fields::showCarBodyField()?>
	<?=Lib\Fields::showCarTsField()?>
	<?=Lib\Fields::showCarCostField()?>
	<?=Lib\Fields::showCarMileageBuyField()?>
	<?=Lib\Fields::showCarCreditField()?>
	<?=Lib\Fields::showCarCreditCostField()?>
	<?=Lib\Fields::showCarOsagoField()?>
	<?=Lib\Fields::showCarGtoField()?>
	<?=Lib\Fields::showCarDefaultField()?>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button>
		</div>
	</div>
</form>
<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
