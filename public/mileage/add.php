<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','odo_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','odo_adding_route'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

$defaultCar = Lib\MyCar::getDefaultCarID();

if (isset($_GET['car']))
{
	$carID = intval($_GET['car']);
}
else
{
	$carID = $defaultCar;
}
$start = 'null';
if (isset($_POST["action"])&&intval($_POST["action"])==1) {
	if (intval($_POST['end_point'])>0)
	{
		$start = intval($_POST['end_point']);
	}
	else
	{
		$start = 'null';
	}
	if (Lib\Odo::addNewRouteFromPost($_POST)) {
		echo '<span class="ok">'.Loc::getPackMessage('icar','odo_add_success').'</span>';
	}
	else {
		echo '<span class="err">'.Loc::getPackMessage('icar','odo_add_error').'</span>';
	}
}
?>
<form name="add_route" method="POST">
	<table style="border: 0;">
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_car')?></td>
			<td class="value"><? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_date')?></td>
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
			<td class="value"><?=InputCalendar ('date', $date, 'class="calendarDate"', $strId="")?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_mileage')?></td>
			<td class="value"><?=InputType('text','odo','','',false,'','class="odo"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_start_point')?></td>
			<td class="value"><? echo Lib\Points::showSelectPoints("start_point",$start,'class="start_point"')?></td>
		</tr>
		<tr>
			<td class="name">&nbsp;</td>
			<td class="value"><?=Loc::getPackMessage('icar','odo_or')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_name')?></td>
			<td class="value"><?=InputType('text','start_name','','',false,'','class="start_name"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_address')?></td>
			<td class="value"><?=InputType('text','start_address','','',false,'','class="start_address"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_lat')?></td>
			<td class="value"><?=InputType('text','start_lat','','',false,'','class="start_lat"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_lon')?></td>
			<td class="value"><?=InputType('text','start_lon','','',false,'','class="start_lon"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_local_route')?></td>
			<td class="value"><?=InputType('checkbox','end_start',1,'',false,'','class="end_start"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_end_point')?></td>
			<td class="value"><? echo Lib\Points::showSelectPoints("end_point",'null','class="end_point"')?></td>
		</tr>
		<tr>
			<td class="name">&nbsp;</td>
			<td class="value"><?=Loc::getPackMessage('icar','odo_or')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_name')?></td>
			<td class="value"><?=InputType('text','end_name','','',false,'','class="end_name"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_address')?></td>
			<td class="value"><?=InputType('text','end_address','','',false,'','class="end_address"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_lat')?></td>
			<td class="value"><?=InputType('text','end_lat','','',false,'','class="end_lat"')?></td>
		</tr>
		<tr>
			<td class="name"><?=Loc::getPackMessage('icar','odo_new_lon')?></td>
			<td class="value"><?=InputType('text','end_lon','','',false,'','class="end_lon"')?></td>
		</tr>
		<tr>
			<td class="name"><input type="hidden" name="action" value="1"></td>
			<td class="value"><input type="submit" value="<?=Loc::getPackMessage('icar','odo_add_route')?>"></td>
		</tr>
	</table>
</form>
<style>
	.name {
		text-align: right;
	}
	.value {
		text-align: left;
	}
	.ok {
		color: green;
	}
	.err {
		color: red;
	}
</style>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
