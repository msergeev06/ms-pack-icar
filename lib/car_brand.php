<?php
/**
 * MSergeev\Packages\Icar\Lib\CarBrand
 * Марки автомобилей
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Lib\Events;
use MSergeev\Packages\Icar\Tables\CarBrandTable;
use MSergeev\Core\Lib\Loc;
use MSergeev\Core\Exception;

/**
 * Class CarBrand
 *
 * Events:
 * OnBeforeAddNewBrand - Перед добавлением нового бренда. Передается имя нового бренда
 * OnAfterAddNewBrand - После добавления нового бренда. Передается имя нового бренда и ID записи в DB
 *
 * @static
 */
class CarBrand
{
	/**
	 * @var array Массив получаемых полей из базы
	 *
	 * @private
	 * @static
	 */
	private static $arCarBrandFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'NAME',
		'CODE'
	);

	/**
	 * Возвращает тег <select> со списком марок автомобилей
	 *
	 * @api
	 *
	 * @param int    $selected  Значение option по-умолчанию
	 * @param string $name      Название тега <select>
	 *
	 * @uses CarBrandTable::getList
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 * @uses SelectBox Функция вывода тега <select>
	 *
	 * @return string
	 */
	public static function getHtmlSelect($selected=0, $name='car_brand')
	{
		$arValues = CarBrandTable::getList(array(
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

	/**
	 * Возвращает массив с информацией по бренду, по его ID
	 *
	 * @api
	 *
	 * @param null|int  $brandID    ID бренда
	 *
	 * @uses CarBrandTable::getList
	 *
	 * @return array|bool
	 */
	public static function getInfoByID ($brandID=null)
	{
		if (!is_null($brandID) && intval($brandID)>0)
		{
			$brandID = intval($brandID);
		}
		else
		{
			return false;
		}

		$arRes = CarBrandTable::getList(array(
			'select' => self::$arCarBrandFields,
			'filter' => array('ID'=>$brandID),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Добавляет новый бренд
	 *
	 * @api
	 *
	 * @param string $brandName Имя нового бренда
	 *
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throw MSergeev\Core\Exception\ArgumentNullException Когда имя бренда не указано
	 * @throw MSergeev\Core\Exception\ArgumentOutOfRangeException Если длинна названия бренда = 0
	 *
	 * @return bool|int
	 */
	public static function addNewBrand ($brandName=null)
	{
		try
		{
			if (is_null($brandName))
			{
				throw new Exception\ArgumentNullException('$brandName');
			}
			if (strlen($brandName)<=0)
			{
				throw new Exception\ArgumentOutOfRangeException('$brandName',1,255);
			}
			elseif (strlen($brandName)>255)
			{
				$brandName = substr($brandName,0,254);
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}
		catch (Exception\ArgumentOutOfRangeException $e2)
		{
			$e2->showException();
			return false;
		}

		if ($arEvents = Events::getPackageEvents('icar','OnBeforeAddNewBrand'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					Events::executePackageEvent($arEvent,array(&$brandName));
				}
			}
		}

		$query = new Query('insert');
		$query->setInsertParams(
			array('NAME'=>$brandName),
			CarBrandTable::getTableName(),
			CarBrandTable::getMapArray()
		);
		$res = $query->exec();

		if ($res->getResult())
		{

			if ($arEvents = Events::getPackageEvents('icar','OnAfterAddNewBrand'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						Events::executePackageEvent($arEvent,array($brandName,$res->getInsertId()));
					}
				}
			}

			return $res->getInsertId();
		}
		else
		{
			return false;
		}
	}
}