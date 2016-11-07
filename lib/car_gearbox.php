<?php
/**
 * MSergeev\Packages\Icar\Lib\CarGearbox
 * Типы КПП
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\CarGearboxTable;
use MSergeev\Core\Lib\Loc;

/**
 * Class CarGearbox
 * @package MSergeev\Packages\Icar\Lib
 */
class CarGearbox
{
	/**
	 * Выводит тег <select> со списком КПП
	 *
	 * @api
	 *
	 * @param int    $selected  Значение option по-умолчанию
	 * @param string $name      Название тега <select>
	 *
	 * @use SelectBox() Функция вывода тега <select>
	 *
	 * @return string
	 */
	public static function getHtmlSelect($selected=0,$name='car_gearbox')
	{
		$arValues = CarGearboxTable::getList(array(
			'select' => array(
				'ID' => 'VALUE',
				'NAME'
			),
			'filter' => array(
				'ACTIVE' => true
			),
			'order' => array(
				'SORT' => 'ASC',
				'NAME' => 'ASC'
			)
		));

		if ($selected>0)
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'),$selected);
		else
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'));
	}
}