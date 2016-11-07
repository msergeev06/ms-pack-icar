<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','mycars_my_cars')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','mycars_car_edit'));

use \MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

if (!isset($_POST['step']))
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
	<form name="car_add" method="post" action="">
		<input type="hidden" name="step" value="1">
		<input type="hidden" name="car_id" value="<?=$carID?>">
		<table class="car_add">
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_name')?>:</td>
				<td><?= InputType ('text', 'car_name', $arCar['NAME'], '', FALSE, '', 'class="car_name"') ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_active')?>:</td>
				<td><?= SelectBoxBool ('car_active', (($arCar['ACTIVE']) ? 1 : 0)) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_sort')?>:</td>
				<td><?= InputType ('text', 'car_sort', $arCar['SORT'], '', FALSE, '', 'class="car_sort"') ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_brand')?>:</td>
				<td><?= Lib\CarBrand::getHtmlSelect ($arCar['BRAND_ID']) ?></td>
				<td>&nbsp;</td>
			</tr>
			<? if ($arCar['BRAND_ID'] > 0): ?>
				<tr>
					<td><?=Loc::getPackMessage('icar','mycars_select_model')?>:</td>
					<td class="td_model"><?= Lib\CarModel::getHtmlSelect ($arCar['BRAND_ID'],
					                                                      $arCar['MODEL_ID']) ?></td>
					<td>&nbsp;</td>
				</tr>
			<? else: ?>
				<tr>
					<td><?=Loc::getPackMessage('icar','mycars_add_model')?>:</td>
					<td class="td_model"><?= InputType ('text', 'car_model_text', '', '', FALSE, '',
					                                    'class="car_model_text"') ?></td>
					<td>&nbsp;</td>
				</tr>
			<?endif; ?>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_year')?>:</td>
				<td><?= InputType ('text', 'car_year', $arCar['YEAR'], '', FALSE, '', 'class="car_year"') ?></td>
				<td class="td_year_error error">&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_vin')?>:</td>
				<td><?= InputType ('text', 'car_vin', $arCar['VIN'], '', FALSE, '', 'class="car_vin"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_num_and_lat')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_number')?>:</td>
				<td><?= InputType ('text', 'car_number', $arCar['CAR_NUMBER'], '', FALSE, '',
				                   'class="car_number"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_num_and_lat')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_engine_capacity')?>:</td>
				<td><?= InputType ('text', 'car_engine', $arCar['ENGINE_CAPACITY'], '', FALSE, '',
				                   'class="car_engine"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_litra')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_gearbox')?>:</td>
				<td><?= Lib\CarGearbox::getHtmlSelect ($arCar['GEARBOX_ID']) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_body_type')?>:</td>
				<td><?= Lib\CarBody::getHtmlSelect ($arCar['BODY_ID']) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_interval_ts')?>:</td>
				<td><?= InputType ('text', 'car_ts', $arCar['INTERVAL_TS'], '', FALSE, '', 'class="car_ts"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_km')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_auto_cost_buy')?>:</td>
				<td><?= InputType ('text', 'car_cost', Lib\Main::moneyFormat ($arCar['COST'], TRUE), '', FALSE, '',
				                   'class="car_cost"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_rub')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_mileage_buy')?>:</td>
				<td><?= InputType ('text', 'car_mileage', Lib\Main::mileageFormat ($arCar['MILEAGE'], TRUE), '', FALSE,
				                   '', 'class="car_mileage"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_km')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_credit')?>:</td>
				<td><?= SelectBoxBool ('car_credit', (($arCar['CREDIT']) ? 1 : 0)) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_car_credit_cost')?>:</td>
				<td><?= InputType ('text', 'car_credit_cost', Lib\Main::moneyFormat ($arCar['CREDIT_COST'], TRUE), '',
				                   FALSE, '', 'class="car_credit_cost"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_rub')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_date_end_osago')?>:</td>
				<td><?= InputCalendar ('car_osago', $arCar['DATE_OSAGO_END'], 'class="car_osago"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_set_notice')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_date_end_gto')?>:</td>
				<td><?= InputCalendar ('car_gto', $arCar['DATE_GTO_END'], 'class="car_gto"') ?></td>
				<td><?=Loc::getPackMessage('icar','mycars_set_notice')?></td>
			</tr>
			<tr>
				<td><?=Loc::getPackMessage('icar','mycars_default_car')?>:</td>
				<td><?= SelectBoxBool ('car_default', (($arCar['DEFAULT']) ? 1 : 0)) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><input class="submit" type="submit" name="submit" value="<?=Loc::getPackMessage('icar','all_save')?>"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</form>
