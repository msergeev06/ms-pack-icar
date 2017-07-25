<?php
/**
 * MSergeev\Packages\Icar\Lib\Fuel
 * Расходы на топливо
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Lib\Loc;
use MSergeev\Packages\Yandexmap\Lib\YandexMap;

/**
 * Class Fuel
 *
 * Events:
 * OnBeforeAddFuel - Перед добавлением записи о заправке. Передается массив полей записи
 * OnAfterAddFuel - После добавления записи о заправке. Передается массив полей и ID записи в DB
 * OnBeforeUpdateFuel - Перед изменением записи о заправке. Передается массив изменяемых полей
 * OnAfterUpdateFuel - После изменения записи о заправке. Передается массив измененных полей
 * OnBeforeDeleteFuel - Перед удалением записи о заправке. Передается ID записи
 * OnAfterDeleteFuel - После попытки удаления записи. Передается флаг успешности удаления
 *
 * @static
 */
class Fuel
{
	/**
	 * @var array Массив полей записи заправки
	 *
	 * @private
	 * @static
	 */
	private static $arFuelFields = array(
		'ID',
		'MY_CAR_ID',
		'MY_CAR_ID.NAME' => 'MY_CAR_NAME',
		'MY_CAR_ID.CAR_NUMBER' => 'MY_CAR_NUMBER',
		'DATE',
		'ODO',
		'FUELMARK_ID',
		'FUELMARK_ID.NAME' => 'FUELMARK_NAME',
		'LITER',
		'LITER_COST',
		'SUM',
		'FULL',
		'MISSING',
		'POINTS_ID',
		'POINTS_ID.NAME' => 'POINT_NAME',
		'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
		'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
		'POINTS_ID.RADIUS' => 'POINT_RADIUS',
		'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
		'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
		'DESCRIPTION' => 'INFO',
		'CHECK',
		'CHECK.WIDTH' => 'CHECK_WIDTH',
		'CHECK.HEIGHT' => 'CHECK_HEIGHT',
		'CHECK.SUBDIR' => 'CHECK_SUBDIR',
		'CHECK.FILE_NAME' => 'CHECK_FILE_NAME',
		'CHECK.DESCRIPTION' => 'CHECK_DESCRIPTION',
		'DISTANCE',
		'EXPENCE',
		'TANK_LITER',
		'TANK_COST',
		'COST_KM'
	);
	//protected static $bRecalculateExpence = false;

