<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Core\Lib\DateHelper;
use MSergeev\Packages\Icar\Tables\CarBodyTable;
use MSergeev\Packages\Icar\Tables\CarBrandTable;
use MSergeev\Packages\Icar\Tables\CarGearboxTable;
use MSergeev\Packages\Icar\Tables\CarModelTable;
use MSergeev\Packages\Icar\Tables\MyCarTable;

class MyCar
{
	/**
	 * Добавляет новый автомобиль
	 *
	 * @param array $arData
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
				$arInsert[0][$field] = $arData[$field];
			}
		}

		if (isset($arInsert[0]['DEFAULT']) && $arInsert[0]['DEFAULT'])
		{
			$res = static::uncheckDefaultAllCars();
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
	 * @param array $arData
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
			$res = static::uncheckDefaultAllCars();
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
			null,   //true
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
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	public static function uncheckDefaultAllCars()
	{
		$helper = new SqlHelper();
		$query = new Query('update');
		$sql = "UPDATE\n\t"
			.$helper->wrapQuotes(MyCarTable::getTableName())
			."\nSET\n\t"
			.$helper->wrapQuotes('DEFAULT')
			." = 'N'\nWHERE\n\t"
			.$helper->wrapQuotes('DEFAULT')." = 'Y';";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();

		return $res;
	}

	/**
	 * Возвращает массив данных обо всех автомобилях
	 *
	 * @param bool $bActive
	 *
	 * @return array
	 */
	public static function getListCar ($bActive=true)
	{
		$helper = new SqlHelper();

		$sql = "SELECT\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('ID')." AS "
			.$helper->wrapQuotes('ID').",\n\t";
		$sql .= $helper->wrapQuotes('car')
			.".".$helper->wrapQuotes('ACTIVE')." AS "
			.$helper->wrapQuotes('ACTIVE').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('SORT')." AS "
			.$helper->wrapQuotes('SORT').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('NAME')." AS "
			.$helper->wrapQuotes('NAME').",\n\t";
		$sql .= $helper->wrapQuotes('brand')."."
			.$helper->wrapQuotes('ID')." AS "
			.$helper->wrapQuotes('BRAND_ID').",\n\t";
		$sql .= $helper->wrapQuotes('brand')."."
			.$helper->wrapQuotes('NAME')." AS "
			.$helper->wrapQuotes('BRAND_NAME').",\n\t";
		$sql .= $helper->wrapQuotes('brand')."."
			.$helper->wrapQuotes('CODE')." AS "
			.$helper->wrapQuotes('BRAND_CODE').",\n\t";
		$sql .= $helper->wrapQuotes('model')."."
			.$helper->wrapQuotes('ID')." AS "
			.$helper->wrapQuotes('MODEL_ID').",\n\t";
		$sql .= $helper->wrapQuotes('model')."."
			.$helper->wrapQuotes('NAME')." AS "
			.$helper->wrapQuotes('MODEL_NAME').",\n\t";
		$sql .= $helper->wrapQuotes('model')."."
			.$helper->wrapQuotes('BRANDS_ID')." AS "
			.$helper->wrapQuotes('MODEL_BRANDS_ID').",\n\t";
		$sql .= $helper->wrapQuotes('model')."."
			.$helper->wrapQuotes('CODE')." AS "
			.$helper->wrapQuotes('MODEL_CODE').",\n\t";
		$sql .= $helper->wrapQuotes('gear')."."
			.$helper->wrapQuotes('ID')." AS "
			.$helper->wrapQuotes('GEARBOX_ID').",\n\t";
		$sql .= $helper->wrapQuotes('gear')."."
			.$helper->wrapQuotes('NAME')." AS "
			.$helper->wrapQuotes('GEARBOX_NAME').",\n\t";
		$sql .= $helper->wrapQuotes('gear')."."
			.$helper->wrapQuotes('CODE')." AS "
			.$helper->wrapQuotes('GEARBOX_CODE').",\n\t";
		$sql .= $helper->wrapQuotes('body')."."
			.$helper->wrapQuotes('ID')." AS "
			.$helper->wrapQuotes('BODY_ID').",\n\t";
		$sql .= $helper->wrapQuotes('body')."."
			.$helper->wrapQuotes('NAME')." AS "
			.$helper->wrapQuotes('BODY_NAME').",\n\t";
		$sql .= $helper->wrapQuotes('body')."."
			.$helper->wrapQuotes('CODE')." AS "
			.$helper->wrapQuotes('BODY_CODE').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('YEAR')." AS "
			.$helper->wrapQuotes('YEAR').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('VIN')." AS "
			.$helper->wrapQuotes('VIN').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CAR_NUMBER')." AS "
			.$helper->wrapQuotes('CAR_NUMBER').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('ENGINE_CAPACITY')." AS "
			.$helper->wrapQuotes('ENGINE_CAPACITY').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('INTERVAL_TS')." AS "
			.$helper->wrapQuotes('INTERVAL_TS').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('COST')." AS "
			.$helper->wrapQuotes('COST').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('MILEAGE')." AS "
			.$helper->wrapQuotes('MILEAGE').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CREDIT')." AS "
			.$helper->wrapQuotes('CREDIT').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CREDIT_COST')." AS "
			.$helper->wrapQuotes('CREDIT_COST').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('DATE_OSAGO_END')." AS "
			.$helper->wrapQuotes('DATE_OSAGO_END').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('DATE_GTO_END')." AS "
			.$helper->wrapQuotes('DATE_GTO_END').",\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('DEFAULT')." AS "
			.$helper->wrapQuotes('DEFAULT')."\n";
		$sql .= "FROM\n\t";
		$sql .= $helper->wrapQuotes('ms_icar_my_car')." AS "
			.$helper->wrapQuotes('car')." ,\n\t";
		$sql .= $helper->wrapQuotes('ms_icar_car_brand')." AS "
			.$helper->wrapQuotes('brand')." ,\n\t";
		$sql .= $helper->wrapQuotes('ms_icar_car_model')." AS "
			.$helper->wrapQuotes('model')." ,\n\t";
		$sql .= $helper->wrapQuotes('ms_icar_car_gearbox')." AS "
			.$helper->wrapQuotes('gear')." ,\n\t";
		$sql .= $helper->wrapQuotes('ms_icar_car_body')." AS "
			.$helper->wrapQuotes('body')."\n";
		$sql .= "WHERE\n\t";
		if ($bActive)
		{
			$sql .= $helper->wrapQuotes('car')."."
				.$helper->wrapQuotes('ACTIVE')." = 'Y' AND\n\t";
		}
		$sql .= $helper->wrapQuotes('brand')."."
			.$helper->wrapQuotes('ID')." = "
			.$helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CAR_BRANDS_ID')." AND\n\t";
		$sql .= $helper->wrapQuotes('model')."."
			.$helper->wrapQuotes('ID')." = "
			.$helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CAR_MODEL_ID')." AND\n\t";
		$sql .= $helper->wrapQuotes('gear')."."
			.$helper->wrapQuotes('ID')." = "
			.$helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CAR_GEARBOX_ID')." AND\n\t";
		$sql .= $helper->wrapQuotes('body')."."
			.$helper->wrapQuotes('ID')." = "
			.$helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('CAR_BODY_ID')."\n";
		$sql .= "ORDER BY\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('SORT')." ASC,\n\t";
		$sql .= $helper->wrapQuotes('car')."."
			.$helper->wrapQuotes('NAME')." ASC";


		$query = new Query('select');
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		$arResult = array();
		$i=0;
		while ($ar_res = $res->fetch())
		{
			foreach ($ar_res as $key=>$value)
			{
				if (!is_numeric($key))
				{
					$arResult[$i][$key] = $value;
				}
			}
			$i++;
		}
		$arResult = static::fetchCarData($arResult);

		return $arResult;
	}