<?
}
else
{
	$arParams = array ();
	//Проверка полей
	if (TRUE)
	{
		if (isset($_POST['car_id']))
		{
			$arParams['ID'] = intval($_POST['car_id']);
		}

		if (isset($_POST['car_name']))
		{
			$arParams['NAME'] = htmlspecialchars ($_POST['car_name']);
		}
		if (isset($_POST['car_active']) && $_POST['car_active'] > 0)
		{
			$arParams['ACTIVE'] = TRUE;
		} else
		{
			$arParams['ACTIVE'] = FALSE;
		}

		if (isset($_POST['car_sort']))
		{
			$arParams['SORT'] = intval ($_POST['car_sort']);
		}

		if (isset($_POST['car_brand']))
		{
			$arParams['CAR_BRANDS_ID'] = intval ($_POST['car_brand']);
		}

		if (isset($_POST['car_model']))
		{
			$arParams['CAR_MODEL_ID'] = intval ($_POST['car_model']);
		}

		if (isset($_POST['car_year']))
		{
			$arParams['YEAR'] = intval ($_POST['car_year']);
		}

		if (isset($_POST['car_vin']))
		{
			$arParams['VIN'] = htmlspecialchars ($_POST['car_vin']);
		}

		if (isset($_POST['car_number']))
		{
			$arParams['CAR_NUMBER'] = htmlspecialchars ($_POST['car_number']);
		}

		if (isset($_POST['car_engine']))
		{
			$arParams['ENGINE_CAPACITY'] = floatval ($_POST['car_engine']);
		}

		if (isset($_POST['car_gearbox']))
		{
			$arParams['CAR_GEARBOX_ID'] = intval ($_POST['car_gearbox']);
		}

		if (isset($_POST['car_body']))
		{
			$arParams['CAR_BODY_ID'] = intval ($_POST['car_body']);
		}

		if (isset($_POST['car_ts']))
		{
			$arParams['INTERVAL_TS'] = floatval ($_POST['car_ts']);
		}

		if (isset($_POST['car_cost']))
		{
			$arParams['COST'] = floatval ($_POST['car_cost']);
		}

		if (isset($_POST['car_mileage']))
		{
			$arParams['MILEAGE'] = floatval ($_POST['car_mileage']);
		}

		if (isset($_POST['car_credit']) && $_POST['car_credit'] > 0)
		{
			$arParams['CREDIT'] = TRUE;
		} else
		{
			$arParams['CREDIT'] = FALSE;
		}

		if (isset($_POST['car_credit_cost']))
		{
			$arParams['CREDIT_COST'] = floatval ($_POST['car_credit_cost']);
		}

		if (isset($_POST['car_osago']))
		{
			$arParams['DATE_OSAGO_END'] = htmlspecialchars ($_POST['car_osago']);
		}

		if (isset($_POST['car_gto']))
		{
			$arParams['DATE_GTO_END'] = htmlspecialchars ($_POST['car_gto']);
		}

		if (isset($_POST['car_default']) && $_POST['car_default'] > 0)
		{
			$arParams['DEFAULT'] = TRUE;
		} else
		{
			$arParams['DEFAULT'] = FALSE;
		}


	}

	$res = Lib\MyCar::editCar ($arParams);
	if ($res->getResult ())
	{
		?>
		<div class="ok"><?=Loc::getPackMessage('icar','mycars_change_save_success')?></div><?
	}

}

?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
