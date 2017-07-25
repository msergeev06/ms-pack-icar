<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Options;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','ts_title')." - ".CoreLib\Loc::getPackMessage('icar','ts_title_add'));

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	if (Lib\Ts::addFromPost($_POST)) {
		echo '<span class="ok">'.CoreLib\Loc::getPackMessage('icar','ts_add_success').'</span>';
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'ts/',3);
	}
	else {
		echo '<span class="err">'.CoreLib\Loc::getPackMessage('icar','all_add_error')
			.":<br>"
			.Lib\Errors::showErrorList()
			.'</span>';
	}
}


if (isset($_REQUEST['car']) && intval($_REQUEST['car'])>0)
{
	$carID = intval($_REQUEST['car']);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}

if (!$lastTs = Options::getOptionInt('icar_last_ts_'.$carID))
{
	$lastTs = 'null';
}
if (!$lastExecutor = Options::getOptionInt('icar_last_executor_'.$carID))
{
	$lastExecutor = 'null';
}
if (!$lastPoint = Options::getOptionInt('icar_last_executor_'.$carID.'_point'))
{
	$lastPoint = 'null';
}

//msDebug(Lib\Odo::getMaxOdo($carID));
?>
<form class="form-horizontal" role="form" name="add_ts" method="post" action="">
	<?=Lib\Fields::showCarIdField($carID)?>
	<?=Lib\Fields::showTsField($lastTs)?>
	<?=Lib\Fields::showDateField()?>
	<?=Lib\Fields::showExecutorsField($lastExecutor)?>
	<?=Lib\Fields::showCostField()?>
	<?=Lib\Fields::showOdoField()?>
	<?=Lib\Fields::showStartPointField($lastPoint,true,false,'',array('waypoint','service','other'))?>
	<?=Lib\Fields::showCommentField()?>
	<?//=Lib\Fields::showCheckField()?>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button>
		</div>
	</div>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
