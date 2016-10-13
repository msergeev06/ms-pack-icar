<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Мои машины - Редактирование автомобиля");

use \MSergeev\Packages\Icar\Lib;

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
				<td>Название авто:</td>
				<td><?= InputType ('text', 'car_name', $arCar['NAME'], '', FALSE, '', 'class="car_name"') ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Автомобиль активен:</td>
				<td><?= SelectBoxBool ('car_active', (($arCar['ACTIVE']) ? 1 : 0)) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Сортировка:</td>
				<td><?= InputType ('text', 'car_sort', $arCar['SORT'], '', FALSE, '', 'class="car_sort"') ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Марка авто:</td>
				<td><?= Lib\CarBrand::getHtmlSelect ($arCar['CAR_BRANDS_ID']) ?></td>
				<td>&nbsp;</td>
			</tr>
			<? if ($arCar['CAR_BRANDS_ID'] > 0): ?>
				<tr>
					<td>Выберите модель:</td>
					<td class="td_model"><?= Lib\CarModel::getHtmlSelect ($arCar['CAR_BRANDS_ID'],
					                                                      $arCar['CAR_MODEL_ID']) ?></td>
					<td>&nbsp;</td>
				</tr>
			<? else: ?>
				<tr>
					<td>Добавьте модель:</td>
					<td class="td_model"><?= InputType ('text', 'car_model_text', '', '', FALSE, '',
					                                    'class="car_model_text"') ?></td>
					<td>&nbsp;</td>
				</tr>
			<?endif; ?>
			<tr>
				<td>Год выпуска:</td>
				<td><?= InputType ('text', 'car_year', $arCar['YEAR'], '', FALSE, '', 'class="car_year"') ?></td>
				<td class="td_year_error error">&nbsp;</td>
			</tr>
			<tr>
				<td>VIN:</td>
				<td><?= InputType ('text', 'car_vin', $arCar['VIN'], '', FALSE, '', 'class="car_vin"') ?></td>
				<td>Цифры и латинские буквы</td>
			</tr>
			<tr>
				<td>Гос номер:</td>
				<td><?= InputType ('text', 'car_number', $arCar['CAR_NUMBER'], '', FALSE, '',
				                   'class="car_number"') ?></td>
				<td>Цифры и латинские буквы</td>
			</tr>
			<tr>
				<td>Объём двигателя:</td>
				<td><?= InputType ('text', 'car_engine', $arCar['ENGINE_CAPACITY'], '', FALSE, '',
				                   'class="car_engine"') ?></td>
				<td>литра</td>
			</tr>
			<tr>
				<td>КПП:</td>
				<td><?= Lib\CarGearbox::getHtmlSelect ($arCar['CAR_GEARBOX_ID']) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Тип кузова:</td>
				<td><?= Lib\CarBody::getHtmlSelect ($arCar['CAR_BODY_ID']) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Интервал прохождения ТО:</td>
				<td><?= InputType ('text', 'car_ts', $arCar['INTERVAL_TS'], '', FALSE, '', 'class="car_ts"') ?></td>
				<td>км</td>
			</tr>
			<tr>
				<td>Стоимость при покупке:</td>
				<td><?= InputType ('text', 'car_cost', Lib\Main::moneyFormat ($arCar['COST'], TRUE), '', FALSE, '',
				                   'class="car_cost"') ?></td>
				<td>руб.</td>
			</tr>
			<tr>
				<td>Пробег при покупке:</td>
				<td><?= InputType ('text', 'car_mileage', Lib\Main::mileageFormat ($arCar['MILEAGE'], TRUE), '', FALSE,
				                   '', 'class="car_mileage"') ?></td>
				<td>км</td>
			</tr>
			<tr>
				<td>Автомобиль в кредит:</td>
				<td><?= SelectBoxBool ('car_credit', (($arCar['CREDIT']) ? 1 : 0)) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Сумма кредита:</td>
				<td><?= InputType ('text', 'car_credit_cost', Lib\Main::moneyFormat ($arCar['CREDIT_COST'], TRUE), '',
				                   FALSE, '', 'class="car_credit_cost"') ?></td>
				<td>руб.</td>
			</tr>
			<tr>
				<td>Дата окончания ОСАГО:</td>
				<td><?= InputCalendar ('car_osago', $arCar['DATE_OSAGO_END'], 'class="car_osago"') ?></td>
				<td>Настроить напоминание</td>
			</tr>
			<tr>
				<td>Дата окончания ГТО:</td>
				<td><?= InputCalendar ('car_gto', $arCar['DATE_GTO_END'], 'class="car_gto"') ?></td>
				<td>Настроить напоминание</td>
			</tr>
			<tr>
				<td>Автомобиль по-умолчанию:</td>
				<td><?= SelectBoxBool ('car_default', (($arCar['DEFAULT']) ? 1 : 0)) ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><input class="submit" type="submit" name="submit" value="Сохранить изменения"></td>
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
		<div class="ok">Изменения успешно сохранены</div><?
	}

}

?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
