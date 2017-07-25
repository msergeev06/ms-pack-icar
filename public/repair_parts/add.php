<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','rp_title')." - ".CoreLib\Loc::getPackMessage('icar','rp_title_add'));

$bError = false;

if (isset($_POST["action"]))
{
	if ($res = Lib\RepairParts::addFromPost($_POST)) {
		?><span class="ok"><?=CoreLib\Loc::getPackMessage('icar','all_add_success')?></span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'repair_parts/',3);
		$bError = false;
	}
	else {
		?><span class="error"><?=CoreLib\Loc::getPackMessage('icar','all_add_error')?>:</span><br><?=(Lib\Errors::issetWarningsAndErrors())?Lib\Errors::showErrorList():''?><?
		$bError = true;
		//msDebug($res);
	}
}

if (isset($_REQUEST['car']))
{
	$carID = intval($_REQUEST["car"]);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}

if (isset($_POST['date']))
{
	$date = $_POST['date'];
}
else
{
	$date = date('d.m.Y');
}

$pService = Lib\Points::getPointTypeIdByCode ("service");
$pStore = Lib\Points::getPointTypeIdByCode ("shop");
$pCarwash = Lib\Points::getPointTypeIdByCode ("wash");
?>
<form class="form-horizontal" role="form" name="add_ts" method="post" action="">
	<?=Lib\Fields::showCarIdField((($bError)?intval($_POST['my_car']):$carID))?>
	<?=Lib\Fields::showDateField($date)?>
	<?=Lib\Fields::showNameField((($bError)?$_POST['name']:''))?>
	<?=Lib\Fields::showStorageField((($bError)?intval($_POST['storage']):1))?>
	<?=Lib\Fields::showCatalogNumberField((($bError)?$_POST['catalog_number']:''))?>
	<?=Lib\Fields::showNumberField((($bError)?$_POST['number']:''))?>
	<?=Lib\Fields::showCostField((($bError)?$_POST['cost']:''))?>
	<?=Lib\Fields::showReasonReplacementField(
		(($bError)?intval($_POST['my_car']):$carID),
		(($bError)?intval($_POST['reason']):1),
		(($bError && isset($_POST['reason_ts']))?intval($_POST['reason_ts']):'null'),
		(($bError && isset($_POST['reason_breakdown']))?intval($_POST['reason_breakdown']):'null'),
		(($bError && isset($_POST['reason_dtp']))?intval($_POST['reason_dtp']):'null'),
		(($bError && isset($_POST['reason_tuning']))?intval($_POST['reason_tuning']):'null'),
		(($bError && isset($_POST['reason_upgrade']))?intval($_POST['reason_upgrade']):'null')
	)?>
	<?=Lib\Fields::showWhoPaidField((($bError)?intval($_POST['who_paid']):1))?>
	<?=Lib\Fields::showOdoField((($bError)?$_POST['odo']:''))?>
	<?=Lib\Fields::showStartPointField((($bError && intval($_POST['start_point'])>0)?intval($_POST['start_point']):'null'))?>
	<?=Lib\Fields::showCommentField((($bError)?$_POST['comment']:''))?>
	<input type="hidden" name="action" value="1">
	<input type="hidden" name="car" value="<?=$carID?>">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button>
		</div>
	</div>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
