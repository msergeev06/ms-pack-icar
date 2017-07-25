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
use MSergeev\Core\Lib\Events;
use MSergeev\Core\Lib\Tools;
use MSergeev\Packages\Icar\Tables\CarModelTable;
use MSergeev\Core\Lib\Loc;

/**
 * Class CarModel
 *
 * Events:
 * OnBeforeAddNewModel - Перед добавлением новой модели (ID бренда, имя модели, префикс)
 * OnAfterAddNewModel - После добавления новой модели (ID бренда, имя модели, префикс, ID новой модели)
 *
 * @static
 */
class CarModel
{
	/**
	 * @var array Массив получаемых полей из базы
	 *
	 * @private
	 * @static
	 */
	private static $arCarModelFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'BRANDS_ID',
		'BRANDS_ID.ACTIVE' => 'BRANDS_ACTIVE',
		'BRANDS_ID.SORT' => 'BRANDS_SORT',
		'BRANDS_ID.NAME' => 'BRANDS_NAME',
		'BRANDS_ID.CODE' => 'BRANDS_CODE',
		'NAME',
		'CODE'
	);

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
	 * @uses CarModelTable::getList
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 * @uses SelectBox Функция вывода тега <select>
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
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'),$selected,'class="form-control"');
		else
			return SelectBox($name,$arValues,Loc::getPackMessage('icar','all_select_default'),'null','class="form-control"');
	}

	/**
	 * Функция добавляет новую модель автомобиля
	 *
	 * @api
	 *
	 * @param int       $brandID    ID бренда
	 * @param string    $modelName  Название модели
	 * @param string    $prefix     Префикс кода модели, обычно код бренда
	 *
	 * @uses CarBrand::getInfoByID
	 * @uses CarModelTable::getTableName
	 * @uses CarModelTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Lib\Tools::generateCode
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throw MSergeev\Core\Exception\ArgumentNullException Если называние модели не задано
	 * @throw MSergeev\Core\Exception\ArgumentNullException Если ID бренда не задано
	 *
	 * @return int ID добавленной модели
	 */
	public static function addNewModel ($brandID=null, $modelName=null, $prefix=null)
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

			if (is_null($prefix))
			{
				if ($arBrand = CarBrand::getInfoByID($brandID))
				{
					$prefix = $arBrand['CODE'];
				}
			}

			if ($arEvents = Events::getPackageEvents('icar','OnBeforeAddNewModel'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						Events::executePackageEvent($arEvent,array(&$brandID, &$modelName, &$prefix));
					}
				}
			}

			$arInsert[0] = array(
				'BRANDS_ID' => $brandID,
				'NAME' => $modelName
			);
			if (!is_null($prefix))
			{
				$arInsert[0]['CODE'] = $prefix."_".Tools::generateCode($modelName);
			}
			$query = new Query('insert');
			$query->setInsertParams(
				$arInsert,
				CarModelTable::getTableName(),
				CarModelTable::getMapArray()
			);
			$res = $query->exec();

			if ($res->getResult())
			{
				if ($arEvents = Events::getPackageEvents('icar','OnAfterAddNewModel'))
				{
					foreach ($arEvents as $sort=>$ar_events)
					{
						foreach ($ar_events as $arEvent)
						{
							Events::executePackageEvent($arEvent,array($brandID, $modelName, $prefix, $res->getInsertId()));
						}
					}
				}

				return $res->getInsertId();
			}

		}
		catch (ArgumentNullException $e)
		{
			$e->showException();
		}
	}

	/**
	 * Возвращает массив с данными модели, по ее ID
	 *
	 * @api
	 *
	 * @param int $modelID ID модели
	 *
	 * @uses CarModelTable::getList
	 *
	 * @return bool|array
	 */
	public static function getInfoByID ($modelID=null)
	{
		if (!is_null($modelID))
		{
			$modelID = intval($modelID);
		}
		else
		{
			return false;
		}

		$arRes = CarModelTable::getList(array(
			'select' => self::$arCarModelFields,
			'filter' => array('ID'=>$modelID),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

}