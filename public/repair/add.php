<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Lib\Loc;

CoreLib\Buffer::setTitle("Ремонт - Добавление расходов на ремонт");

$carID = (isset($_REQUEST['car'])?intval($_REQUEST['car']):Lib\MyCar::getDefaultCarID());
$lastExecutor = CoreLib\Options::getOptionInt('icar_last_executor_'.$carID);
if (isset($_POST['action']))
{
	//Обработка формы
	if ($res = Lib\Repair::addFromPost($_POST)) {
		?><span class="ok">Данные успешно добавлены</span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'repair/',3);
		$bError = false;
	}
	else {
		?><span class="error">Ошибка добавления данных</span><?
		$bError = true;
		//msDebug($res);
	}
	if (Lib\Errors::issetWarningsAndErrors())
	{
		echo Lib\Errors::showErrorList();
	}

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
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<table class="table_form">
		<?=Lib\Fields::showCarIdField((($bError)?intval($_POST['my_car']):$carID))?>
		<?=Lib\Fields::showDateField($date)?>
		<?=Lib\Fields::showCostField((($bError)?$_POST['cost']:''))?>
		<?=Lib\Fields::showExecutorsField($lastExecutor)?>
		<?=Lib\Fields::showNameField((($bError)?$_POST['name']:''))?>
		<?=Lib\Fields::showOdoField((($bError)?$_POST['odo']:''))?>
		<?=Lib\Fields::showReasonReplacementField(
			$carID,
			(($bError)?intval($_POST['reason']):1),
			(($bError && isset($_POST['reason_ts']))?intval($_POST['reason_ts']):'null'),
			(($bError && isset($_POST['reason_breakdown']))?intval($_POST['reason_breakdown']):'null'),
			(($bError && isset($_POST['reason_dtp']))?intval($_POST['reason_dtp']):'null'),
			(($bError && isset($_POST['reason_tuning']))?intval($_POST['reason_tuning']):'null'),
			(($bError && isset($_POST['reason_upgrade']))?intval($_POST['reason_upgrade']):'null')
		)?>
		<?=Lib\Fields::showWhoPaidField((($bError)?intval($_POST['who_paid']):1))?>
		<?=Lib\Fields::showStartPointField(
			(($bError && intval($_POST['showSelectPoints'])>0)?intval($_POST['showSelectPoints']):'null'),
			true,
			false,
			'Путевая точка'
		)?>
		<?=Lib\Fields::showCommentField((($bError)?$_POST['comment']:''))?>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Добавить запись"></td>
		</tr>
	</table>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
