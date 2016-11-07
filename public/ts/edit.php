<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','ts_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','ts_title_edit'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

if (isset($_REQUEST['id']) && intval($_REQUEST['id'])>0)
{
	$editID = intval($_REQUEST['id']);
}
else
{
	die("ERROR");
}

if (!isset($_POST["action"]))
{
	if (!$arTs = Lib\Ts::getTsList (NULL, $editID))
	{
		die("ERROR");
	}
	//msDebug($arTs);
	?>
	<form action="" method="post">
		<table class="add_ts">
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_car')?></td>
				<td><? echo Lib\MyCar::showSelectCars("my_car",$arTs[0]["MY_CAR_ID"],'class="myCar"'); ?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_num')?></td>
				<td><? echo Lib\Ts::showSelectTsNum("ts_num",$arTs[0]["TS_NUM"]); ?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_date')?></td>
				<td><?=InputCalendar ('date', $arTs[0]["DATE"], 'class="calendarDate"', $strId="")?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_executor')?></td>
				<td><?=Lib\Ts::showSelectExecutor("executor",$arTs[0]["EXECUTORS_ID"])?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_cost')?></td>
				<td><?=InputType('text','cost',$arTs[0]["COST"],'',false,'','class="cost"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_odo')?></td>
				<td><?=InputType('text','odo',$arTs[0]["ODO"],'',false,'','class="odo"')?></td>
			</tr>
			<tr>
				<td class="title"><?=Loc::getPackMessage('icar','ts_point')?></td>
				<td><? echo Lib\Points::showSelectPoints("ts_point",$arTs[0]["POINTS_ID"],'class="ts_point"')?></td>
			</tr>
			<tr>
				<td class="center" colspan="2"><?=Loc::getPackMessage('icar','ts_or')?></td>
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
				<td><?=InputType('text','comment',$arTs[0]["INFO"],'',false,'','class="comment"')?></td>
			</tr>
			<tr>
				<td class="center" colspan="2">
					<input type="hidden" name="action" value="1">
					<input type="hidden" name="edit_id" value="<?=$editID?>">
					<input type="submit" value="<?=Loc::getPackMessage('icar','all_edit')?>">
				</td>
			</tr>
		</table>
	</form>

<?
}
else {
	$arPost = $_POST;
	//msDebug($arPost);
	if ($res = Lib\Ts::updateTsFromPost(intval($arPost['edit_id']),$arPost)) {
		?><span style="color: green;"><?=Loc::getPackMessage('icar','ts_edit_success')?></span><?
	}
	else {
		?><span style="color: red;"><?=Loc::getPackMessage('icar','ts_edit_error')?></span><?
	}
	//echo "<pre>"; print_r($arPost); echo "</pre>";
}

?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
