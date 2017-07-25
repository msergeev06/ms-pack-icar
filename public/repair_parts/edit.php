<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

CoreLib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','rp_title')." - Редактирование информации о приобретенных зап.частях");

$bError = false;

if (isset($_POST["action"]))
{
	if ($res = Lib\RepairParts::updateFromPost($_POST)) {
		?><span style="color: green;">Данные успешно сохранены</span><?
		CoreLib\Buffer::setRefresh(CoreLib\Loader::getSitePublic('icar').'repair_parts/',3);
		$bError = false;
	}
	else {
		?><span style="color: red;">Ошибка сохранения данных<? //=Lib\RepairParts::showErrorList()?></span><?
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

if (isset($_GET['id']))
{
	$arRepairParts = Lib\RepairParts::getList($carID,intval($_GET['id']),1);
	$reasonCode = Lib\ReasonReplacement::getCodeById($arRepairParts['REASON_REPLACEMENT_ID']);
	switch ($reasonCode)
	{
		case 'ts':
			$show = 'ts';
			break;
		case 'breakdown':
			$show = 'breakdown';
			break;
		case 'tuning':
			$show = 'tuning';
			break;
		case 'upgrade':
			$show = 'upgrade';
			break;
		case 'tire':
			$show = 'tire';
			break;
		case 'accident':
			$show = 'accident';
			break;
		default:
			$show = '';
			return false;
	}
}
else
{
	$show = '';
}


$pService = Lib\Points::getPointTypeIdByCode ("service");
$pStore = Lib\Points::getPointTypeIdByCode ("shop");
$pCarwash = Lib\Points::getPointTypeIdByCode ("wash");
?>
<?if(!isset($_POST["action"])):?>
<form action="" method="post">
	<input type="hidden" name="car" value="<?=$carID?>">
	<input type="hidden" name="id" value="<?=intval($_GET['id'])?>">
	<table class="table_form">
		<?=Lib\Fields::showCarIdField(isset($arRepairParts['MY_CAR_ID'])?$arRepairParts['MY_CAR_ID']:$carID)?>
		<?=Lib\Fields::showDateField(isset($arRepairParts['DATE'])?$arRepairParts['DATE']:date('d.m.Y'))?>
		<?=Lib\Fields::showNameField(isset($arRepairParts['NAME'])?$arRepairParts['NAME']:'')?>
		<?=Lib\Fields::showStorageField(isset($arRepairParts['STORAGE_ID'])?$arRepairParts['STORAGE_ID']:1)?>
		<?=Lib\Fields::showCatalogNumberField(isset($arRepairParts['CATALOG_NUMBER'])?$arRepairParts['CATALOG_NUMBER']:'')?>
		<?=Lib\Fields::showNumberField(isset($arRepairParts['NUMBER'])?$arRepairParts['NUMBER']:'')?>
		<?=Lib\Fields::showCostField(isset($arRepairParts['COST'])?$arRepairParts['COST']:'')?>
		<?=Lib\Fields::showReasonReplacementField(
			(isset($arRepairParts['MY_CAR_ID'])?$arRepairParts['MY_CAR_ID']:$carID),
			(isset($arRepairParts['REASON_REPLACEMENT_ID'])?$arRepairParts['REASON_REPLACEMENT_ID']:1),
			((isset($arRepairParts['TS_ID']) && $arRepairParts['TS_ID']>0)?intval($arRepairParts['TS_ID']):'null'),
			((isset($arRepairParts['REPAIR_ID']) && $arRepairParts['REPAIR_ID']>0)?intval($arRepairParts['REPAIR_ID']):'null'),
			((isset($arRepairParts['ACCIDENT_ID']) && $arRepairParts['ACCIDENT_ID']>0)?intval($arRepairParts['ACCIDENT_ID']):'null'),
			((isset($arRepairParts['REPAIR_ID']) && $arRepairParts['REPAIR_ID']>0)?intval($arRepairParts['REPAIR_ID']):'null'),
			((isset($arRepairParts['REPAIR_ID']) && $arRepairParts['REPAIR_ID']>0)?intval($arRepairParts['REPAIR_ID']):'null'),
			$show
		)?>
		<?=Lib\Fields::showWhoPaidField(isset($arRepairParts['WHO_PAID_ID'])?$arRepairParts['WHO_PAID_ID']:1)?>
		<?=Lib\Fields::showOdoField(isset($arRepairParts['ODO'])?$arRepairParts['ODO']:'')?>
		<?=Lib\Fields::showStartPointField(isset($arRepairParts['POINTS_ID'])?$arRepairParts['POINTS_ID']:'null')?>
		<?=Lib\Fields::showCommentField(isset($arRepairParts['DESCRIPTION'])?$arRepairParts['DESCRIPTION']:'')?>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Сохранить изменения"></td>
		</tr>
	</table>
</form>
<?endif;?>
<? $curDir = basename(__DIR__); ?>
<? include_once(CoreLib\Loader::getPublic("icar")."include/footer.php"); ?>
