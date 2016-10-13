<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\StorageTable;

class Storage
{
	public static function showSelectStorageList ($strBoxName, $strDetText='--- Выбрать ---', $strSelectedVal = "null", $field1="class=\"storageselect\"")
	{
		$arRes = StorageTable::getList(array(
			'select' => array('ID'=>'VALUE','NAME'),
			'filter' => array('ACTIVE'=>true),
			'order' => array('SORT'=>'ASC')
		));

		return SelectBox($strBoxName, $arRes, $strDetText, $strSelectedVal, $field1);
	}
}