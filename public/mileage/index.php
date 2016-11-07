<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','odo_title'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

$defaultCar = Lib\MyCar::getDefaultCarID();
?>

<p><?=Loc::getPackMessage('icar','odo_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$defaultCar,'class="myCar"'); ?><br><br></p>
<p><a class="linkAdd" href="add.php"><?=Loc::getPackMessage('icar','odo_add_route')?></a></p>


<p><select name="period" id="period_select">
	<option value="1" selected><?=Loc::getPackMessage('icar','odo_now_month')?></option>
	<option value="2"><?=Loc::getPackMessage('icar','odo_last_month')?></option>
	<option value="3"><?=Loc::getPackMessage('icar','odo_for_year')?></option>
</select>&nbsp;&nbsp;<?=Loc::getPackMessage('icar','odo_display_period_from')?> <?=InputCalendar ('from', date("d.m.Y"), 'class="calendarFrom"', $strId="")?> <?=Loc::getPackMessage('icar','odo_display_period_to')?> <?=InputCalendar ('to', date("d.m.Y"), 'class="calendarTo"', $strId="")?> <a href="#"><?=Loc::getPackMessage('icar','odo_show')?></a></p>

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
