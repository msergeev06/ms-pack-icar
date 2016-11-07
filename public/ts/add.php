<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','ts_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','ts_title_add'));

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

$lastTs = Options::getOptionInt('icar_last_ts_'.$carID);
$lastExecutor = Options::getOptionInt('icar_last_executor_'.$carID);
$lastPoint = Options::getOptionInt('icar_last_executor_'.$carID.'_point');

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	if (Lib\Ts::addTsFromPost($_POST)) {
		echo '<span class="ok">'.Loc::getPackMessage('icar','ts_add_success').'</span>';
	}
	else {
		echo '<span class="err">'.Loc::getPackMessage('icar','ts_add_error').'</span>';
	}
}
//msDebug(Lib\Odo::getMaxOdo($carID));
?>
<form action="" method="post">
	<table class="add_ts">
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_car')?></td>
			<td><? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_num')?></td>
			<td><? echo Lib\Ts::showSelectTsNum("ts_num",$lastTs); ?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_date')?></td>
			<td><?=InputCalendar ('date', date('d.m.Y'), 'class="calendarDate"', $strId="")?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_executor')?></td>
			<td><?=Lib\Ts::showSelectExecutor("executor",$lastExecutor)?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_cost')?></td>
			<td><?=InputType('text','cost','','',false,'','class="cost"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_odo')?></td>
			<td><?=InputType('text','odo','','',false,'','class="odo"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_point')?></td>
			<td><? echo Lib\Points::showSelectPoints("ts_point",$lastPoint,'class="ts_point"')?></td>
		</tr>
		<tr>
			<td class="center" colspan="2">или</td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_new_point_name')?></td>
			<td><?=InputType('text','newpoint_name','','',false,'','class="newpoint_name"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_new_point_address')?></td>
			<td><?=InputType('text','newpoint_address','','',false,'','class="newpoint_address"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_new_point_lat')?></td>
			<td><?=InputType('text','newpoint_lat','','',false,'','class="newpoint_lat"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_new_point_lon')?></td>
			<td><?=InputType('text','newpoint_lon','','',false,'','class="newpoint_lon"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','ts_comment')?></td>
			<td><?=InputType('text','comment','','',false,'','class="comment"')?></td>
		</tr>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="<?=Loc::getPackMessage('icar','ts_add')?>"></td>
		</tr>
	</table>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
