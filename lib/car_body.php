<?php
/**
 * MSergeev\Packages\Icar\Lib\CarBody
 * Типы кузова автомобиля
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\CarBodyTable;
use MSergeev\Core\Lib\Loc;

/**
 * Class CarBody
 *
 * @static
 */
class CarBody
{
	/**
	 * Возвращает тег <select> со списком типов кузовов автомобилей
	 *
	 * @api
	 *
	 * @param int    $selected  Значение option по-умолчанию
	 * @param string $name      Название тега <select>
	 *
	 * @uses CarBodyTable::getList
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 * @uses SelectBox Функция вывода тега <select>
	 *
	 * @return string
	 */
	public static function getHtmlSelect($selected=0, $name='car_body')
	{
		$arValues = CarBodyTable::getList(array(
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
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'),$selected,'class="form-control"');
		else
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'),'null','class="form-control"');
	}
}