<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Мои машины"); ?>
<?
use MSergeev\Packages\Icar\Lib\MyCar;

$arResult['CARS'] = MyCar::getListCar();
//msDebug($arResult);
?>
<p><a href="add.php">Добавить автомобиль</a></p>
<p>Автомобили в вашем гараже:</p>
<div class="mycars">
	<?foreach ($arResult['CARS'] as $myCar):?>
		<div class="carinfo" id="car_<?=$myCar["ID"]?>">
			<div class="blockcar">
				<b><i><?=$myCar['NAME']?></i></b>&nbsp;<?=($myCar["DEFAULT"]) ? '(по-умолчанию)' : ''?><br>
				<b><?=$myCar["BRAND"]['NAME']?>&nbsp;<?=$myCar["MODEL"]['NAME']?>&nbsp;<?=$myCar["YEAR"]?>&nbsp;г.</b><br>
				<b>Гос.&nbsp;номер:</b> <?=$myCar["CAR_NUMBER"]?><br>
				<b>VIN:</b> <?=$myCar["VIN"]?>
			</div>
			<div class="detailinfo">
				<table class="cardetailinfo">
					<tr>
						<td><b>Тип кузова:</b></td>
						<td><?=$myCar["BODY"]['NAME']?></td>
						<td><b>Коробка передач:</b></td>
						<td><?=$myCar["GEARBOX"]['NAME']?></td>
						<td><b>Объем двигателя:</b></td>
						<td><?=$myCar["ENGINE_CAPACITY"]?>&nbsp;л.</td>
					</tr>
					<tr>
						<td><b>Стоимость автомобиля:</b></td>
						<td><?=$myCar["COST"]?>&nbsp;руб.</td>
						<td><b>Кредит:</b>&nbsp;<?=($myCar["CREDIT"]) ? 'Да' : 'Нет'?></td>
						<td><?=($myCar["CREDIT"]) ? $myCar["CREDIT_COST"]."&nbsp;руб." : "&nbsp;"?></td>
						<td><b>Интервал ТО:</b></td>
						<td><?=$myCar["INTERVAL_TS"]?>&nbsp;км.</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><b>Дата окончания ОСАГО:</b></td>
						<td><?=$myCar["DATE_OSAGO_END"]?></td>
						<td><b>Дата окончания ГТО:</b></td>
						<td><?=$myCar["DATE_GTO_END"]?></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><b>Всего расходов:</b></td>
						<td colspan="5"><?=MyCar::getCarTotalCostsFormatted($myCar['ID'])?>&nbsp;руб.</td>
					</tr>
					<tr>
						<td><b>Средний расход топлива:</b></td>
						<td colspan="5"><?=MyCar::getCarAverageFuelFormatted($myCar['ID'])?>&nbsp;л./100км.</td>
					</tr>
					<tr>
						<td><b>Израсходованно топлива:</b></td>
						<td colspan="5"><?=MyCar::getCarTotalSpentFuelFormatted($myCar['ID'])?>&nbsp;л.</td>
					</tr>
					<tr>
						<td><b>Текущий пробег:</b></td>
						<td colspan="5"><?=MyCar::getCarCurrentMileageFormatted($myCar['ID'])?>&nbsp;км.</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><b>Дополнительное описание:</b></td>
						<td colspan="5">В разработке</td>
					</tr>
					<tr>
						<td><b>Фотографии автомобиля:</b></td>
						<td colspan="5">В разработке</td>
					</tr>
					<tr>
						<td><b>Автомобиль по-умолчанию:</b></td>
						<td colspan="5"><?=($myCar["DEFAULT"]) ? '<b>Да</b>' : 'Нет'?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td><a href="edit.php?car=<?=$myCar["ID"]?>">Редактировать</a></td>
						<td colspan="5"><a href="delete.php?car=<?=$myCar["ID"]?>">Удалить</a></td>
					</tr>
				</table>

			</div>
		</div>
	<?endforeach?>
</div>
<p><a href="add.php">Добавить автомобиль</a></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
