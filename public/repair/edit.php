<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle("Ремонт - Редактирование записи о расходе на ремонт");

$carID = (isset($_REQUEST['car'])?intval($_REQUEST['car']):Lib\MyCar::getDefaultCarID());
$lastExecutor = CoreLib\Options::getOptionInt('icar_last_executor_'.$carID);

if (isset($_POST['action']))
{
	//Обработка формы
	if ($res = Lib\Repair::updateFromPost($_POST)) {
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
if (isset($_REQUEST['id']))
{
	$arRepair = Lib\Repair::getList($carID,intval($_GET['id']));
	$reasonCode = Lib\ReasonReplacement::getCodeById($arRepair['REASON_REPLACEMENT_ID']);
	if ($reasonCode!==false)
	{
		switch ($reasonCode)
		{
			case 'ts':
				$show = 'ts';
				break;
			case 'accident';
				$show = 'accident';
				break;
			default:
				$show = 'tire';
				break;
		}
	}
	else
	{
		$show = '';
	}
?>
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
	<table class="table_form">
		<?=Lib\Fields::showCarIdField($arRepair['MY_CAR_ID'])?>
		<?=Lib\Fields::showDateField($arRepair['DATE'])?>
		<?=Lib\Fields::showCostField($arRepair['COST'])?>
		<?=Lib\Fields::showExecutorsField($arRepair['EXECUTOR_ID'])?>
		<?=Lib\Fields::showNameField($arRepair['NAME'])?>
		<?=Lib\Fields::showOdoField($arRepair['ODO'])?>
		<?=Lib\Fields::showReasonReplacementField(
			$arRepair['MY_CAR_ID'],
			$arRepair['REASON_REPLACEMENT_ID'],
			$arRepair['TS_ID'],
			'null',
			$arRepair['ACCIDENT_ID'],
			'null',
			'null',
			$show
		)?>
		<?=Lib\Fields::showWhoPaidField($arRepair['WHO_PAID_ID'])?>
		<?=Lib\Fields::showStartPointField(
			$arRepair['POINTS_ID'],
			true,
			false,
			'Путевая точка'
		)?>
		<?=Lib\Fields::showCommentField($arRepair['INFO'])?>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Добавить запись"></td>
		</tr>
	</table>
</form>
<?
}

?>
<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
