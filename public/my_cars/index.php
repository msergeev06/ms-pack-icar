<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','mycars_my_cars')); ?>
<?
use MSergeev\Packages\Icar\Lib\MyCar;
use MSergeev\Core\Lib\Loc;

$arResult['CARS'] = MyCar::getListCar();
//msDebug($arResult);
?>
<p><a href="add.php"><?=Loc::getPackMessage('icar','mycars_add_car')?></a></p>
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
						<td><?=$myCar['ENGINE_CAPACITY']?>&nbsp;<?=Loc::getPackMessage('icar','mycars_l')?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_auto_cost')?>:</b></td>
						<td><?=$myCar['COST']?>&nbsp;<?=Loc::getPackMessage('icar','mycars_rub')?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_credit')?>:</b>&nbsp;<?=($myCar['CREDIT']) ? Loc::getPackMessage('icar','mycars_yes') : Loc::getPackMessage('icar','mycars_no')?></td>
						<td><?=($myCar['CREDIT']) ? $myCar['CREDIT_COST']."&nbsp;".Loc::getPackMessage('icar','mycars_rub') : "&nbsp;"?></td>
						<td><b><?=Loc::getPackMessage('icar','mycars_interval_ts')?>:</b></td>
						<td><?=$myCar['INTERVAL_TS']?>&nbsp;<?=Loc::getPackMessage('icar','mycars_km')?></td>
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
						<td colspan="5"><?=MyCar::getCarTotalCostsFormatted($myCar['ID'])?>&nbsp;<?=Loc::getPackMessage('icar','mycars_rub')?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_average_fuel')?>:</b></td>
						<td colspan="5"><?=MyCar::getCarAverageFuelFormatted($myCar['ID'])?>&nbsp;<?=Loc::getPackMessage('icar','mycars_l_100km')?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_spent_fuel')?>:</b></td>
						<td colspan="5"><?=MyCar::getCarTotalSpentFuelFormatted($myCar['ID'])?>&nbsp;<?=Loc::getPackMessage('icar','mycars_l')?></td>
					</tr>
					<tr>
						<td><b><?=Loc::getPackMessage('icar','mycars_current_mileage')?>:</b></td>
						<td colspan="5"><?=MyCar::getCarCurrentMileageFormatted($myCar['ID'])?>&nbsp;<?=Loc::getPackMessage('icar','mycars_km')?></td>
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
						<td><a href="edit.php?car=<?=$myCar['ID']?>"><?=Loc::getPackMessage('icar','all_edit')?></a></td>
						<td colspan="5"><a href="delete.php?car=<?=$myCar['ID']?>"><?=Loc::getPackMessage('icar','all_delete')?></a></td>
					</tr>
				</table>

			</div>
		</div>
	<?endforeach?>
</div>
<p><a href="add.php"><?=Loc::getPackMessage('icar','mycars_add_car')?></a></p>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