	/**
	 * Возвращает сумму расходов на топливо за все время
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCosts ($carID=null)
	{
		$fuelCosts = 0;
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getSumFunction('SUM','SUMM')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
		}

		return floatval($fuelCosts);
	}

	/**
	 * Возвращает сумму расходов на топливо за Год
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий год, false - предыдущий год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCostsYear ($carID=null, $now=true)
	{
		$fuelCosts = 0;
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$year = intval(date('Y'));
		}
		else
		{
			$year = intval(date('Y')) - 1;
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('SUM','SUMM')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." >= '".$year."-01-01' AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." <= '".$year."-12-31'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
		}

		return floatval($fuelCosts);
	}

	/**
	 * Возвращает сумму расходов топлива за Месяц
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий месяц, false - предыдущий месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCostsMonth ($carID=null, $now=true)
	{
		$fuelCosts = 0;
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$monthYear = date('Y-m');
			$days = date('t');
		}
		else
		{
			$time = strtotime('-1 month');
			$monthYear = date('Y-m',$time);
			$days = date('t',$time);
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('SUM','SUMM')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." >= '".$monthYear."-01' AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." <= '".$monthYear."-".$days."'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
		}

		return floatval($fuelCosts);
	}

	/**
	 * Возвращает количество заправок за все время
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return int
	 */
	public static function getNumberOfRefills ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getCountFunction('ID','COUNT')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return intval($ar_res['COUNT']);
		}

		return intval(0);
	}

	/**
	 * Возвращает количество заправок за Год
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий год, false - предыдущий год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return int
	 */
	public static function getNumberOfRefillsYear ($carID=null, $now=true)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$year = intval(date('Y'));
		}
		else
		{
			$year = intval(date('Y')) - 1;
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getCountFunction('ID','COUNT')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." >= '".$year."-01-01' AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." <= '".$year."-12-31'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return intval($ar_res['COUNT']);
		}

		return intval(0);
	}

	/**
	 * Возвращает количество заправок за Месяц
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий месяц, false - предыдущий месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return int
	 */
	public static function getNumberOfRefillsMonth ($carID=null, $now=true)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$monthYear = date('Y-m');
			$days = date('t');
		}
		else
		{
			$time = strtotime('-1 month');
			$monthYear = date('Y-m',$time);
			$days = date('t',$time);
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getCountFunction('ID','COUNT')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." >= '".$monthYear."-01' AND\n\t"
			.$helper->wrapFieldQuotes('DATE')." <= '".$monthYear."-".$days."'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return intval($ar_res['COUNT']);
		}

		return intval(0);
	}

	/**
	 * Возвращает максимальное количество заправленных литров
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMaxRefills ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('LITER','MAX')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MAX']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает минимальное количество заправленных литров
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMinRefills ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMinFunction('LITER','MIN')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MIN']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает среднее значение заправленных литров
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getAverageFuelRefills ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getAvgFunction('LITER','AVG')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['AVG']))
			{
				return floatval($ar_res['AVG']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает среднюю стоимость заправки
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getAverageFuelCosts ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getAvgFunction('SUM','AVG')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['AVG']))
			{
				return floatval($ar_res['AVG']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает среднюю стоимость километра (по топливу)
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getAverageFuelCostKm ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getAvgFunction('COST_KM','AVG')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['AVG']))
			{
				return floatval($ar_res['AVG']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает средний расход топлива
	 *
	 * @api
	 *
	 * @param int|null $carID   ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getAverageFuelConsumption($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getAvgFunction('EXPENCE','EXPENCE')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['EXPENCE']))
			{
				return floatval($ar_res['EXPENCE']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает минимальное значение среднего расхода топлива
	 *
	 * @api
	 *
	 * @param int|null $carID   ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMinFuelConsumption($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMinFunction('EXPENCE','MIN')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['MIN']))
			{
				return floatval($ar_res['MIN']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает максимальное значение среднего расхода топлива
	 *
	 * @api
	 *
	 * @param int|null $carID   ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMaxFuelConsumption($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('EXPENCE','MAX')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['MAX']))
			{
				return floatval($ar_res['MAX']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает количество израсходованного топлива за все время
	 *
	 * @api
	 *
	 * @param int|null $carID   ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getCarTotalSpentFuel ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$total = 0;

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getSumFunction('LITER','SUMM')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$total = $ar_res['SUMM'];
		}

		return floatval($total);
	}

	/**
	 * Возвращает количество израсходованного топлива за Год
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий год, false - прошлый год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getCarTotalSpentFuelYear ($carID=null, $now=true)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$year = intval(date('Y'));
		}
		else
		{
			$year = intval(date('Y')) - 1;
		}

		$total = 0;

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getSumFunction('LITER','SUMM')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." >= '".$year."-01-01' AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." <= '".$year."-12-31'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$total = $ar_res['SUMM'];
		}

		return floatval($total);
	}

	/**
	 * Возвращает количество израсходованного топлива за Месяц
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    true - текущий месяц, false - прошлый месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getCarTotalSpentFuelMonth ($carID=null, $now=true)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$monthYear = date('Y-m');
			$days = date('t');
		}
		else
		{
			$time = strtotime('-1 month');
			$monthYear = date('Y-m',$time);
			$days = date('t',$time);
		}

		$total = 0;

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getSumFunction('LITER','SUMM')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." >= '".$monthYear."-01' AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." <= '".$monthYear."-".$days."'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$total = $ar_res['SUMM'];
		}

		return floatval($total);
	}

	/**
	 * Возвращает минимальную цену на 1 литр топлива
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMinFuelCost ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getMinFunction('LITER_COST','MIN')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MIN']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает максимальную цену на 1 литр топлива
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMaxFuelCost ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getMaxFunction('LITER_COST','MAX')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MAX']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает максимальную стоимость приобретенного топлива
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMaxCheck ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getMaxFunction('SUM','MAX')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MAX']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает минимальную стоимость приобретенного топлива
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMinCheck ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getMinFunction('SUM','MIN')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MIN']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает минимальную стоимость за километр по топливу
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMinCostByKm ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getMinFunction('COST_KM','MIN')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MIN']);
		}

		return floatval(0);
	}

	/**
	 * Возвращает максимальную стоимость за километр по топливу
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMaxCostByKm ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$sqlHelper->getMaxFunction('COST_KM','MAX')."\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['MAX']);
		}

		return floatval(0);
	}

	/**
	 * Подготавливает данные о расходах на топливо из формы для добавления в БД
	 *
	 * @api
	 *
	 * @param array $post Массив POST
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Odo::getCurrentOdo
	 * @uses Errors::issetErrors
	 * @uses Fuel::addDB
	 *
	 * @throws Exception\ArgumentNullException Если массив POST пуст
	 *
	 * @return int|bool ID добавленной записи, либо false
	 */
	public static function addFuelFromPost ($post=null)
	{
		try
		{
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('post');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}

		$arData = array();
		Fields::validateFields($post, $arData);
		if (!isset($arData['MY_CAR_ID']))
		{
			$arData['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}

		if (!isset($arData['DATE']))
		{
			Errors::addError('DATE','Неверный формат даты');
		}

		if (!isset($arData['ODO']) || $arData['ODO']==0)
		{
			$arData['ODO'] = Odo::getCurrentOdo($arData['MY_CAR_ID']);
		}

		if (!isset($arData['FUELMARK_ID']))
		{
			Errors::addError('FUELMARK_ID','Не указана марка топлива');
		}

		if (!isset($arData['LITER']) || $arData['LITER']<=0)
		{
			Errors::addError('LITER','Не указано количество литров');
		}

		if (!isset($arData['LITER_COST']) || $arData['LITER_COST']<=0)
		{
			Errors::addError('LITER_COST', 'Не указана цена за литр');
		}

		if (isset($arData['LITER']) && isset($arData['LITER_COST']))
		{
			$arData['SUM'] = floatval($arData['LITER_COST'] * $arData['LITER']);
		}

		if (!isset($arData['FULL']) || !$arData['FULL'])
		{
			$arData['FULL'] = false;
		}
		else
		{
			$arData['FULL'] = true;
		}

		if (!isset($arData['MISSING']) || !$arData['MISSING'])
		{
			$arData['MISSING'] = false;
		}
		else
		{
			$arData['MISSING'] = true;
		}

		/*
		$arData['EXPENCE'] = static::calculateExpence(
			$arData['MY_CAR_ID'],
			$arData['DATE'],
			$arData['ODO'],
			$arData['LITER'],
			$arData['FULL']
		);
		*/

		if (!isset($arData['POINTS_ID']))
		{
			Errors::addError('POINTS_ID','Не указана путевая точка');
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		return self::addDB($arData);
	}

	/**
	 * Подготавливает данные о расходах на топливо из формы для обновления в DB
	 *
	 * @api
	 *
	 * @param array $post Массив POST
	 *
	 * @uses Fuel::getList
	 * @uses Fuel::recalculateExpence
	 * @uses Fields::validateFields
	 * @uses FuelTable::getTableName
	 * @uses FuelTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Lib\File::deleteFile
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если массив POST пуст или отсутствует id записи
	 * @throws Exception\ArgumentOutOfRangeException Если id записи <= 0
	 *
	 * @return bool
	 */
	public static function updateFuelFromPost ($post=null)
	{
		try
		{
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('post');
			}
			if (!isset($post['id']))
			{
				throw new Exception\ArgumentNullException('post[id]');
			}
			elseif (intval($post['id'])<=0)
			{
				throw new Exception\ArgumentOutOfRangeException('post[id]',1);
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}
		catch (Exception\ArgumentOutOfRangeException $e2)
		{
			die ($e2->showException());
		}

		$arFuel = static::getList(null,intval($post['id']));
		$arFuel = $arFuel[0];

		$arUpdate = array();
		Fields::validateFields($post, $arUpdate);
		//msDebug($post);
		//msDebug($arUpdate);
		if (isset($arUpdate['LITER']) && $arUpdate['LITER_COST'])
		{
			$sum = floatval($arUpdate['LITER'] * $arUpdate['LITER_COST']);
			if (round($sum,2) != $arFuel['SUM'])
			{
				$arUpdate['SUM'] = round($sum,2);
			}
		}

		if (!isset($arUpdate['FULL']) || !$arUpdate['FULL'])
		{
			$arUpdate['FULL'] = false;
		}
		else
		{
			$arUpdate['FULL'] = true;
		}

		if (!isset($arUpdate['MISSING']) || !$arUpdate['MISSING'])
		{
			$arUpdate['MISSING'] = false;
		}
		else
		{
			$arUpdate['MISSING'] = true;
		}

		$checkImg = null;
		if (!is_null($arUpdate['CHECK']) && isset($arUpdate['~CHECK_DELETE']) && $arUpdate['~CHECK_DELETE']===true)
		{
			$checkImg = $arUpdate['CHECK'];
			$arUpdate['CHECK'] = NULL;
		}
		if (isset($arUpdate['~CHECK_DELETE']))
		{
			unset($arUpdate['~CHECK_DELETE']);
		}

		$newCheck = null;
		if (isset($arUpdate['~NEW_CHECK']) && intval($arUpdate['~NEW_CHECK'])>0)
		{
			$checkImg = $arUpdate['CHECK'];
			$newCheck = $arUpdate['CHECK'] = $arUpdate['~NEW_CHECK'];
		}
		if (isset($arUpdate['~NEW_CHECK']))
		{
			unset($arUpdate['~NEW_CHECK']);
		}

		//msDebug($arUpdate);
		//msDebug($arFuel);
		foreach ($arUpdate as $key=>$value)
		{
			if (
				(is_null($arFuel[$key]) && !is_null($arUpdate[$key]))
				|| (!is_null($arFuel[$key]) && is_null($arUpdate[$key]))
				|| (!is_null($arFuel[$key]) && !is_null($arUpdate[$key]) && ($arFuel[$key]!==$arUpdate[$key]))
			)
			{
				continue;
			}
			else
			{
				unset($arUpdate[$key]);
			}
		}
		//msDebugDie($arUpdate);

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateFuel'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$arUpdate));
				}
			}
		}

		if (!empty($arUpdate))
		{
			$query = new Query('update');
			$query->setUpdateParams(
				$arUpdate,
				$post['id'],
				Tables\FuelTable::getTableName(),
				Tables\FuelTable::getMapArray()
			);
			$res = $query->exec();
			if ($res->getResult())
			{
				if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateFuel'))
				{
					foreach ($arEvents as $sort=>$ar_events)
					{
						foreach ($ar_events as $arEvent)
						{
							CoreLib\Events::executePackageEvent($arEvent,array($arUpdate));
						}
					}
				}

				if (isset($arUpdate['MY_CAR_ID'])
					|| isset($arUpdate['DATE'])
					|| isset($arUpdate['ODO'])
					|| isset($arUpdate['FUELMARK_ID'])
					|| isset($arUpdate['LITER'])
					|| isset($arUpdate['LITER_COST'])
					|| isset($arUpdate['SUM'])
					|| isset($arUpdate['FULL'])
					|| isset($arUpdate['MISSING'])
				)
				{
					static::recalculateExpence($arFuel);
				}
				if (!is_null($checkImg))
				{
					CoreLib\File::deleteFile($checkImg);
				}
				return true;
			}
			else
			{
				if (!is_null($newCheck))
				{
					CoreLib\File::deleteFile($newCheck);
				}
				return false;
			}
		}
		else
		{
			return true;
		}
	}

	/**
	 * Удаляет запись расходов на топливо
	 *
	 * @api
	 *
	 * @param int $fuelID ID записи расходов
	 *
	 * @uses FuelTable::getTableName
	 * @uses FuelTable::getMapArray
	 * @uses FuelTable::getTableLinks
	 * @uses Fuel::recalculateExpence
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не указан ID удаляемой записи
	 * @throws Exception\ArgumentOutOfRangeException Если ID удаляемой записи <= 0
	 *
	 * @return bool
	 */
	public static function deleteFuel ($fuelID=null)
	{
		try
		{
			if (is_null($fuelID))
			{
				throw new Exception\ArgumentNullException('fuelID');
			}
			if (intval($fuelID)<=0)
			{
				throw new Exception\ArgumentOutOfRangeException('fuelID',1);
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die ($e->showException());
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeDeleteFuel'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$fuelID));
				}
			}
		}

		$query = new Query('delete');
		$query->setDeleteParams(
			$fuelID,
			true,
			Tables\FuelTable::getTableName(),
			Tables\FuelTable::getMapArray(),
			Tables\FuelTable::getTableLinks()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteFuel'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array(true));
					}
				}
			}

			self::recalculateExpence();
			return true;
		}
		else
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteFuel'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array(false));
					}
				}
			}

			return false;
		}

	}

	/**
	 * Возвращает <select> с марками топлива
	 *
	 * @api
	 *
	 * @param string $strBoxName        Навзание тега <select>
	 * @param string $strSelectedVal    Значение option по-умолчанию
	 * @param string $field1            Прочие параметры тега <select>
	 *
	 * @uses Fuel::getFuelMarksList
	 * @uses MSergeev\Core\Lib\Loc::getPAckMessage
	 * @uses SelectBox Функция отображения тега <select>
	 *
	 * @return string
	 */
	public static function showSelectFuelMarks($strBoxName="fuel_mark", $strSelectedVal='null', $field1='class="fuel_mark"')
	{
		if ($arFuelMarks = static::getFuelMarksList())
		{
			$arValues = array ();
			foreach ($arFuelMarks as $arFuelMark)
			{
				$arValues[] = array (
					'NAME'  => $arFuelMark['NAME'],
					'VALUE' => $arFuelMark['ID']
				);
			}

			return SelectBox ($strBoxName, $arValues, Loc::getPackMessage('icar','all_select_default'), $strSelectedVal, $field1);
		}
		else
		{
			return '['.Loc::getPackMessage('icar','fuel_no_mark').']';
		}
	}

	/**
	 * Возвращает массив со списком расходов на топливо
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 * @param int|null  $getID  ID записи расхода на топливо. Если указано, вернется информация по одной записи
	 * @param int       $limit  Коливество записей в выводе
	 * @param int       $offset С какой записи по порядки начинать вывод
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getList
	 *
	 * @return array|bool
	 */
	public static function getList ($carID=null,$getID=null,$limit=0,$offset=0)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		if (!is_null($getID) && intval($getID)>0)
		{
			$arFilter["ID"] = intval($getID);
			$limit = 1;
		}
		else
		{
			$arFilter['MY_CAR_ID'] = $carID;

		}

		$arList = array(
			'select' => self::$arFuelFields,
			'filter' => $arFilter,
			'order' => array(
				'DATE' => 'DESC',
				'ID' => 'DESC'
			)
		);
		if ($limit > 0)
		{
			$arList['limit'] = $limit;
		}
		if ($offset > 0)
		{
			$arList['offset'] = $offset;
		}

		$arRes = Tables\FuelTable::getList($arList);

		return $arRes;
	}

	/**
	 * Возвращает таблицу с данными о расходах на топливо
	 *
	 * @api
	 *
	 * @param int|null      $carID  ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses IcarWebixHelper
	 * @uses MSergeev\Core\Lib\Tools::getSitePath
	 * @uses MSergeev\Core\Lib\Loader::getTemplate
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 * @uses MSergeev\Core\Lib\Webix::showDataTable
	 * @uses MSergeev\Core\Lib\DateHelper
	 *
	 * @return bool|void
	 */
	public static function showListTable ($carID = null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		$bYandex = false;
		if (CoreLib\Loader::issetPackage('yandexmap'))
		{
			$bYandex = CoreLib\Loader::IncludePackage('yandexmap');
		}
//		CoreLib\Plugins::includeMagnificPopup();

		$arList = static::getList($carID);
		//msDebug($arList);
		if ($arList)
		{
			echo '<div id="fuelList"></div><div id="fuelPager"></div>';

			$dateHelper = new CoreLib\DateHelper();
			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('icar')."images/");

			//msDebug($arList);
			$arDatas = array();
			foreach ($arList as $list)
			{
//				$spn=$list['POINT_RADIUS']/20000;
				$info = '';
				if (!is_null($list['CHECK']))
				{
					$info .= addslashes(CoreLib\File::showImage(CoreLib\Loader::getSiteTemplate('icar').'images/check.png',16,16,'class="check_img"','/msergeev/upload/'.$list['CHECK_SUBDIR'].'/'.$list['CHECK_FILE_NAME'],true)."&nbsp;");
				}
				if(strlen($list['INFO'])>0)
				{
					$info .= "<img src='".$imgSrcPath."info.png'>";
				}

				$arDatas[] = array(
					'id' => $list['ID'],
					'date' => $list['DATE'],
					'timestamp' => "=".$dateHelper->getDateTimestamp($list['DATE']),
					'odo' => "=".$list['ODO'],
					'fuelmark_name' => $list['FUELMARK_NAME'],
					'liter' => "=".$list['LITER'],
					'liter_cost' => "=".$list['LITER_COST'],
					'sum' => "=".$list['SUM'],
					'full' => ($list['FULL'])?"Да":"-",
					'expence' => "=".floatval($list['EXPENCE']),
					'point_name' => $list['POINT_NAME'],
/*						.(($bYandex)?"&nbsp;".addslashes('<a href="'.YandexMap::getStaticUrl(
								$list['POINT_LATITUDE'],
								$list['POINT_LONGITUDE'],
								$list['POINT_RADIUS'],
								600,
								450
							).'" class="popup-link-'.$list['ID'].'"><img src="'.CoreLib\Loader::getSiteTemplate('icar').'images/yandex.png" width="20" height="20"></a>'):''),*/
					'point_latitude' => $list['POINT_LATITUDE'],
					'point_longitude' => $list['POINT_LONGITUDE'],
					'yandex_map' => (!is_null($list['POINT_LONGITUDE']) && !is_null($list['POINT_LATITUDE']))
						?addslashes((($bYandex)
								?YandexMap::showImgPoint($list['POINT_LATITUDE'],$list['POINT_LONGITUDE'],$list['POINT_RADIUS'],600,450)
								:'')
						)
						:'',
					'point_type' => $list['POINT_TYPE_NAME'],
					'info' => $info,
					'comment' => $list['INFO'],
					'edit' => "<a class='table_button' href='edit.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='delete.php?id=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
				);
/*				if ($bYandex)
				{
					CoreLib\Buffer::addJsToDownPage('$(".popup-link-'.$list['ID'].'").magnificPopup({type: "image"});');
				}*/
			}

			$webixHelper = new IcarWebixHelper();

			$webixHelper->addFunctionSortByTimestamp();

			$arData = array(
				'grid' => 'fuelGrid',
				'container' => 'fuelList',
				'footer' => true,
				'tooltip' => true,
				'pager' => array('container'=>'fuelPager'),
				'columns' => array(
					$webixHelper->getColumnArray('DATE',array(
						'footer'=>'={text:"'.Loc::getPackMessage('icar','all_summ').':", colspan:3}'
					)),
					$webixHelper->getColumnArray('ODO'),
					$webixHelper->getColumnArray('FUELMARK_NAME'),
					$webixHelper->getColumnArray('LITER',array(
						'footer'=>'={ content:"summColumn" }'
					)),
					$webixHelper->getColumnArray('LITER_COST'),
					$webixHelper->getColumnArray('LITER_COST_SUM',array(
						'footer'=>'={ content:"summColumn" }'
					)),
					$webixHelper->getColumnArray('FULL'),
					$webixHelper->getColumnArray('EXPENCE'),
					$webixHelper->getColumnArray('POINT'),
					$webixHelper->getColumnArray('INFO'),
					$webixHelper->getColumnArray('EDIT'),
					$webixHelper->getColumnArray('DELETE')
				),
				'data' => $arDatas
			);

			CoreLib\Webix::showDataTable($arData);
			return true;
		}
		else
		{
			echo Loc::getPackMessage('icar','fuel_no_data');
			return false;
		}
	}

	/**
	 * Функция возвращает максимальное значение одометра для записей о заправках
	 *
	 * @param int|null $carID ID автомобиля. Если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getMaxOdo ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\FuelTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('ODO','MAX_ODO')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['MAX_ODO']))
			{
				return floatval($ar_res['MAX_ODO']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает массив всех марок топлива, по умолчанию выбирает только активные
	 *
	 * @param bool $bActive Флаг, выбирать активные (по-умолчанию) или все
	 *
	 * @uses FuelmarkTable::getList
	 *
	 * @return array|bool
	 */
	protected static function getFuelMarksList($bActive=true)
	{
		$arList = array();
		if ($bActive)
		{
			$arList['filter'] = array('ACTIVE'=>true);
		}
		$arList['order'] = array('SORT'=>'ASC');
		if ($arResult = Tables\FuelmarkTable::getList($arList))
		{
			return $arResult;
		}
		else
		{
			return false;
		}

	}

	/**
	 * Добавляет данные о заправках в БД
	 *
	 * @param array $arData Массив с обработанными данными о заправках
	 *
	 * @uses Points::increasePointPopular
	 * @uses Fuel::recalculateExpence
	 * @uses FuelTable::getTableName
	 * @uses FuelTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Lib\Options::setOption
	 * @uses MSergeev\Core\Lib\File::deleteFile
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если массив данных не задан
	 * @throws Exception\ArgumentTypeException Если arData не массив
	 *
	 * @return bool|int
	 */
	protected static function addDB (array $arData=null)
	{
		try
		{
			if (is_null($arData))
			{
				throw new Exception\ArgumentNullException('arData');
			}
			elseif (!is_array($arData))
			{
				throw new Exception\ArgumentTypeException('arData','array');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}
		catch (Exception\ArgumentTypeException $e2)
		{
			die($e2->showException());
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddFuel'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$arData));
				}
			}
		}

		$query = new Query('insert');
		$query->setInsertParams(
			$arData,
			Tables\FuelTable::getTableName(),
			Tables\FuelTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddFuel'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($arData,$res->getInsertId()));
					}
				}
			}

			Points::increasePointPopular($arData['POINTS_ID']);
			static::recalculateExpence($arData);
			CoreLib\Options::setOption('icar_last_fuelmark_'.$arData['MY_CAR_ID'],$arData['FUELMARK_ID']);
			return $res->getInsertId();
		}
		else
		{
			if (isset($arData['CHECK']) && !is_null($arData['CHECK']))
			{
				CoreLib\File::deleteFile($arData['CHECK']);
			}
			return false;
		}
	}

	/**
	 * Функция пресчитывает расход топлива для всех записей, начиная с заданной
	 *
	 * @param array $arData Массив обработанных данных о заправках
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses FuelTable::getList
	 * @uses Fuel::calculateDistance
	 * @uses Fuel::calculateExpence
	 * @uses Fuel::calculateTank
	 * @uses Fuel::calculationCostKm
	 * @uses Fuel::updateExpence
	 * @uses Fuel::recalculateExpence Если не получилось с пробегом (одо), пробуем без
	 *
	 * @throws Exception\ArgumentNullException Если не задан массив с данными
	 * @throws Exception\ArgumentTypeException Если передан не массив
	 *
	 * @return bool
	 */
	protected static function recalculateExpence (array $arData=null)
	{
		try
		{
			if (is_null($arData))
			{
				throw new Exception\ArgumentNullException('arData');
			}
			elseif (!is_array($arData))
			{
				throw new Exception\ArgumentTypeException('arData','array');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}
		catch (Exception\ArgumentTypeException $e2)
		{
			die($e2->showException());
		}

		if (!isset($arData['MY_CAR_ID']) || intval($arData['MY_CAR_ID'])<=0)
		{
			$arData['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}

		$bUpdate = true;
		//Если не задано значение Одометра - необходимо сделать перерасчет всех записей
		if (!isset($arData['ODO']))
		{
			//Получаем список всех записей
			$arList = array(
				'select' => self::$arFuelFields,
				'filter' => array(
					'MY_CAR_ID' => $arData['MY_CAR_ID']
				),
				'order' => array('ODO'=>'ASC','DATE'=>'ASC')
			);
			$arRes = Tables\FuelTable::getList($arList);
			//Рассчитываем дистанцию для списка записей
			self::calculateDistance($arRes);
			//Рассчитываем средний расход для списка записей
			self::calculateExpence($arRes);
			//Рассчитываем количество и стоимость 1 литра бензина в баке для списка записей
			self::calculateTank($arRes,true);
			//Рассчитываем стоимость километра для списка записей
			self::calculationCostKm($arRes);
			//Обновляем рассчитанные данные в базе данных
			foreach ($arRes as $ar_res)
			{
				$arUpdate = array(
					'DISTANCE'      => $ar_res['DISTANCE'],
					'EXPENCE'       => $ar_res['EXPENCE'],
					'TANK_LITER'    => $ar_res['TANK_LITER'],
					'TANK_COST'     => $ar_res['TANK_COST'],
					'COST_KM'       => $ar_res['COST_KM']
				);
				$res = self::updateExpence($ar_res['ID'],$arUpdate);
				$bUpdate = $res;
			}
		}
		//Если значение Одометра задано - перерасчет необходимо производить начиная с предыдущей полной заправки и до
		//конца списка записей. На тот случай, если добавленная запись не является последней, т.е. добавили запись в
		//середину списка
		else
		{
			//Получаем значение одометра для предыдущей полной заправки
			$arRes = Tables\FuelTable::getList(array(
				'select' => array('ODO'),
				'filter' => array(
					'MY_CAR_ID' => $arData['MY_CAR_ID'],
					'<ODO' => $arData['ODO'],
					'FULL' => true
				),
				'order' => array('ODO'=>'DESC'),
				'limit' => 1
			),true);
			//Если данные о предыдущей полной заправки не найдены
			if (!$arRes)
			{
				//Необходимо произвести перерасчет для всех записей DB (вызываем сами себя (рекурсия))
				$bUpdate = self::recalculateExpence(array());
			}
			//Если данные о предыдущей полной заправки найдены
			else
			{
				//Сохраняем значение одометра для записи о предыдущей полной заправки
				$arRes = $arRes[0];
				$odo = $arRes['ODO'];
				//Составляем список записей начиная с предыдущей полной заправки и до конца списка
				$arRes = Tables\FuelTable::getList(array(
					'select' => self::$arFuelFields,
					'filter' => array(
						'MY_CAR_ID' => $arData['MY_CAR_ID'],
						'>=ODO' => $odo
					),
					'order' => array('ODO'=>'ASC','DATE'=>'ASC')
				));
				//Если есть что обрабатывать
				if ($arRes)
				{
					//Рассчитываем дистанцию для списка записей
					self::calculateDistance($arRes);
					//Рассчитываем средний расход для списка записей
					self::calculateExpence($arRes);
					//Рассчитываем количество и стоимость литра топлива в баке для списка записей
					self::calculateTank($arRes);
					//Рассчитываем стоимость километра пути для списка записей
					self::calculationCostKm($arRes);
					//Обновляем рассчитанные данные для списка записей в DB
					foreach ($arRes as $ar_res)
					{
						$arUpdate = array(
							'DISTANCE'      => $ar_res['DISTANCE'],
							'EXPENCE'       => $ar_res['EXPENCE'],
							'TANK_LITER'    => $ar_res['TANK_LITER'],
							'TANK_COST'     => $ar_res['TANK_COST'],
							'COST_KM'       => $ar_res['COST_KM']
						);
						$res = self::updateExpence($ar_res['ID'],$arUpdate);
						$bUpdate = $res;
					}
				}
			}
		}

		//Возвращаем флаг успешности/неуспешности обновления произведенных расчетов
		return $bUpdate;
	}

	/**
	 * Обновляет значение расхода для указанной записи
	 *
	 * @param int   $primary    Значение PRIMARY для записи
	 * @param array $arUpdate   Массив обновляемых значений
	 *
	 * @uses FuelTable::getTableName
	 * @uses FuelTable::getMapArray
	 * @uses MSergeev\Core\Entity\Query
	 * @uses Msergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не указан ID изменяемой записи
	 * @throws Exception\ArgumentNullException Если не указан массив изменяемых полей
	 * @throws Exception\ArgumentTypeException Если вместо массива полей передан не массив
	 *
	 * @return bool
	 */
	protected static function updateExpence ($primary=null,$arUpdate=null)
	{
		try
		{
			if (is_null($primary))
			{
				throw new Exception\ArgumentNullException('primary');
			}
			if (is_null($arUpdate))
			{
				throw new Exception\ArgumentNullException('arUpdate');
			}
			elseif (!is_array($arUpdate))
			{
				throw new Exception\ArgumentTypeException('arUpdate','array');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}
		catch (Exception\ArgumentTypeException $e2)
		{
			$e2->showException();
			return false;
		}

		$query = new Query('update');
		$query->setUpdateParams(
			$arUpdate,
			$primary,
			Tables\FuelTable::getTableName(),
			Tables\FuelTable::getMapArray()
		);
		$res = $query->exec();
		//msEchoVar($res);
		//msDebugDie($res);
		if ($res->getResult())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Сохраняет последнюю использованную марку топлива для автомобиля
	 *
	 * @api
	 *
	 * @param int   $fuelMark   ID марки топлива
	 * @param int   $carID      ID автомобиля
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MSergeev\Core\Lib\Options::setOption
	 *
	 * @throws Exception\ArgumentNullException Если не задан ID марки топлива
	 *
	 * @return bool
	 */
	protected static function setLastUseFuelMark ($fuelMark=null, $carID=null)
	{
		try
		{
			if (is_null($fuelMark))
			{
				throw new Exception\ArgumentNullException('fuelMark');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		if (!CoreLib\Options::setOption('icar_last_fuelmark_'.$carID,$fuelMark))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Подсчитывает расстояние между заправками для списка записей
	 *
	 * @param array &$arData Массив записей
	 */
	protected static function calculateDistance (&$arData=array())
	{
		if (!empty($arData))
		{
			for ($i=0; $i<count($arData); $i++)
			{
				if (isset($arData[$i+1]))
				{
					if (!$arData[$i+1]['MISSING'])
					{
						$arData[$i]['DISTANCE'] = $arData[$i+1]['ODO'] - $arData[$i]['ODO'];
						if ($arData[$i]['DISTANCE']<=0)
						{
							$arData[$i]['DISTANCE'] = NULL;
						}
					}
					else
					{
						$arData[$i]['DISTANCE'] = NULL;
					}
				}
				else
				{
					$arData[$i]['DISTANCE'] = NULL;
				}
			}
		}
	}

	/**
	 * Подсчитывает расход топлива на 100км для списка записей
	 *
	 * @param array &$arData Массив записей
	 */
	protected static function calculateExpence (&$arData=array())
	{
		//Если массив записей не пустой
		if (!empty($arData))
		{
			//Бежим по записям
			for ($i=0; $i<count($arData); $i++)
			{
				//Если существует следующая запись, можно высчитывать расход
				if (isset($arData[$i+1]))
				{
					//Если уже была посчитана дистанция между заправками
					if (isset($arData[$i]['DISTANCE']) && !is_null($arData[$i]['DISTANCE']))
					{
						//Если у текущей заправки статус полного бака
						if ($arData[$i]['FULL'])
						{
							//Если у следующей записи флаг полной заправки и нет статуса пропущенной заправки
							if ($arData[$i+1]['FULL'] && !$arData[$i+1]['MISSING'])
							{
								//Высчитываем расход
								$arData[$i]['EXPENCE'] = round(($arData[$i+1]['LITER'] * 100 / $arData[$i]['DISTANCE']),2);
							}
							//Если у следующей записи нет флага полной заправки, но и нет статуса пропущенной заправки
							elseif (!$arData[$i+1]['FULL'] && !$arData[$i+1]['MISSING'])
							{
								//Устанавливаем первоначальные параметры
								$liters = 0;
								$dist = $arData[$i]['DISTANCE'];
								$exp = TRUE;
								//Бежим от следующей записи
								for ($j=$i+1; $j<count($arData); $j++)
								{
									//Если нет флага пропущенной заправки
									if (!$arData[$j]['MISSING'])
									{
										//Если нет флага полной заправки
										if (!$arData[$j]['FULL'])
										{
											//Вычисляем сумму потраченного бензина
											$liters += $arData[$j]['LITER'];
											//Вычисляем пройденное расстояние
											$dist += $arData[$j]['DISTANCE'];
										}
										//Если встретили флаг полной заправки
										else
										{
											//Добавляем сумму заправленных литров
											$liters += $arData[$j]['LITER'];
											//Выходим из цикла подсчета
											break;
										}
									}
									//Если у записи есть флаг пропущенной заправки
									else
									{
										//Выходим и считаем расход пустым
										$exp = FALSE;
										break;
									}
								}
								//Если расход не должен быть пустым
								if ($exp)
								{
									//Если дистанция больше нуля
									if ($dist>0)
									{
										//Высчитываем расход
										$arData[$i]['EXPENCE'] = round(($liters * 100 / $dist),2);
									}
									//Если дистанция равна 0 (на 0 делить нельзя)
									else
									{
										//Удаляем расход
										$arData[$i]['EXPENCE'] = NULL;
									}
								}
								//Если расход должен быть пустым
								else
								{
									//Удаляем расход
									$arData[$i]['EXPENCE'] = NULL;
								}
							}
							//Если у следующей записи флаг пропущенной заправки
							else
							{
								//Нечего считать, удаляем расход
								$arData[$i]['EXPENCE'] = NULL;
							}
						}
						//Если у текущей заправки нет флага полного бака
						else
						{
							//Нечего считать, удаляем расход
							$arData[$i]['EXPENCE'] = NULL;
						}
					}
					//Если не была посчитана дистанция
					else
					{
						//Нечего считать, удаляем расход
						$arData[$i]['EXPENCE'] = NULL;
					}
				}
				//Если не существует следующей записи, т.е. запись последняя
				else
				{
					//Нечего считать, удаляем расход
					$arData[$i]['EXPENCE'] = NULL;
				}
			}
		}
	}

	/**
	 * Подсчитывает количество топлива в баке и стоимость 1 литра топлива в баке.
	 *
	 * Работает со списком записей
	 *
	 * @param array &$arData    Массив записей
	 * @param bool  $bFirst     Флаг необходимости подсчета для 1й записи
	 */
	protected static function calculateTank (&$arData=array(),$bFirst=false)
	{
		//Если массив записей не пустой
		if (!empty($arData))
		{
			//Если установлен флаг 1й записи
			if ($bFirst)
			{
				//Начинаем с 0 записи считать
				$start = 0;
			}
			//Иначе
			else
			{
				//Начинаем с 1 записи считать
				$start = 1;
			}
			//Бежим по записям
			for ($i=$start; $i<count($arData); $i++)
			{
				//Если запись первая или присутствует флаг пропущенной заправки
				if ($bFirst || $arData[$i]['MISSING'])
				{
					//Количество литров в баке равно количеству заправленных литров
					$arData[$i]['TANK_LITER'] = $arData[$i]['LITER'];
					//Стоимость 1 литра в баке равно стоимости 1 литра заправленного топлива
					$arData[$i]['TANK_COST'] = $arData[$i]['LITER_COST'];
					//Сбрасываем флаг первой записи
					$bFirst = false;
				}
				//Если запись не первая, начинаем подсчет
				else
				{
					//Если в баке меньше литров, чем было заправлено
					if ($arData[$i-1]['TANK_LITER']<$arData[$i]['LITER'])
					{
						//Количество литров в баке равно количеству заправленных литров
						$arData[$i]['TANK_LITER'] = $arData[$i]['LITER'];
						//Стоимость 1 литра в баке равно стоимости 1 литра заправленного топлива
						$arData[$i]['TANK_COST'] = $arData[$i]['LITER_COST'];
					}
					//Иначе
					else
					{
						//Количество литров в баке не изменилось
						$arData[$i]['TANK_LITER'] = $arData[$i-1]['TANK_LITER'];
						//Осталось в баке литров перед заправкой
						$leftL = $arData[$i-1]['TANK_LITER'] - $arData[$i]['LITER'];
						//Стоимость оставшегося в баке топлива
						$leftSum = $leftL * $arData[$i-1]['TANK_COST'];
						//Стоимость заправленного топлива
						$sum = $arData[$i]['LITER'] * $arData[$i]['LITER_COST'];
						//Стоимость топлива в баке после заправки
						$tankSum = $leftSum + $sum;
						//Цена 1 литра топлива в баке
						$arData[$i]['TANK_COST'] = round(($tankSum / $arData[$i]['TANK_LITER']),2);
					}
				}
			}
		}
	}

	/**
	 * Подсчитывает стоимость км пути для списка записей
	 *
	 * @param array &$arData Массив записей
	 */
	protected static function calculationCostKm (&$arData=array())
	{
		//Если список записей не пустой
		if (!empty($arData))
		{
			//Бежим по записям
			for ($i=0; $i<count($arData); $i++)
			{
				//Если существует следующая запись - можно считать стоимость километра
				if (isset($arData[$i+1]))
				{
					//Если существуют данные по пройденной дистанции
					if (isset($arData[$i]['DISTANCE']) && !is_null($arData[$i]['DISTANCE']))
					{
						//Если топлива в баке больше было, чем заправлено на заправке
						if ($arData[$i]['TANK_LITER']>$arData[$i+1]['LITER'])
						{
							//Если текущая заправка полная
							if ($arData[$i]['FULL'])
							{
								//Если следующая заправка полная и у нее нет флага пропущенной заправки
								if ($arData[$i+1]['FULL'] && !$arData[$i+1]['MISSING'])
								{
									//Считаем стоимость километра для текущей заправки
									$arData[$i]['COST_KM'] = round(
										($arData[$i+1]['LITER'] * $arData[$i]['TANK_COST'] / $arData[$i]['DISTANCE']),
										2
									);
								}
								//Если следующая заправка не полная и у нее отсутствует флаг пропущенной заправки
								elseif (!$arData[$i+1]['FULL'] && !$arData[$i+1]['MISSING'])
								{
									//Устанавливаем первоначальные переменные
									$sum = $dist = 0;
									$cost = true;
									//Бежим по списку заправок, начиная со следующей
									for ($j=$i+1; $j<count($arData); $j++)
									{
										//Если у записи о заправки нет флага пропущенной заправки и не пустое значение
										//дистанции
										if (!$arData[$j]['MISSING'] && !is_null($arData[$j]['DISTANCE']))
										{
											//Если у записи флаг полной заправки
											if ($arData[$j]['FULL'])
											{
												//Увеличиваем сумму расхода на заправки путем умножения количества
												//потраченного топлива на цену бензина в баке
												$sum += $arData[$j]['LITER'] * $arData[$j-1]['TANK_COST'];
												//Суммируем пройденную дистанцию
												$dist += $arData[$j-1]['DISTANCE'];
												//Выходим из цикла, т.к. мы искали именно полную заправку
												break;
											}
											else
											{
												//Увеличиваем сумму расхода на заправки путем умножения количества
												//потраченного топлива на цену бензина в баке
												$sum += $arData[$j]['LITER'] * $arData[$j-1]['TANK_COST'];
												//Суммируем пройденную дистанцию
												$dist += $arData[$j-1]['DISTANCE'];
												//И переходим к следующей записи, т.к. мы ищем полную заправку
											}
										}
										//иначе
										else
										{
											//Устанавливаем флаг неудачи подсчета суммы расхода и дистанции
											$cost = false;
											//Выходим из цикла, т.к. искать больше нечего
											break;
										}
									}
									//Если сумма расхода и дистанции были успешно подсчитаны
									if ($cost)
									{
										//Если дистанция больше 0
										if ($dist>0)
										{
											//Подсчитываем стоимость километра пути
											$arData[$i]['COST_KM'] = round(($sum / $dist),2);
										}
										//Если равно 0 (на ноль делить нельзя)
										else
										{
											//Удаляем данные о стоимости километра
											$arData[$i]['COST_KM'] = NULL;
										}
									}
									//если ничего не подсчитали - нечего записывать
									else
									{
										//Удаляем данные о стоимости километра
										$arData[$i]['COST_KM'] = NULL;
									}
								}
								//Если у заправки флаг пропущенной заправки - нечего считать
								else
								{
									//Удаляем данные о стоимости километра
									$arData[$i]['COST_KM'] = NULL;
								}
							}
							//Если текущая заправка не полная - для нее ничего считать не надо
							else
							{
								//Удаляем данные о стоимости километра
								$arData[$i]['COST_KM'] = NULL;
							}
						}
						//Если учтенного бензина в баке меньше, чем заправлено на заправке, т.е. как бы потрачено
						//больше, чем было (чего быть не должно/не возможно) - значит были пропущенные заправки
						//значит считать нужно по-другому
						else
						{
							//Если текущая заправка полная
							if ($arData[$i]['FULL'])
							{
								//Если следующая заправка полная
								if ($arData[$i+1]['FULL'])
								{
									//Высчитываем дистанцию, которую мы могли бы проехать на том количестве топлива,
									//которое учтено в баке, исходя из данных о том какое расстояние было пройдено при
									//заправленном количестве топлива
									$dist = $arData[$i]['TANK_LITER'] * $arData[$i]['DISTANCE'] / $arData[$i+1]['LITER'];
									//Если посчитанная дистанция больше 0
									if ($dist>0)
									{
										//Вычисляем стоимость километра исходя из подсчитанной дистанции
										$arData[$i]['COST_KM'] = round(
											($arData[$i]['TANK_LITER'] * $arData[$i]['TANK_COST'] / $dist),
											2
										);
									}
									//если дистанция равна или меньше 0 (на 0 делить нельзя, меньше нуля не имеет смысла)
									else
									{
										//Удаляем данные о стоимость километра
										$arData[$i]['COST_KM'] = NULL;
									}
								}
								//Если следующая заправка не полная
								else
								{
									//TODO: Не тестировалось. Протестить при возможности (когда будут данные)
									//Устанавливаем первоначальные данные дистанции и суммы потраченного топлива
									$dist = $arData[$i]['TANK_LITER'] * $arData[$i]['DISTANCE'] / $arData[$i+1]['LITER'];
									$sum = $arData[$i]['TANK_LITER'] * $arData[$i]['TANK_COST'];
									$cost = true;
									//Бежим по списку заправок, начиная со следующей
									for ($j=$i+1;$j<count($arData);$j++)
									{
										//Если у записи нет флага пропущенной заправки
										if (!$arData[$j]['MISSING'])
										{
											//Если следующая заправка полная
											if ($arData[$j+1]['FULL'])
											{
												//Суммируем расход на заправку, путем умножения потраченных литров на
												//стоимость 1 литра в баке
												$sum += $arData[$j+1]['LITER'] * $arData[$j]['TANK_COST'];
												//Вычисляем пройденную дистанцию
												$dist += $arData[$j]['DISTANCE'];
												//Выходим из цикла, т.к. мы искали первую полную заправку
												break;
											}
											//Если следующая заправка не полная
											else
											{
												//Суммируем расход на заправку, путем умножения потраченных литров на
												//стоимость 1 литра в баке
												$sum += $arData[$j+1]['LITER'] * $arData[$j]['TANK_COST'];
												//Вычисляем пройденную дистанцию
												$dist += $arData[$j]['DISTANCE'];
											}
										}
										//Если у записи стоит флаг пропущенной заправки
										else
										{
											//Ставим флаг отсутсвия подсчетов суммы расходов и дистанции
											$cost = false;
											//Выходим из цикла, т.к. при пропущенной заправке считать нечего
											break;
										}
									}
									//Если данные о сумме расходов и дистанции были успешно подсчитаны
									if ($cost)
									{
										//Если подсчитанная дистанция больше 0
										if ($dist>0)
										{
											//Считаем стоимость километра пути
											$arData[$i]['COST_KM'] = round(($sum / $dist),2);
										}
										//Если подсчитанная дистанция равна 0 или меньше (на 0 делить нельзя)
										else
										{
											//Удаляем данные о стоимости километра
											$arData[$i]['COST_KM'] = NULL;
										}
									}
									//Если данные не были подсчитаны - делать нечего дальше
									else
									{
										//Удаляем данные о стоимости километра
										$arData[$i]['COST_KM'] = NULL;
									}
								}
							}
							//Если текущая заправка не полная - для нее нечего считать
							else
							{
								//Удаляем данные о стоимости километра
								$arData[$i]['COST_KM'] = NULL;
							}
						}
					}
					//Если данных о пройденной дистанции нет - нечего считать
					else
					{
						//Удаляем данные о стоимости километра
						$arData[$i]['COST_KM'] = NULL;
					}
				}
				//Если не существует слудеющей заправки - нечего считать, недостаточно данных
				else
				{
					//Удаляем данные о стоимости километра
					$arData[$i]['COST_KM'] = NULL;
				}
			}
		}
	}
}