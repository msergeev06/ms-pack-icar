<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','rp_title')." - ".MSergeev\Core\Lib\Loc::getPackMessage('icar','rp_title_add'));
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Lib\Loc;

if (isset($_REQUEST['car']))
{
	$carID = intval($_REQUEST["car"]);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}
$pService = Lib\Points::getPointTypeIdByCode ("service");
$pStore = Lib\Points::getPointTypeIdByCode ("shop");
$pCarwash = Lib\Points::getPointTypeIdByCode ("wash");
$bError = false;
if (isset($_POST["action"])) {
	if ($res = Lib\RepairParts::addRepairPartsFromPost($_POST)) {
		?><span style="color: green;"><?=Loc::getPackMessage('icar','rp_add_success')?></span><?
		$bError = false;
	}
	else {
		?><span style="color: red;"><?=Loc::getPackMessage('icar','rp_add_error')?>:<?=Lib\RepairParts::showErrorList()?></span><?
		$bError = true;
		//msDebug($res);

	}
}
?>
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<table class="add_ts">
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_car')?></td>
			<td><?=Lib\MyCar::showSelectCars("my_car",(($bError)?intval($_POST['my_car']):$carID),'class="myCar"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_date')?></td>
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
			<td class="title"><?=Loc::getPackMessage('icar','rp_name')?></td>
			<td><?=InputType('text','name',(($bError)?$_POST['name']:''),'',false,'','class="name"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_storage')?></td>
			<td><?=Lib\Storage::showSelectStorageList("storage",'',(($bError)?intval($_POST['storage']):1))?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_catalog_number')?></td>
			<td><?=InputType('text','catalog_number',(($bError)?$_POST['catalog_number']:''),'',false,'','class="catalog_number"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_number')?></td>
			<td><?=InputType('text','number',(($bError)?$_POST['number']:''),'',false,'','class="number"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_cost')?></td>
			<td><?=InputType('text','cost',(($bError)?$_POST['cost']:''),'',false,'','class="cost"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_reason')?></td>
			<td><?=Lib\ReasonReplacement::showSelectReasonReplacementList("reason",'',(($bError)?intval($_POST['reason']):1))?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_reason_add')?></td>
			<td class="reason_add">
				<?=Lib\Ts::showSelectTsList($carID,"reason_ts",Loc::getPackMessage('icar','rp_not_select'),(($bError && isset($_POST['reason_ts']))?intval($_POST['reason_ts']):'null'),'id="reason_ts" class="tslistselect"')?>
				<?=Lib\Repair::showSelectRepairList($carID, "reason_breakdown",Loc::getPackMessage('icar','rp_not_select'),(($bError && isset($_POST['reason_breakdown']))?intval($_POST['reason_breakdown']):'null'),'id="reason_breakdown" class="repairlistselect" style="display: none;"')?>
				<?=Lib\Accident::showSelectAccidentList($carID, "reason_dtp",Loc::getPackMessage('icar','rp_not_select'),(($bError && isset($_POST['reason_dtp']))?intval($_POST['reason_dtp']):'null'),'id="reason_dtp" class="accidentlistselect" style="display: none;"')?>
				<?=Lib\Repair::showSelectRepairList($carID, "reason_tuning",Loc::getPackMessage('icar','rp_not_select'),(($bError && isset($_POST['reason_tuning']))?intval($_POST['reason_tuning']):'null'),'id="reason_tuning" class="repairlistselect" style="display: none;"')?>
				<?=Lib\Repair::showSelectRepairList($carID, "reason_upgrade",Loc::getPackMessage('icar','rp_not_select'),(($bError && isset($_POST['reason_upgrade']))?intval($_POST['reason_upgrade']):'null'),'id="reason_upgrade" class="repairlistselect" style="display: none;"')?>
				<span class="reason_tire" style="display: none;">-</span>
			</td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_who_paid')?></td>
			<td><?=Lib\WhoPaid::showSelectWhoPaidList("who_paid",(($bError)?intval($_POST['who_paid']):1))?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_odo')?></td>
			<td><?=InputType('text','odo',(($bError)?$_POST['odo']:''),'',false,'','class="odo"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_point')?></td>
			<td><? echo Lib\Points::showSelectPoints("ts_point",(($bError && intval($_POST['showSelectPoints'])>0)?intval($_POST['showSelectPoints']):'null'))?></td>
		</tr>
		<tr>
			<td class="center" colspan="2"><?=Loc::getPackMessage('icar','rp_or')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_new_point_name')?></td>
			<td><?=InputType('text','newpoint_name',(($bError)?$_POST['newpoint_name']:''),'',false,'','class="newpoint_name"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_new_point_address')?></td>
			<td><?=InputType('text','newpoint_address',(($bError)?$_POST['newpoint_address']:''),'',false,'','class="newpoint_address"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_new_point_lat')?></td>
			<td><?=InputType('text','newpoint_lat',(($bError)?$_POST['newpoint_lat']:''),'',false,'','class="newpoint_lat"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_new_point_lon')?></td>
			<td><?=InputType('text','newpoint_lon',(($bError)?$_POST['newpoint_lon']:''),'',false,'','class="newpoint_lon"')?></td>
		</tr>
		<tr>
			<td class="title"><?=Loc::getPackMessage('icar','rp_comment')?></td>
			<td><?=InputType('text','comment',(($bError)?$_POST['comment']:''),'',false,'','class="comment"')?></td>
		</tr>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="<?=Loc::getPackMessage('icar','rp_add')?>"></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	function hideAllReason()
	{
		$('#reason_ts').hide();
		$('#reason_breakdown').hide();
		$('#reason_dtp').hide();
		$('#reason_tuning').hide();
		$('#reason_upgrade').hide();
		$(".reason_tire").hide();
	}
	$(document).on("ready",function(){
		$(".reasonreplacementselect").on("change",function(){
			var sel = $(this).val();
			if (sel==1) {
				hideAllReason();
				$("#reason_ts").show();
			}
			else if (sel==2) {
				hideAllReason();
				$("#reason_breakdown").show();
			}
			else if (sel==3) {
				hideAllReason();
				$("#reason_tuning").show();
			}
			else if (sel==4) {
				hideAllReason();
				$("#reason_upgrade").show();
			}
			else if (sel==5) {
				hideAllReason();
				$(".reason_tire").show();
			}
			else if (sel==6) {
				hideAllReason();
				$("#reason_dtp").show();
			}
		});
	});
</script>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
