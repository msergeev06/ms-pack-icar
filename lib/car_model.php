<?php
/**
 * MSergeev\Packages\Icar\Lib\CarModel
 * Модели автомобилей
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception\ArgumentNullException;
use MSergeev\Packages\Icar\Tables\CarModelTable;
use MSergeev\Core\Lib\Loc;

/**
 * Class CarModel
 * @package MSergeev\Packages\Icar\Lib
 */
class CarModel
{
	/**
	 * Возвращает тег <select> со списком моделей автомобилей
	 *
	 * @api
	 *
	 * @param null|int $brand       null или ID бренда, если список должен содержать
	 *                              только модели одного бренда
	 * @param int       $selected   Значение option по-умолчанию
	 * @param string    $name       Название тега <select>
	 *
	 * @use SelectBox() Функция вывода тега <select>
	 *
	 * @return string
	 */
	public static function getHtmlSelect($brand=null, $selected=0, $name='car_model')
	{
		$arGetList = array(
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
		);
		if (!is_null($brand))
		{
			$arGetList['filter']['BRANDS_ID'] = intval($brand);
		}
		$arValues = CarModelTable::getList($arGetList);

		if ($selected>0)
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'),$selected);
		else
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'));
	}

	/**
	 * Функция добавляет новую модель автомобиля
	 *
	 * @api
	 *
	 * @param int       $brandID    ID бренда
	 * @param string    $modelName  Название модели
	 *
	 * @return int ID добавленной модели
	 */
	public static function addNewModel ($brandID=null, $modelName=null)
	{
		try
		{
			if (is_null($modelName))
			{
				throw new ArgumentNullException('model_name');
			}
			else
			{
				$modelName = htmlspecialchars($modelName);
			}

			if (is_null($brandID))
			{
				throw new ArgumentNullException('brandID');
			}

			$arInsert[0] = array(
				'BRANDS_ID' => $brandID,
				'NAME' => $modelName
			);
			$query = new Query('insert');
			$query->setInsertParams(
				$arInsert,
				CarModelTable::getTableName(),
				CarModelTable::getMapArray()
			);
			$res = $query->exec();

			if ($res->getResult())
			{
				return $res->getInsertId();
			}

		}
		catch (ArgumentNullException $e)
		{
			$e->showException();
		}
	}

}