	/**
	 * Получает развернутые данные об автомобиле из других таблиц
	 *
	 * @param $arResult
	 *
	 * @return mixed
	 */
	protected static function fetchCarData ($arResult)
	{
		$myCarMap = MyCarTable::getMapArray();
		$carBodyMap = CarBodyTable::getMapArray();
		$carBrandMap = CarBrandTable::getMapArray();
		$carGearboxMap = CarGearboxTable::getMapArray();
		$carModelMap = CarModelTable::getMapArray();
		foreach ($arResult as $key=>&$arCar)
		{
			foreach ($arCar as $field=>&$value)
			{
				if (isset($myCarMap[$field]))
				{
					$value = $myCarMap[$field]->fetchDataModification($value);
				}
				elseif (strstr($field,'BRAND_'))
				{
					switch ($field)
					{
						case 'BRAND_ID':
							$value = $carBrandMap['ID']->fetchDataModification($value);
							$arCar['BRAND']['ID'] = $value;
							break;
						case 'BRAND_NAME':
							$value = $carBrandMap['NAME']->fetchDataModification($value);
							$arCar['BRAND']['NAME'] = $value;
							break;
						case 'BRAND_CODE':
							$value = $carBrandMap['CODE']->fetchDataModification($value);
							$arCar['BRAND']['CODE'] = $value;
							break;
					}
				}
				elseif (strstr($field,'MODEL_'))
				{
					switch ($field)
					{
						case 'MODEL_ID':
							$value = $carModelMap['ID']->fetchDataModification($value);
							$arCar['MODEL']['ID'] = $value;
							break;
						case 'MODEL_NAME':
							$value = $carModelMap['NAME']->fetchDataModification($value);
							$arCar['MODEL']['NAME'] = $value;
							break;
						case 'MODEL_BRANDS_ID':
							$value = $carModelMap['BRANDS_ID']->fetchDataModification($value);
							$arCar['MODEL']['BRAND_ID'] = $value;
							break;
						case 'MODEL_CODE':
							$value = $carModelMap['CODE']->fetchDataModification($value);
							$arCar['MODEL']['CODE'] = $value;
							break;
					}
				}
				elseif (strstr($field,'GEARBOX_'))
				{
					switch ($field)
					{
						case 'GEARBOX_ID':
							$value = $carGearboxMap['ID']->fetchDataModification($value);
							$arCar['GEARBOX']['ID'] = $value;
							break;
						case 'GEARBOX_NAME':
							$value = $carGearboxMap['NAME']->fetchDataModification($value);
							$arCar['GEARBOX']['NAME'] = $value;
							break;
						case 'GEARBOX_CODE':
							$value = $carGearboxMap['CODE']->fetchDataModification($value);
							$arCar['GEARBOX']['CODE'] = $value;
							break;
					}
				}
				elseif (strstr($field,'BODY_'))
				{
					switch ($field)
					{
						case 'BODY_ID':
							$value = $carBodyMap['ID']->fetchDataModification($value);
							$arCar['BODY']['ID'] = $value;
							break;
						case 'BODY_NAME':
							$value = $carBodyMap['NAME']->fetchDataModification($value);
							$arCar['BODY']['NAME'] = $value;
							break;
						case 'BODY_CODE':
							$value = $carBodyMap['CODE']->fetchDataModification($value);
							$arCar['BODY']['CODE'] = $value;
							break;
					}
				}
			}
			unset($value);
		}
		unset($arCar);

		return $arResult;
	}

