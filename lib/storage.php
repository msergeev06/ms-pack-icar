<?php
/**
 * MSergeev\Packages\Icar\Lib\Storage
 * Места хранения
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\StorageTable;

/**
 * Class Storage
 */
class Storage
{
	/**
	 * Возвращает тег <select> со списком Мест хранения
	 *
	 * @api
	 *
	 * @param string $strBoxName        Свойство name тега <select>
	 * @param string $strDetText        Текст для пустого значения
	 * @param string $strSelectedVal    Значение по-умолчанию
	 * @param string $field1            Дополнительные свойства тега <select>
	 *
	 * @uses StorageTable::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
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