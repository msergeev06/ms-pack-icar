<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(
	CoreLib\Loc::getPackMessage('icar','odo_title')." - ".CoreLib\Loc::getPackMessage('icar','odo_adding_route')
);

if (isset($_POST["action"])&&intval($_POST["action"])==1) {

	if (Lib\Odo::addNewRouteFromPost($_POST)) {
		?><div class="text-success"><?=CoreLib\Loc::getPackMessage('icar','all_add_success')?></div><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'mileage/',3);
	}
	else {
		?><div class="text-danger"><?=CoreLib\Loc::getPackMessage('icar','all_add_error')?></div><?
	}
}


if (isset($_GET['car']))
{
	$carID = intval($_GET['car']);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}


if (!$start = CoreLib\Options::getOptionInt('icar_'.$carID.'_last_point'))
{
	$start = 'null';
}

if (isset($_POST['date']))
{
	$date = $_POST['date'];
}
else
{
	$date = date('d.m.Y');
}

?>
<form class="form-horizontal" role="form" name="add_route" method="post" action="">
	<?=Lib\Fields::showCarIdField()?>
	<?=Lib\Fields::showDateField($date)?>
	<?=Lib\Fields::showOdoField()?>
	<?=Lib\Fields::showStartPointField($start,true,true)?>
	<?//=Lib\Fields::showEndPointField('null',true)?>
	<?=Lib\Fields::showMobileGpsField('null','end')?>
	<input type="hidden" name="action" value="1">
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="submit btn btn-success"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></button>
		</div>
	</div>
</form>


<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
