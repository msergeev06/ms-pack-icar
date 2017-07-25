<? include_once(__DIR__."/../include/header.php");
use MSergeev\Packages\Icar\Lib;
use MSergeev\Core\Lib as CoreLib;

MSergeev\Core\Lib\Buffer::setTitle(CoreLib\Loc::getPackMessage('icar','points_title'));

?>
<p><a href="add.php?car=<?=$carID?>"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></a><br><br></p>

<? Lib\Points::showListTable(); ?>

<p><a href="add.php?car=<?=$carID?>"><?=CoreLib\Loc::getPackMessage('icar','all_add')?></a><br><br></p>

<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>



<? $curDir = basename(__DIR__); ?>
<? include_once(MSergeev\Core\Lib\Loader::getPublic("icar")."include/footer.php"); ?>
