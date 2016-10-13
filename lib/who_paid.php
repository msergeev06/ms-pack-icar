<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\WhoPaidTable;

class WhoPaid
{
	public static function showSelectWhoPaidList ($strBoxName, $selected=1, $field1="class=\"whopaidselect\"")
	{
		$arRes = WhoPaidTable::getList(array(
			'select' => array('ID'=>'VALUE','NAME'),
			'filter' => array('ACTIVE'=>true),
			'order' => array('SORT'=>'ASC')
		));

		return SelectBox($strBoxName, $arRes, '', $selected, $field1);
	}
}