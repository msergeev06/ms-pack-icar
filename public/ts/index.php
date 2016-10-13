<? include_once(__DIR__."/../include/header.php"); MSergeev\Core\Lib\Buffer::setTitle("ТО");

use MSergeev\Packages\Icar\Lib;
if (isset($_REQUEST['car']) && intval($_REQUEST['car'])>0)
{
	$carID = intval($_REQUEST['car']);
}
else
{
	$carID = Lib\MyCar::getDefaultCarID();
}

if (!isset($_REQUEST['page']))
{
	$page = 1;
}
else
{
	$page = intval($_REQUEST['page']);
}
?>
<p>Вы можете указать стоимость ТО двумя способами:
	<ul>
		<li>1. Указать явно стоимость ТО.</li>
		<li>2. Не указывать стоимость, но указать в разделах запчасти и ремонт, что расходы использованы в рамках ТО и указать номер ТО, в этом случае сумма затрат на ТО будет считаться автоматически.</li>
	</ul>
</p>
<p>
	Статистика для: <? echo Lib\MyCar::showSelectCars("my_car",$carID,'class="myCar"'); ?><br>
	Общие затраты на ТО: <?=Lib\Ts::getTotalMaintenanceCostsFormatted($carID)?> руб.<br><br>
</p>
<p><a href="add.php?car=<?=$carID?>">Добавить запись</a><br><br></p>

<? Lib\Ts::showListTable($carID); ?>

<p><a href="add.php?car=<?=$carID?>">Добавить запись</a></p>
<? $curDir = basename(__DIR__); include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
