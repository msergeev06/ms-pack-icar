<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(
	CoreLib\Loc::getPackMessage('icar','mycars_my_cars')." - ".CoreLib\Loc::getPackMessage('icar','mycars_car_edit')
);

if (!isset($_POST['action']))
{
	if (isset($_REQUEST['car']) && intval ($_REQUEST['car']) > 0)
	{
		$carID = $_REQUEST['car'];
	} else
	{
		$carID = Lib\MyCar::getDefaultCarID ();
	}

	$arCar = Lib\MyCar::getCarByID ($carID);
	?>
	<form class="form-horizontal" role="form" name="car_edit" method="post" action="">
		<?=Lib\Fields::showCarIdHiddenField($carID)?>
		<?=Lib\Fields::showCarNameField($arCar['NAME'])?>
		<?=Lib\Fields::showCarActiveField($arCar['ACTIVE'])?>
		<?=Lib\Fields::showCarSortField($arCar['SORT'])?>
		<?=Lib\Fields::showCarBrandAndModelField($arCar['BRAND_ID'],$arCar['MODEL_ID'])?>
		<?=Lib\Fields::showCarYearField($arCar['YEAR'])?>
		<?=Lib\Fields::showCarVinField($arCar['VIN'])?>
		<?=Lib\Fields::showCarNumberField($arCar['CAR_NUMBER'])?>
		<?=Lib\Fields::showCarEngineField($arCar['ENGINE_CAPACITY'])?>
		<?=Lib\Fields::showCarGearboxField($arCar['GEARBOX_ID'])?>
		<?=Lib\Fields::showCarBodyField($arCar['BODY_ID'])?>
		<?=Lib\Fields::showCarTsField($arCar['INTERVAL_TS'])?>
		<?=Lib\Fields::showCarCostField($arCar['COST'])?>
		<?=Lib\Fields::showCarMileageBuyField($arCar['MILEAGE'])?>
		<?=Lib\Fields::showCarCreditField($arCar['CREDIT'])?>
		<?=Lib\Fields::showCarCreditCostField($arCar['CREDIT_COST'])?>
		<?=Lib\Fields::showCarOsagoField($arCar['DATE_OSAGO_END'])?>
		<?=Lib\Fields::showCarGtoField($arCar['DATE_GTO_END'])?>
		<?=Lib\Fields::showCarDefaultField($arCar['DEFAULT'])?>
		<input type="hidden" name="action" value="1">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_save')?></button>
			</div>
		</div>
	</form>
<?
}
else
{
	$res = Lib\MyCar::updateCarFromPost ($_POST);
	if ($res)
	{
		?><div class="text-success"><?=CoreLib\Loc::getPackMessage('icar','all_edit_success')?></div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'my_cars/',3);
	}
	else
	{
		?><div class="text-danger"><?=CoreLib\Loc::getPackMessage('icar','all_edit_error')?></div><?
	}

}

?>
<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
