<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("ТО - Добавление данных о прохождении ТО");

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Options;

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
		echo '<span class="ok">Данные успешно добавлены</span>';
	}
	else {
		echo '<span class="err">Ошибка добавления данных</span>';
	}
}
//msDebug(Lib\Odo::getMaxOdo($carID));
?>
<form action="" method="post">
	<table class="add_ts">
		<tr>
			<td class="title">Автомобиль</td>
			<td><? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?></td>
		</tr>
		<tr>
			<td class="title">Номер ТО</td>
			<td><? echo Lib\Ts::showSelectTsNum("ts_num",$lastTs); ?></td>
		</tr>
		<tr>
			<td class="title">Дата</td>
			<td><?=InputCalendar ('date', date('d.m.Y'), 'class="calendarDate"', $strId="")?></td>
		</tr>
		<tr>
			<td class="title">Исполнитель работ</td>
			<td><?=Lib\Ts::showSelectExecutor("executor",$lastExecutor)?></td>
		</tr>
		<tr>
			<td class="title">Стоимость</td>
			<td><?=InputType('text','cost','','',false,'','class="cost"')?></td>
		</tr>
		<tr>
			<td class="title">Пробег</td>
			<td><?=InputType('text','odo','','',false,'','class="odo"')?></td>
		</tr>
		<tr>
			<td class="title">Путевая точка</td>
			<td><? echo Lib\Points::showSelectPoints("ts_point",$lastPoint,'class="ts_point"')?></td>
		</tr>
		<tr>
			<td class="center" colspan="2">или</td>
		</tr>
		<tr>
			<td class="title">Имя новой точки</td>
			<td><?=InputType('text','newpoint_name','','',false,'','class="newpoint_name"')?></td>
		</tr>
		<tr>
			<td class="title">Адрес новой точки</td>
			<td><?=InputType('text','newpoint_address','','',false,'','class="newpoint_address"')?></td>
		</tr>
		<tr>
			<td class="title">Широта (55.12345)</td>
			<td><?=InputType('text','newpoint_lat','','',false,'','class="newpoint_lat"')?></td>
		</tr>
		<tr>
			<td class="title">Долгота (37.12345)</td>
			<td><?=InputType('text','newpoint_lon','','',false,'','class="newpoint_lon"')?></td>
		</tr>
		<tr>
			<td class="title">Комментарий</td>
			<td><?=InputType('text','comment','','',false,'','class="comment"')?></td>
		</tr>
		<tr>
			<td class="center" colspan="2"><input type="hidden" name="action" value="1"><input type="submit" value="Добавить запись"></td>
		</tr>
	</table>
</form>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
