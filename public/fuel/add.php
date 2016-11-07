<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','fuel_title_add'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Options;
use MSergeev\Core\Lib\Loc;

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

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	if (Lib\Fuel::addFuelFromPost($_POST)) {
		echo '<span class="ok">'.Loc::getPackMessage('icar','fuel_add_success').'</span>';
	}
	else {
		echo '<span class="err">'.Loc::getPackMessage('icar','fuel_add_error').'</span>';
	}
}
//msDebug(Lib\Odo::getMaxOdo($carID));
?>
<form action="" method="post">
	<table class="add_ts">
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_car')?></td>
			<td><? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_date')?></td>
			<?
			if (isset($_POST['date']))
			{
				$date = $_POST['date'];
			}
			else
			{
				$date = date('d.m.Y');
			}
			?>
			<td><?=InputCalendar ('date', $date, 'class="calendarDate"', $strId="")?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_odo')?></td>
			<td><?=InputType('text','odo','','',false,'','class="odo"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_mark')?></td>
			<td><? echo Lib\Fuel::showSelectFuelMarks("fuel_mark",$fuelMarkSelected,'class="fuel_mark"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_liters')?></td>
			<td><?=InputType('text','liters','','',false,'','class="liters"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_liter_cost')?></td>
			<td><?=InputType('text','cost_liter','','',false,'','class="cost_liter"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_full')?></td>
			<td><?=InputType('checkbox','full_tank',1,'',false,'','class="full_tank"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','fuel_point')?></td>
			<td><? echo Lib\Points::showSelectPoints("fuel_point",'null','class="fuel_point"',array('fuel'))?></td>
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
			<td><?=InputType('text','comment','','',false,'','class="comment"')?></td>
		</tr>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="<?=Loc::getPackMessage('icar','fuel_add')?>"></td>
		</tr>
	</table>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
