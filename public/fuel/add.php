<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Топливо - Добавление данных о заправке");

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

if (!$fuelMarkSelected = Options::getOptionInt('icar_last_fuelmark_'.$carID))
{
	$fuelMarkSelected = 'null';
}

if (isset($_POST['action']) && intval($_POST['action'])==1)
{
	if (Lib\Fuel::addFuelFromPost($_POST)) {
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
			<td class="title">Дата</td>
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
			<td class="title">Пробег</td>
			<td><?=InputType('text','odo','','',false,'','class="odo"')?></td>
		</tr>
		<tr>
			<td class="title">Марка топлива</td>
			<td><? echo Lib\Fuel::showSelectFuelMarks("fuel_mark",$fuelMarkSelected,'class="fuel_mark"')?></td>
		</tr>
		<tr>
			<td class="title">Литраж</td>
			<td><?=InputType('text','liters','','',false,'','class="liters"')?></td>
		</tr>
		<tr>
			<td class="title">Цена за литр</td>
			<td><?=InputType('text','cost_liter','','',false,'','class="cost_liter"')?></td>
		</tr>
		<tr>
			<td class="title">Полный бак</td>
			<td><?=InputType('checkbox','full_tank',1,'',false,'','class="full_tank"')?></td>
		</tr>
		<tr>
			<td class="title">Путевая точка</td>
			<td><? echo Lib\Points::showSelectPoints("fuel_point",'null','class="fuel_point"',array('fuel'))?></td>
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
