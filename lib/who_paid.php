<?php
/**
 * MSergeev\Packages\Icar\Lib\WhoPaid
 * Кто платил
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\WhoPaidTable;

/**
 * Class WhoPaid
 */
class WhoPaid
{
	/**
	 * Возвращает тег <select> со списком тех Кто платил
	 *
	 * @api
	 *
	 * @param string $strBoxName    Свойство name тега <select>
	 * @param int    $selected      Значение по-умолчанию
	 * @param string $field1        Дополнительные свойства тега <select>
	 *
	 * @uses WhoPaidTable::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
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