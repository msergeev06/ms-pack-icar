<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title_edit'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

$fuelCostsID = intval($_REQUEST["id"]);

if(isset($_POST["action"])) {
	if ($res = Lib\Fuel::updateFuelFromPost($_POST)) {
		?><span style="color: green;"><?=Loc::getPackMessage('icar','fuel_edit_success')?></span><?
	}
	else {
		?><span style="color: red;"><?=Loc::getPackMessage('icar','fuel_edit_error')?></span><?
	}
}
	$arFuel = Lib\Fuel::getFuelList(null,$fuelCostsID);
	$arFuel = $arFuel[0];
	//msDebug($arFuel);
?>
	<form action="" method="post">
		<input type="hidden" name="id" value="<?=$fuelCostsID?>">
		<table class="add_ts">
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_car')?></td>
				<td><? echo Lib\MyCar::showSelectCars("my_car",$arFuel['MY_CAR_ID'],'class="myCar"'); ?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_date')?></td>
				<td><?=InputCalendar ('date', $arFuel['DATE'], 'class="calendarDate"', $strId="")?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_odo')?></td>
				<td><?=InputType('text','odo',$arFuel['ODO'],'',false,'','class="odo"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_mark')?></td>
				<td><? echo Lib\Fuel::showSelectFuelMarks("fuel_mark",$arFuel['FUELMARK_ID'],'class="fuel_mark"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_liters')?></td>
				<td><?=InputType('text','liters',$arFuel['LITER'],'',false,'','class="liters"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_liter_cost')?></td>
				<td><?=InputType('text','cost_liter',$arFuel['LITER_COST'],'',false,'','class="cost_liter"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_full')?></td>
				<td><?=InputType('checkbox','full_tank',1,(($arFuel['FULL'])?1:0),false,'','class="full_tank"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_point')?></td>
				<td><? echo Lib\Points::showSelectPoints("fuel_point",$arFuel['POINTS_ID'],'class="fuel_point"',array('fuel'))?></td>
			</tr>
			<tr>
				<td class="center" colspan="2"><?=Loc::getPackMessage('icar','fuel_or')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_new_point_name')?></td>
				<td><?=InputType('text','newpoint_name','','',false,'','class="newpoint_name"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_new_point_address')?></td>
				<td><?=InputType('text','newpoint_address','','',false,'','class="newpoint_address"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_new_point_lat')?></td>
				<td><?=InputType('text','newpoint_lat','','',false,'','class="newpoint_lat"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_new_point_lon')?></td>
				<td><?=InputType('text','newpoint_lon','','',false,'','class="newpoint_lon"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','fuel_comment')?></td>
				<td><?=InputType('text','comment',$arFuel['INFO'],'',false,'','class="comment"')?></td>
			</tr>
			<tr>
				<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="<?=Loc::getPackMessage('icar','all_save')?>"></td>
			</tr>
		</table>
	</form>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
