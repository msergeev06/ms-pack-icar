<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','mycars_my_cars')); ?>
<?
use MSergeev\Packages\Icar\Lib\MyCar;
use MSergeev\Core\Lib\Loc;
use MSergeev\Packages\Icar\Lib;

$arResult['CARS'] = MyCar::getList();
//msDebug($arResult);
?>
<p><a href="add.php"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','mycars_add_car')?></button></a></p>
<p><?=Loc::getPackMessage('icar','mycars_auto_in_garage')?>:</p>
<div class="mycars">
	<?foreach ($arResult['CARS'] as $myCar):?>
		<div class="carinfo" id="car_<?=$myCar['ID']?>">
			<div class="blockcar">
				<b><i><?=$myCar['NAME']?></i></b>&nbsp;<?=($myCar['DEFAULT']) ? '('.Loc::getPackMessage('icar','mycars_default').')' : ''?><br>
				<b><?=$myCar['BRAND_NAME']?>&nbsp;<?=$myCar['MODEL_NAME']?>&nbsp;<?=$myCar['YEAR']?>&nbsp;<?=Loc::getPackMessage('icar','mycars_y')?></b><br>
				<b><?=Loc::getPackMessage('icar','mycars_car_number')?>:</b> <?=$myCar['CAR_NUMBER']?><br>
				<b><?=Loc::getPackMessage('icar','mycars_vin')?>:</b> <?=$myCar['VIN']?>
			</div>
			<div class="detailinfo">
				<table class="cardetailinfo">
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_body_type')?>:</b></td>
						<td><?=$myCar['BODY_NAME']?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_gearbox')?>:</b></td>
						<td><?=$myCar['GEARBOX_NAME']?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_engine_capacity')?>:</b></td>
						<td><?=Lib\Main::formatLiter($myCar['ENGINE_CAPACITY'])?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_auto_cost')?>:</b></td>
						<td><?=Lib\Main::formatMoney($myCar['COST'])?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_credit')?>:</b>&nbsp;<?=($myCar['CREDIT']) ? Loc::getPackMessage('icar','mycars_yes') : Loc::getPackMessage('icar','mycars_no')?></td>
						<td><?=($myCar['CREDIT']) ? Lib\Main::formatMoney($myCar['CREDIT_COST']) : "&nbsp;"?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_interval_ts')?>:</b></td>
						<td><?=Lib\Main::formatMileage($myCar['INTERVAL_TS'])?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_date_end_osago')?>:</b></td>
						<td><?=$myCar['DATE_OSAGO_END']?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_date_end_gto')?>:</b></td>
						<td><?=$myCar['DATE_GTO_END']?></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_total_costs')?>:</b></td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'])?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_average_fuel')?>:</b></td>
						<td colspan="5"><?=Lib\Statistics::getCarAverageFuelFormatted($myCar['ID'])?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_spent_fuel')?>:</b></td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelFormatted($myCar['ID'])?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_current_mileage')?>:</b></td>
						<td colspan="5"><?=Lib\Statistics::getCarCurrentMileageFormatted($myCar['ID'])?></td>
					</tr>
					<tr>
						<td><b>В собственности:</b></td>
						<td colspan="5"><?=Lib\Statistics::getOwnershipYearsMonthsDaysFormatted($myCar['ID'])?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><button type="button" class="btn btn-default full-stat">Полная статистика</button></td>
						<td colspan="5"></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Расходы (с топливом)</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средняя стоимость за км</td>
						<td colspan="5"><?=Lib\Statistics::getAverageCostKmFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Расходы (без топлива)</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'],array(),array('fuel'))?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],true,array(),array('fuel'))?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],false,array(),array('fuel'))?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],true,array(),array('fuel'))?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],false,array(),array('fuel'))?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Расходы (на топливо)</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'],array('fuel'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],true,array('fuel'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],false,array('fuel'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],true,array('fuel'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],false,array('fuel'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Расходы (на обслуживание)</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'],array('repair','parts'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],true,array('repair','parts'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],false,array('repair','parts'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],true,array('repair','parts'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],false,array('repair','parts'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Общая статистика</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Последнее значение одометра</td>
						<td colspan="5"><?=Lib\Statistics::getCarCurrentOdoFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Общее расстояние</td>
						<td colspan="5"><?=Lib\Statistics::getCarCurrentMileageFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Всего топлива</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Общая стоимость топлива</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'],array('fuel'),array())?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Всего заправок</td>
						<td colspan="5"><?=Lib\Statistics::getNumberOfRefills($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Заправки топливом</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getNumberOfRefills($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getNumberOfRefillsYear($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getNumberOfRefillsYear($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getNumberOfRefillsMonth($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getNumberOfRefillsMonth($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Максимальная заправка</td>
						<td colspan="5"><?=Lib\Statistics::getMaxRefillsFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Минимальная заправка</td>
						<td colspan="5"><?=Lib\Statistics::getMinRefillsFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Топливо</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelYearFormatted($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelYearFormatted($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelMonthFormatted($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalSpentFuelMonthFormatted($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Расходы</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За все время</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За текущий год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>За прошлый год</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsYearFormatted($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Текущий месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],true)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Прошлый месяц</td>
						<td colspan="5"><?=Lib\Statistics::getCarTotalCostsMonthFormatted($myCar['ID'],false)?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Самый дорогой чек</td>
						<td colspan="5"><?=Lib\Statistics::getMaxCheckFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Самый дешевый чек</td>
						<td colspan="5"><?=Lib\Statistics::getMinCheckFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Лучшая цена за топливо</td>
						<td colspan="5"><?=Lib\Statistics::getMinFuelCostFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Худшая цена за топливо</td>
						<td colspan="5"><?=Lib\Statistics::getMaxFuelCostFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Лучшая стоимость за километр</td>
						<td colspan="5"><?=Lib\Statistics::getMinCostByKmFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Наихудшая стоимость за километр</td>
						<td colspan="5"><?=Lib\Statistics::getMaxCostByKmFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td><strong>Средние значения</strong></td>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средняя заправка</td>
						<td colspan="5"><?=Lib\Statistics::getAverageFuelRefillsFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средняя цена заправки</td>
						<td colspan="5"><?=Lib\Statistics::getAverageFuelCostsFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средняя стоимость за километр (топливо)</td>
						<td colspan="5"><?=Lib\Statistics::getAverageFuelCostKmFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средняя стоимость в день</td>
						<td colspan="5"><?=Lib\Statistics::getAverageCostPerDayFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средняя стоимость в месяц</td>
						<td colspan="5"><?=Lib\Statistics::getAverageCostPerMonthFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средний пробег в день</td>
						<td colspan="5"><?=Lib\Statistics::getAverageMileageDayFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средний пробег в месяц</td>
						<td colspan="5"><?=Lib\Statistics::getAverageMileageMonthFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Средний расход топлива</td>
						<td colspan="5"><?=Lib\Statistics::getCarAverageFuelFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Наименьший расход топлива</td>
						<td colspan="5"><?=Lib\Statistics::getMinFuelConsumptionFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td>Наибольший расход топлива</td>
						<td colspan="5"><?=Lib\Statistics::getMaxFuelConsumptionFormatted($myCar['ID'])?></td>
					</tr>
					<tr class="full_statistic" style="display: none;">
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_description')?>:</b></td>
						<td colspan="5"><?=Loc::getPackMessage('icar','mycars_construction')?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_car_photo')?>:</b></td>
						<td colspan="5"><?=Loc::getPackMessage('icar','mycars_construction')?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_default_car')?>:</b></td>
						<td colspan="5"><?=($myCar['DEFAULT']) ? '<b>'.Loc::getPackMessage('icar','mycars_yes').'</b>' : Loc::getPackMessage('icar','mycars_no')?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><a href="edit.php?car=<?=$myCar['ID']?>"><button type="button" class="btn btn-success"><?=Loc::getPackMessage('icar','all_edit')?></button></a></td>
						<td colspan="5"><a href="delete.php?car=<?=$myCar['ID']?>"><button type="button" class="btn btn-danger"><?=Loc::getPackMessage('icar','all_delete')?></button></a></td>
					</tr>
				</table>

			</div>
		</div>
	<?endforeach?>
</div>
<script>
$(document).on("ready",function(){
	var hide = 1;
	$('.full-stat').on("click",function(){
		if (hide == 1)
		{
			$('.full_statistic').show();
			hide = 0;
		}
		else
		{
			$('.full_statistic').hide();
			hide = 1;
		}
	});
});
</script>
<p><a href="add.php"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','mycars_add_car')?></button></a></p>
<? $curDir = basename(__DIR__); ?>
<? /*
ЗАМЕНА
Моторное масло и масляный фильтр 15000 (12 мес)
Фильтр системы вентиляции воздуха в салоне автомобиля 15000 (12 мес)
Воздушный фильтр 30000 (24 мес)
Топливный фильтр 30000 (24 мес)
Свечи зажигания 30000 (24 мес)
Тормозная жидкость 45000 (36 мес)
Охлаждающая жидкость 75000 (60 мес)
Ремень ГРМ 75000 (60 мес)
Приводной ремень 90000 (72 мес)

ПРОВЕРКА
Зазоры в приводе впускных и выпускных клапанов 15000 (12 мес)
Направление света фар 15000 (12 мес)
Тормозная система и гидравлический привод сцепления, рабочая жидкость 15000 (12 мес)
Вакуумные штанги, соединения и контрольный клапан усилителя тормозов 15000 (12 мес)
Ремень ГРМ 15000 (12 мес)
Приводной ремень 15000 (12 мес)
Охлаждающая жидкость 15000 (12 мес)
Система охлаждения двигателя 15000 (12 мес)
Тормозные колодки, тормозные диски и другие компоненты тормозной системы 15000 (12 мес)
Трансмиссионное масло механической коробки передач 15000 (12 мес)
Воздушный фильтр 15000 (12 мес)
Рулевой механизм и привод, детали осей и подвески, передние приводные валы, выпускная система
15000 (12 мес)
Свечи зажигания 15000 (12 мес)
Рабочая и стояночная тормозная система (работа, свободный ход) 15000 (12 мес)
Компьютерная диагностика 15000 (12 мес)
Работа системы кондиционирования воздуха 15000 (12 мес)
Проверка кузова на отсутствие коррозии 15000 (12 мес)
Положение выключателя стоп-сигналов 15000 (12 мес)
Уровень вредных выбросов в отработавших газах 15000 (12 мес)
Диагностика усилителя рулевого управления 15000 (12 мес)
Топливопроводы и трубопроводы паров бензина 30000 (12 мес)
Кислородные датчики 75000 (60 мес)
Зазоры в приводе впускных и выпускных клапанов (первая замена) 2500


Свечи зажигания
8-клапанный двигатель	A17DVRM JSC "Robert Bosch Saratov"
			LR15YC-1 BRISK
			WR7DCX Bosch
16-клапанный двигатель	AU17DVRM JSC "Robert Bosch Saratov"
			DR15YC-1 BRISK
			FR7DCU Bosch

Зазор между электродами свечи зажигания должен составлять от 1 до 1,15 мм


Тормозная жидкость
Марка		Изготовитель		Нормативный документ
ROSDOT		"Тосол-Синтез",		ТУ 2451-004-36732629
		г. Дзержинск
KAPROS-DOT	"Сибур-Нефтехим",	ТУ 2451-030-52470175
		г. Дзержинск


Приборы наружного освещения
Фары ближний и дальний свет: H4
Передний указатель поворота: PY21W
Дневные ходовые огни (ДХО) и габаритные фонари: W21/5W
Противотуманные фары: H11
Боковой повторитель указателя поворота: WY5W
Задний комбинированный фонарь:
	Указатель поворота: PY21W
	Стоп-сигналы и габаритные фонари: P21/5W
	Фонарь заднего хода: W16W
	Задний противотуманный фонарь: P21W
Фонарь освещения регистрационного знака: C5W

Внутренние осветительные приборы
Плафоны общего и местного освещения: C10W
Плафоны передних сидений: T4W
Плафон багажного отделения: C5W

Рекомендуется переставлять колеса через каждые 10000 км пробега автомобиля


Заправочные емкости:
Топливный бак: 50л
Система смазки двигателя (С МКП)
	(всухую) 3,2 л
	(замена масла и фильтра) 2,9 л
Система охлаждения двигателя и отопитель 7,84 л
Механическая коробка передач 2,2 л
Гидропривод тормозной системы 0,45 л
Омыватель ветрового стекла 3 л

Моторное масло:
Минимальная температура 	Вязкость масла		Максимальная температура
воздуха для холодного 		по SAE J 300		наружного воздуха
пуска двигателя, С*
ниже -35			0W-40			30
-30				5W-30			25
-30				5W-40			35
-25				10W-30			25
-25				10W-40			35
-20				15W-40			45
-15				20W-50			выше 45

Трансмиссионное масло для коробки передач
Минимальная температура		Вязкость масла		Максимальная температура
смазываемого узла, С		по SAE J 306		наружного воздуха, С
-40				75W-80			35
-40				75W-85			35
-40				75W-90			45
-26				80W-85			35
-26				80W-90			45
-12				85W-90			45 и выше

Колеса и шины

Типоразмер шины,	Посадочный	Вылет	Давление воздуха в шинах
индекс максимальной	диаметр		(ЕТ)	передних/задних, мПа (кгс/см2)
нагрузки и скорости	(дюймы)			Частичная 		Полная
Стандартное колесо				нагрузка		нагрузка
185/60R14 82H		5J		35	0,2/0,2 (2,0/2,0)	0,2/0,22 (2,0/2,2)
185/60R15 82V		6J		35	0,2/0,2 (2,0/2,0)	0,2/0,22 (2,0/2,2)
Запасное колесо *5
175/65R14 82T,H		5J		35	0,2/0,2 (2,0/2,0)	0,2/0,22 (2,0/2,2)
185/60R14 82T,H		5J		35	0,2/0,2 (2,0/2,0)	0,2/0,22 (2,0/2,2)


Расход топлива норма
город: 9
трасса: 5,27
смешанный: 6,5

*/?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
