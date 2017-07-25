<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle(MSergeev\Core\Lib\Loc::getPackMessage('icar','odo_title'));

use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib\Loc;

$defaultCar = Lib\MyCar::getDefaultCarID();
?>

<p><?=Loc::getPackMessage('icar','all_statistic_for')?>: <? echo Lib\MyCar::showSelectCars("my_car",$defaultCar,'class="myCar"'); ?><br><br></p>
<p><a class="linkAdd" href="add.php"><button type="button" class="btn btn-primary"><?=Loc::getPackMessage('icar','odo_add_route')?></button></a><br><br></p>


<p class="visible-md visible-lg"><select name="period" id="period_select">
	<option value="1" selected><?=Loc::getPackMessage('icar','odo_now_month')?></option>
	<option value="2"><?=Loc::getPackMessage('icar','odo_last_month')?></option>
	<option value="3"><?=Loc::getPackMessage('icar','odo_for_year')?></option>
</select>&nbsp;&nbsp;<?=Loc::getPackMessage('icar','odo_display_period_from')?> <?=InputDate ('from', date("Y-m").'-01', 'class="calendarFrom"')?> <?=Loc::getPackMessage('icar','odo_display_period_to')?> <?=InputDate ('to', date("Y-m-d"), 'class="calendarTo"')?> <a class="show_period" href="#"><?=Loc::getPackMessage('icar','odo_show')?></a></p>

<div class="charts visible-md visible-lg"><? echo Lib\Odo::showChartsOdo('01.'.date("m.Y"),date("d.m.Y"),$defaultCar); ?></div>
<script type="text/javascript">
	$(document).on("ready",function(){
		var sel,car;

		$("#period_select").on("change",function(){
			sel = $(this).val();
			car = $('.myCar').val();

			$(".charts").html('<iframe src="/msergeev/packages/icar/tools/getchartsodo.php?sel='+sel+'&car='+car+'" scrolling="no" frameborder="no" width="100%" height="450" align="left"></iframe>');

		});

		$(".show_period").on("click",function(){
			car = $('.myCar').val();
			from = $('#from').val();
			to = $('#to').val();

			$(".charts").html('<iframe src="/msergeev/packages/icar/tools/getchartsodo.php?from='+from+'&to='+to+'&car='+car+'" scrolling="no" frameborder="no" width="100%" height="450" align="left"></iframe>');
		});

		$(".update").on("click", function(){
			//$.post("/msergeev/packages/icar/tools/update_day_odo.php",function(){});
		});
	});
</script>


<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
