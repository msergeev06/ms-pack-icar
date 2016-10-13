<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("Пробег");

use MSergeev\Packages\Icar\Lib;
$defaultCar = Lib\MyCar::getDefaultCarID();
?>

<p>Статистика для: <? echo Lib\MyCar::showSelectCars("my_car",$defaultCar,'class="myCar"'); ?><br><br></p>
<p><a class="linkAdd" href="add.php">Добавить маршрут</a></p>


<p><select name="period" id="period_select">
	<option value="1" selected>Текущий месяц</option>
	<option value="2">Прошлый месяц</option>
	<option value="3">За год</option>
</select>&nbsp;&nbsp;Показать за период с <?=InputCalendar ('from', date("d.m.Y"), 'class="calendarFrom"', $strId="")?> по <?=InputCalendar ('to', date("d.m.Y"), 'class="calendarTo"', $strId="")?> <a href="#">Показать</a></p>

<div class="charts"><? echo Lib\Odo::showChartsOdo('01.'.date("m.Y"),date("d.m.Y"),$defaultCar); ?></div>
<script type="text/javascript">
	$(document).on("ready",function(){
		var sel,car;

		$("#period_select").on("change",function(){
			sel = $(this).val();
			car = $('.myCar').val();

			//$(".charts").html('<iframe src="/msergeev/investtocar/include/tools/getchartsodo.php?chartWidth='+chartWidth+'&chartHeight='+chartHeight+'&type='+sel+'&xTitle='+xTitle+'&yTitle='+yTitle+'" scrolling="no" frameborder="no" width="'+chartWidth+'" height="'+chartHeight+'" align="left"></iframe>');

		});

		$(".update").on("click", function(){
			//$.post("/msergeev/investtocar/include/tools/update_day_odo.php",function(){});
		});
	});
</script>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
