<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Мои машины - Добавление автомобиля");

use \MSergeev\Packages\Icar\Lib;

?>
<?if (!isset($_POST['step'])):?>
	<form name="car_add" method="post" action="">
		<input type="hidden" name="step" value="1">
		<table class="car_add">
			<tr>
				<td>Название авто:</td>
				<td><?=InputType('text','car_name','','',false,'','class="car_name"')?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Автомобиль активен:</td>
				<td><?=SelectBoxBool('car_active',1)?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Сортировка:</td>
				<td><?=InputType('text','car_sort','500','',false,'','class="car_sort"')?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Марка авто:</td>
				<td><?=Lib\CarBrand::getHtmlSelect()?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Модель:</td>
				<td class="td_model"><?=InputType('text','car_model_text','','',false,'','class="car_model_text"')?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Год выпуска:</td>
				<td><?=InputType('text','car_year','','',false,'','class="car_year"')?></td>
				<td class="td_year_error error">&nbsp;</td>
			</tr>
			<tr>
				<td>VIN:</td>
				<td><?=InputType('text','car_vin','','',false,'','class="car_vin"')?></td>
				<td>Цифры и латинские буквы</td>
			</tr>
			<tr>
				<td>Гос номер:</td>
				<td><?=InputType('text','car_number','','',false,'','class="car_number"')?></td>
				<td>Цифры и латинские буквы</td>
			</tr>
			<tr>
				<td>Объём двигателя:</td>
				<td><?=InputType('text','car_engine','','',false,'','class="car_engine"')?></td>
				<td>литра</td>
			</tr>
			<tr>
				<td>КПП:</td>
				<td><?=Lib\CarGearbox::getHtmlSelect()?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Тип кузова:</td>
				<td><?=Lib\CarBody::getHtmlSelect()?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Интервал прохождения ТО:</td>
				<td><?=InputType('text','car_ts','','',false,'','class="car_ts"')?></td>
				<td>км</td>
			</tr>
			<tr>
				<td>Стоимость при покупке:</td>
				<td><?=InputType('text','car_cost','','',false,'','class="car_cost"')?></td>
				<td>руб.</td>
			</tr>
			<tr>
				<td>Пробег при покупке:</td>
				<td><?=InputType('text','car_mileage','','',false,'','class="car_mileage"')?></td>
				<td>км</td>
			</tr>
			<tr>
				<td>Автомобиль в кредит:</td>
				<td><?=SelectBoxBool('car_credit')?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Сумма кредита:</td>
				<td><?=InputType('text','car_credit_cost','','',false,'','class="car_credit_cost"')?></td>
				<td>руб.</td>
			</tr>
			<tr>
				<td>Дата окончания ОСАГО:</td>
				<td><?=InputCalendar('car_osago','','class="car_osago"')?></td>
				<td>Настроить напоминание</td>
			</tr>
			<tr>
				<td>Дата окончания ГТО:</td>
				<td><?=InputCalendar('car_gto','','class="car_gto"')?></td>
				<td>Настроить напоминание</td>
			</tr>
			<tr>
				<td>Автомобиль по-умолчанию:</td>
				<td><?=SelectBoxBool('car_default')?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><input class="submit" type="submit" name="submit" value="Добавить"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</form>

	<script type="text/javascript">
		$(document).on("ready",function(){
			$('#car_brand').on('change',function(){
				var brand_id = $(this).val();
				$.post(
					"<?=\MSergeev\Core\Lib\Config::getConfig("ICAR_TOOLS_ROOT").'get_select_model.php'?>",
					{
						brand_id: brand_id
					},
					function(data) {
						console.log(data);
						if (data.status == 'ok')
						{
							$('.td_model').html(data.select);
						}
						else
						{
							$('.td_model').html('<?=InputType('text','car_model_text','','',false,'','class="car_model_text"')?>');

						}
					},
					"json"
				);
			});
			$('.submit').on('click',function(){
				var error = false;
				if ($('.car_year').val() < 1500)
				{
					$('.td_year_error').text('Автомобиль настолько старый? Не верю!');
					//$(this).focus();
					error = true;
				}

				if (error)
				{
					return false;
				}
			});
		});
	</script>