	/**
	 * Подсчитывает общую сумму расходов по автомобилю
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
	 * @param int $carID
	 *
	 * @return array|bool
	 */
	public static function getCarByID ($carID=0)
	{
		if ($carID==0) $carID = static::getDefaultCarID();

		$arResult = MyCarTable::getList(array(
			"filter" => array(
				"ID" => $carID
			),
			"limit" => 1
		));
		if (isset($arResult[0]))
			$arResult = $arResult[0];
		return $arResult;
	}

	/**
	 * @deprecated
	 * @see getDefaultCarID
	 * @return bool
	 */
	public static function getDefaultCar ()
	{
		return static::getDefaultCarID();
	}

	/**
	 * Возвращает ID автомобиля по-умолчанию
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

	public static function getBuyCarOdo ($carID=null)
	{
		try
		{
			if (is_null($carID))
			{
				throw new Exception\ArgumentNullException('carID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
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
					'TEXT' => 'Обратите внимание! Скоро необходимо будет проходить плановое ТО на автомобиле "'.$arCar['NAME'].'"! Осталось проехать '.$raznica.' км'
				);
			}
			elseif ($raznica > 300 && $raznica <= 500)
			{
				$arAlerts[] = array(
					'COLOR' => 'yellow',
					'TYPE' => 'odo',
					'TEXT' => 'Внимание! В ближайшее время необходимо пройти плановое ТО на автомобиле "'.$arCar['NAME'].'"! Осталось проехать '.$raznica.' км'
				);
			}
			elseif ($raznica >= 0 && $raznica <= 300)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'odo',
					'TEXT' => 'ВНИМАНИЕ! Необходимо в срочном порядке пройти плановое ТО на автомобиле "'.$arCar['NAME'].'"!'
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
					'TEXT' => 'Заканчивается срок действия полиса ОСАГО у автомобиля "'.$arCar['NAME'].'". Рекомендуем позаботится о продлении заранее. Осталось '.$carOsagoDay.' '.$dateHelper->showDaysRus($carOsagoDay)
				);
			}
			elseif ($carOsagoDay > 0 && $carOsagoDay <= 5)
			{
				$arAlerts[] = array(
					'COLOR' => 'yellow',
					'TYPE' => 'osago',
					'TEXT' => 'Внимание! Скоро закончится срок действия полиса ОСАГО у автомобиля "'.$arCar['NAME'].'". Необходимо продлить полис! Осталось '.$carOsagoDay.' '.$dateHelper->showDaysRus($carOsagoDay)
				);
			}
			elseif ($carOsagoDay == 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'osago',
					'TEXT' => 'ВНИМАНИЕ! Сегодня заканчиватся срок действия полиса ОСАГО у автомобиля "'.$arCar['NAME'].'". Необходимо СРОЧНО продлить полис!'
				);
			}
			elseif ($carOsagoDay < 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'osago',
					'TEXT' => 'ВНИМАНИЕ! Закончился срок действия полиса ОСАГО у автомобиля "'.$arCar['NAME'].'". Необходимо СРОЧНО продлить полис!'
				);
			}

			if ($carGtoDay > 5 && $carGtoDay <= 30)
			{
				$arAlerts[] = array(
					'COLOR' => 'green',
					'TYPE' => 'gto',
					'TEXT' => 'Подходит дата очередного ГТО у автомобиля "'.$arCar['NAME'].'". Подготовьте автомобиль к осмотру. Осталось '.$carGtoDay.' '.$dateHelper->showDaysRus($carGtoDay)
				);
			}
			elseif ($carGtoDay > 0 && $carGtoDay <= 5)
			{
				$arAlerts[] = array(
					'COLOR' => 'yellow',
					'TYPE' => 'gto',
					'TEXT' => 'Внимание! Скоро подойдет дата очередного ГТО у автомобиля "'.$arCar['NAME'].'". Последняя возможность подготовить автомобиль к осмотру! Осталось '.$carGtoDay.' '.$dateHelper->showDaysRus($carGtoDay)
				);
			}
			elseif ($carGtoDay == 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'gto',
					'TEXT' => 'ВНИМАНИЕ! Сегодня последний день действия ГТО на автомобиле "'.$arCar['NAME'].'". Необходимо СРОЧНО пройти ГТО!'
				);
			}
			elseif ($carGtoDay < 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'gto',
					'TEXT' => 'ВНИМАНИЕ! Необходимо СРОЧНО пройти ГТО на автомобиле "'.$arCar['NAME'].'". Езда без ГТО может привести к штрафу и лишению прав. Необходимо СРОЧНО пройти ГТО!'
				);
			}

			//msDebug($arCar);
		}
		unset($arCar);


		return $arAlerts;
	}
}