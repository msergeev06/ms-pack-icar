<?php
/**
 * MSergeev\Packages\Icar\Lib\MyCar
 * Мои автомобили
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception;
use MSergeev\Core\Lib\Loc;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Core\Lib\DateHelper;
use MSergeev\Packages\Icar\Tables\MyCarTable;

/**
 * Class MyCar
 * @package MSergeev\Packages\Icar\Lib
 *
 * @var array $arCarField Структура полей таблицы автомобиля
 */
class MyCar
{
	private static $arCarFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'NAME',
		'CAR_BRANDS_ID'             => 'BRAND_ID',
		'CAR_BRANDS_ID.NAME'        => 'BRAND_NAME',
		'CAR_BRANDS_ID.CODE'        => 'BRAND_CODE',
		'CAR_MODEL_ID'              => 'MODEL_ID',
		'CAR_MODEL_ID.NAME'         => 'MODEL_NAME',
		'CAR_MODEL_ID.BRANDS_ID'    => 'MODEL_BRANDS_ID',
		'CAR_MODEL_ID.CODE'         => 'MODEL_CODE',
		'CAR_GEARBOX_ID'            => 'GEARBOX_ID',
		'CAR_GEARBOX_ID.NAME'       => 'GEARBOX_NAME',
		'CAR_GEARBOX_ID.CODE'       => 'GEARBOX_CODE',
		'CAR_BODY_ID'               => 'BODY_ID',
		'CAR_BODY_ID.NAME'          => 'BODY_NAME',
		'CAR_BODY_ID.CODE'          => 'BODY_CODE',
		'YEAR',
		'VIN',
		'CAR_NUMBER',
		'ENGINE_CAPACITY',
		'INTERVAL_TS',
		'COST',
		'MILEAGE',
		'CREDIT',
		'CREDIT_COST',
		'DATE_OSAGO_END',
		'DATE_GTO_END',
		'DEFAULT'
	);

	/**
	 * Добавляет новый автомобиль
	 *
	 * @api
	 *
	 * @param array $arData Массив данных по автомобилю
	 *
	 * @throw Exception\ArgumentNullException
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	public static function addNewCar($arData=array())
	{
		try
		{
			if (empty($arData))
			{
				throw new Exception\ArgumentNullException('arData');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();

			return false;
		}


		$arMap = MyCarTable::getMapArray();
		$arInsert = array();
		foreach ($arMap as $field=>$obMap)
		{
			if (isset($arData[$field]))
			{
				$arInsert[$field] = $arData[$field];
			}
		}

		if (isset($arInsert['DEFAULT']) && $arInsert['DEFAULT'])
		{
			static::uncheckDefaultAllCars();
		}

		$query = new Query('insert');
		$query->setInsertParams(
			$arInsert,
			MyCarTable::getTableName(),
			MyCarTable::getMapArray()
		);
		$res = $query->exec();

		return $res;
	}

	/**
	 * Обновляет данне по автомобилю
	 *
	 * @api
	 *
	 * @param array $arData Массив обновляемых полей таблицы
	 *
	 * @throw Exception\ArgumentNullException
	 *        Exception\ArgumentOutOfRangeException
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	public static function editCar ($arData=array())
	{
		try
		{
			if (empty($arData))
			{
				throw new Exception\ArgumentNullException('arData');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();

			return false;
		}

		$arMap = MyCarTable::getMapArray();
		$arUpdate = array();
		foreach ($arData as $field=>$value)
		{
			try
			{
				if (isset($arMap[$field]))
				{
					$arUpdate[$field] = $arData[$field];
				}
				else
				{
					throw new Exception\ArgumentOutOfRangeException("arData[".$field."]");
				}
			}
			catch (Exception\ArgumentOutOfRangeException $e)
			{
				$e->showException();
			}
		}

		if (isset($arUpdate['DEFAULT']) && $arUpdate['DEFAULT'])
		{
			static::uncheckDefaultAllCars();
		}

		$query = new Query('update');
		$query->setUpdateParams(
			$arUpdate,
			null,
			MyCarTable::getTableName(),
			MyCarTable::getMapArray()
		);
		if (isset($arUpdate['ID']) && intval($arUpdate['ID']) > 0)
			$query->setUpdateParams(
				null,
				intval($arUpdate['ID'])
			);
		$res = $query->exec();

		return $res;
	}

	/**
	 * Проверяет можно ли удалить автомобиль,
	 * т.е. нет ли данных, ссылающихся на данный автомобиль
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @throw Exception\ArgumentNullException
	 * @return bool
	 */
	public static function canDeleteCar ($carID=0)
	{
		try
		{
			if ($carID <=0 )
			{
				throw new Exception\ArgumentNullException('carID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}

		$bLinks = MyCarTable::checkTableLinks();

		return !$bLinks;
	}

	/**
	 * Удаляет указанный автомобиль
	 *
	 * @param int $carID ID автомобиля
	 *
	 * @return array|bool
	 */
	public static function deleteCar ($carID=0)
	{
		try
		{
			if ($carID <=0 )
			{
				throw new Exception\ArgumentNullException('carID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$query = new Query("delete");
		$query->setDeleteParams(
			$carID,
			null,   //false
			MyCarTable::getTableName(),
			MyCarTable::getMapArray(),
			MyCarTable::getTableLinks()
		);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return $ar_res;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Снимает пометку "по-умолчанию" со всех автомобилей
	 *
	 * @api
	 *
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	public static function uncheckDefaultAllCars()
	{
		$helper = new SqlHelper(MyCarTable::getTableName());
		$query = new Query('update');
		$sql = "UPDATE\n\t"
			.$helper->wrapTableQuotes()
			."\nSET\n\t"
			.$helper->wrapQuotes('DEFAULT')
			." = 'N'\nWHERE\n\t"
			.$helper->wrapFieldQuotes('DEFAULT')." = 'Y';";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();

		return $res;
	}

	/**
	 * Возвращает массив данных обо всех автомобилях
	 *
	 * @api
	 *
	 * @param bool $bActive
	 *
	 * @return array
	 */
	public static function getListCar ($bActive=true)
	{
		$arList = array(
			'select' => self::$arCarFields,
			'order' => array('SORT'=>'ASC','NAME'=>'ASC')
		);
		if ($bActive)
		{
			$arList['filter'] = array('ACTIVE'=>true);
		}
		$arResult = MyCarTable::getList($arList);

		return $arResult;
	}

	/**
	 * Подсчитывает общую сумму расходов по автомобилю
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @return float
	 */
	public static function getCarTotalCostsFormatted ($carID=null)
	{
		return Main::moneyFormat(Fuel::getTotalFuelCosts($carID));
	}

	/**
	 * Подсчитывает средний расход топлива автомобиля
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @return float
	 */
	public static function getCarAverageFuelFormatted ($carID=null)
	{
		return Main::averageFormat(Fuel::getAverageFuelConsumption($carID));
	}

	/**
	 * Возвращает отформатированное значение израсходованного топлива
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @return string
	 */
	public static function getCarTotalSpentFuelFormatted ($carID=null)
	{
		return Main::literFormat(Fuel::getCarTotalSpentFuel($carID));
	}

	/**
	 * Подсчитывает общее количество израсходованного топлива
	 *
	 * TODO: Доделать или удалить
	 *
	 * @param int $carID
	 *
	 * @return int
	 */
	public static function getCarTotalSpentFuel ($carID=0)
	{
		$spent = 0;

		return $spent;
	}

	/**
	 * Возвращает текущее значение пробега автомобиля
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @return int
	 */
	public static function getCarCurrentMileage ($carID=null)
	{
		$mileage = 0;
		if (is_null($carID))
		{
			$carID = static::getDefaultCarID();
		}
		$routsOdo = floatval(Odo::getMaxOdo($carID));
		if ($routsOdo > $mileage)
		{
			$mileage = $routsOdo;
		}

		return $mileage;
	}

	/**
	 * Выводит отформатированное текщее значение пробега автомобиля
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @return string
	 */
	public static function getCarCurrentMileageFormatted ($carID=null)
	{
		return Main::mileageFormat(static::getCarCurrentMileage ($carID));
	}

	/**
	 * Возвращает массив параметров указанного автомобиля
	 *
	 * @api
	 *
	 * @param int $carID ID автомобиля. Если не указан - будет выбран автомобиль по-умолчанию
	 *
	 * @return array|bool
	 */
	public static function getCarByID ($carID=0)
	{
		if ($carID==0)
		{
			$carID = static::getDefaultCarID();
		}

		$arResult = MyCarTable::getList(array(
			'select' => self::$arCarFields,
			'filter' => array('ID' => $carID),
			'limit' => 1
		));
		if (isset($arResult[0]))
		{
			$arResult = $arResult[0];
		}
		return $arResult;
	}

	/**
	 * Возвращает ID автомобиля по-умолчанию
	 *
	 * @api
	 *
	 * @return bool|int
	 */
	public static function getDefaultCarID ()
	{
		$arRes = MyCarTable::getList(array(
			'select' => array('ID'),
			'filter' => array(
				'ACTIVE' => true,
				'DEFAULT' => true
			),
			'limit' => 1
		));
		if (isset($arRes[0]))
		{
			return $arRes[0]['ID'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Выводит тег <select>, содержащий список автомобилей
	 *
	 * @api
	 *
	 * @param string $strBoxName        Название тега <select>
	 * @param null   $strSelectedVal    Значение по-умолчанию
	 * @param string $field1            Прочие параметры тега <select>
	 * @use SelectBox() Функция вывода тега <select>
	 *
	 * @return string
	 */
	public static function showSelectCars ($strBoxName, $strSelectedVal = null, $field1="class=\"typeselect\"")
	{
		$arCars = static::getListCar();
		if (is_null($strSelectedVal))
		{
			$strSelectedVal = static::getDefaultCarID();
		}

		$arValues = array();
		foreach($arCars as $arCar)
		{
			$arValues[] = array(
				"NAME" => $arCar['NAME'].' - '.$arCar['CAR_NUMBER'],
				"VALUE" => $arCar['ID']
			);
		}

		return SelectBox($strBoxName, $arValues, "", $strSelectedVal, $field1);
	}

	/**
	 * Возвращает значение одометра автомобиля на момент покупки
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля. Если не указан, будет выбран автомобиль по-умолчанию
	 *
	 * @return bool|float Значение одометра, либо false
	 */
	public static function getBuyCarOdo ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		if ($arRes = MyCarTable::getList(array(
			'select' => array('MILEAGE'),
			'filter' => array('ID'=>$carID)
		)))
		{
			return floatval($arRes[0]['MILEAGE']);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Функция проверяет необходимость прохождения ТО и продление страховки.
	 * Если находит - создает массив сообщений, которые потом выводятся на экран.
	 *
	 * @api
	 *
	 * @return array Массив с напоминаниями
	 */
	public static function checkAlerts ()
	{
		$arAlerts = array();
		$arCars = static::getListCar();
		$dateHelper = new DateHelper();

		$time = time();
		foreach ($arCars as &$arCar)
		{
			//Проверка необходимости ТО
			$arCar['CURRENT_MILEAGE'] = static::getCarCurrentMileage ($arCar['ID']);
			//echo 'CURRENT_MILEAGE ='.$arCar['CURRENT_MILEAGE']."<br>";
			//echo 'count = floor(CURRENT_MILEAGE / INTERVAL_TS)<br>';
			$count = floor($arCar['CURRENT_MILEAGE'] / $arCar['INTERVAL_TS']);
			//echo $count.' = floor('.$arCar['CURRENT_MILEAGE'].' / '.$arCar['INTERVAL_TS'].')<br>';
			//echo 'minus = INTERVAL_TS * count<br>';
			$minus = $arCar['INTERVAL_TS'] * $count;
			//echo $minus.' = '.$arCar['INTERVAL_TS'].' * '.$count.'<br>';
			//echo 'curMil = CURRENT_MILEAGE - minus<br>';
			$curMil = $arCar['CURRENT_MILEAGE'] - $minus;
			//echo $curMil.' = '.$arCar['CURRENT_MILEAGE'].' - '.$minus.'<br>';
			//echo 'raznica = INTERVAL_TS - curMil<br>';
			$raznica = $arCar['INTERVAL_TS'] - $curMil;
			//echo $raznica.' = '.$arCar['INTERVAL_TS'].' - '.$curMil.'<br>';
			if ($raznica > 500 && $raznica <= 1000)
			{
				$arAlerts[] = array(
					'COLOR' => 'green',
					'TYPE' => 'odo',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_ts_green',array(
						'CAR_NAME'=>$arCar['NAME'],
						'KM'=>$raznica
					))
				);
			}
			elseif ($raznica > 300 && $raznica <= 500)
			{
				$arAlerts[] = array(
					'COLOR' => 'yellow',
					'TYPE' => 'odo',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_ts_yellow',array(
						'CAR_NAME'=>$arCar['NAME'],
						'KM'=>$raznica
					))
				);
			}
			elseif ($raznica >= 0 && $raznica <= 300)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'odo',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_ts_red',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}

			//Проверка необходимости оформления новой страховки и прохождения ГТО
			$carOsagoTime = $dateHelper->getDateTimestamp($arCar['DATE_OSAGO_END']);
			$carGtoTime = $dateHelper->getDateTimestamp($arCar['DATE_GTO_END']);
			$carOsagoDay = floor(($carOsagoTime - $time) / (60 * 60 * 24)) + 1;
			$carGtoDay = floor(($carGtoTime - $time) / (60 * 60 * 24)) + 1;
			if ($carOsagoDay > 5 && $carOsagoDay <= 30)
			{
				$arAlerts[] = array(
					'COLOR' => 'green',
					'TYPE' => 'osago',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_osago_green',array(
						'CAR_NAME'=>$arCar['NAME'],
						'DAY_NUM'=>$carOsagoDay,
						'DAY_TEXT'=>$dateHelper->showDaysRus($carOsagoDay)
					))
				);
			}
			elseif ($carOsagoDay > 0 && $carOsagoDay <= 5)
			{
				$arAlerts[] = array(
					'COLOR' => 'yellow',
					'TYPE' => 'osago',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_osago_yellow',array(
						'CAR_NAME'=>$arCar['NAME'],
						'DAY_NUM'=>$carOsagoDay,
						'DAY_TEXT'=>$dateHelper->showDaysRus($carOsagoDay)
					))
				);
			}
			elseif ($carOsagoDay == 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'osago',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_osago_red',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}
			elseif ($carOsagoDay < 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'osago',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_osago_red2',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}

			if ($carGtoDay > 5 && $carGtoDay <= 30)
			{
				$arAlerts[] = array(
					'COLOR' => 'green',
					'TYPE' => 'gto',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_gto_green',array(
						'CAR_NAME'=>$arCar['NAME'],
						'DAY_NUM'=>$carGtoDay,
						'DAY_TEXT'=>$dateHelper->showDaysRus($carGtoDay)
					))
				);
			}
			elseif ($carGtoDay > 0 && $carGtoDay <= 5)
			{
				$arAlerts[] = array(
					'COLOR' => 'yellow',
					'TYPE' => 'gto',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_gto_yellow',array(
						'CAR_NAME'=>$arCar['NAME'],
						'DAY_NUM'=>$carGtoDay,
						'DAY_TEXT'=>$dateHelper->showDaysRus($carGtoDay)
					))
				);
			}
			elseif ($carGtoDay == 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'gto',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_gto_red',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}
			elseif ($carGtoDay < 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'gto',
					'TEXT' => Loc::getPackMessage('icar','mycars_alert_gto_red2',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}

			//msDebug($arCar);
		}
		unset($arCar);


		return $arAlerts;
	}
}