<?else:

	$arParams = array();
	//Проверка полей
	if (true)
	{
		if (isset($_POST['car_name']))
		{
			$arParams['NAME'] = htmlspecialchars($_POST['car_name']);
		}
		if (isset($_POST['car_active']) && $_POST['car_active']>0)
		{
			$arParams['ACTIVE'] = true;
		}
		else
		{
			$arParams['ACTIVE'] = false;
		}

		if (isset($_POST['car_sort']))
		{
			$arParams['SORT'] = intval($_POST['car_sort']);
		}

		if(isset($_POST['car_brand']))
		{
			$arParams['CAR_BRANDS_ID'] = intval($_POST['car_brand']);
		}

		if (isset($_POST['car_model']))
		{
			$arParams['CAR_MODEL_ID'] = intval($_POST['car_model']);
		}
		elseif (isset($_POST['car_model_text']))
		{
			$arParams['CAR_MODEL_TEXT'] = htmlspecialchars($_POST['car_model_text']);
		}

		if (isset($_POST['car_year']))
		{
			$arParams['YEAR'] = intval($_POST['car_year']);
		}

		if (isset($_POST['car_vin']))
		{
			$arParams['VIN'] = htmlspecialchars($_POST['car_vin']);
		}

		if (isset($_POST['car_number']))
		{
			$arParams['CAR_NUMBER'] = htmlspecialchars($_POST['car_number']);
		}

		if (isset($_POST['car_engine']))
		{
			$arParams['ENGINE_CAPACITY'] = floatval($_POST['car_engine']);
		}

		if (isset($_POST['car_gearbox']))
		{
			$arParams['CAR_GEARBOX_ID'] = intval($_POST['car_gearbox']);
		}

		if (isset($_POST['car_body']))
		{
			$arParams['CAR_BODY_ID'] = intval($_POST['car_body']);
		}

		if (isset($_POST['car_ts']))
		{
			$arParams['INTERVAL_TS'] = floatval($_POST['car_ts']);
		}

		if (isset($_POST['car_cost']))
		{
			$arParams['COST'] = floatval($_POST['car_cost']);
		}

		if (isset($_POST['car_mileage']))
		{
			$arParams['MILEAGE'] = floatval($_POST['car_mileage']);
		}

		if (isset($_POST['car_credit']) && $_POST['car_credit'] > 0)
		{
			$arParams['CREDIT'] = true;
		}
		else
		{
			$arParams['CREDIT'] = false;
		}

		if (isset($_POST['car_credit_cost']))
		{
			$arParams['CREDIT_COST'] = floatval($_POST['car_credit_cost']);
		}

		if (isset($_POST['car_osago']))
		{
			$arParams['DATE_OSAGO_END'] = htmlspecialchars($_POST['car_osago']);
		}

		if (isset($_POST['car_gto']))
		{
			$arParams['DATE_GTO_END'] = htmlspecialchars($_POST['car_gto']);
		}

		if (isset($_POST['car_default']) && $_POST['car_default']>0)
		{
			$arParams['DEFAULT'] = true;
		}
		else
		{
			$arParams['DEFAULT'] = false;
		}

	}

	if (!isset($arParams['CAR_MODEL_ID'])
		&& isset($arParams['CAR_MODEL_TEXT'])
		&& $arParams['CAR_BRAND'] > 0
	)
	{
		$arParams['CAR_MODEL_ID'] = Lib\CarModel::addNewModel(
			$arParams['CAR_BRAND'],
			$arParams['CAR_MODEL_TEXT']
		);
		if (intval($arParams['CAR_MODEL_ID']) > 0)
		{
			unset($arParams['CAR_MODEL_TEXT']);
		}
	}

	$res = Lib\MyCar::addNewCar($arParams);
	if ($res->getResult())
	{
		?><div class="ok">Автомобиль успешно добавлен</div><?
	}


endif;?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
