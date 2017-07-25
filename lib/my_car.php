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
use MSergeev\Packages\Icar\Tables\MyCarTable;
use MSergeev\Core\Lib as CoreLib;

/**
 * Class MyCar
 *
 * Events:
 * OnBeforeAddNewCar - Перед добавлением нового автомобиля. Передается массив параметров
 * OnAfterAddNewCar - После добавления нового автомобиля. Передается массив параметров и ID записи в DB
 * OnBeforeUpdateCar - Перед изменением данных автомобиля. Передается массив изменяемых параметров и ID записи
 * OnAfterUpdateCar - После изменения данных автомобился. Передается массив измененных параметров и ID записи
 */
class MyCar
{
	/**
     * @var array Структура полей таблицы автомобиля
	 */
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
		'DATE_BUY',
		'DATE_CELL',
		'DEFAULT'
	);

	/**
	 * Добавляет новый автомобиль, обрабатывая данные из формы
	 *
	 * @param array $arPost Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses CarModel::addNewModel
	 * @uses MyCar::addNewCarDB
	 *
	 * @throws Exception\ArgumentNullException если массив пуст
	 *
	 * @return bool|int
	 */
	public static function addNewCarFromPost ($arPost=array())
	{
		try
		{
			if (empty($arPost))
			{
				throw new Exception\ArgumentNullException('$arPost');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}

		$arParams = array();
		//Проверка полей
		Fields::validateFields($arPost,$arParams);
		if (!isset($arParams['NAME']))
		{
			Errors::addError('NAME','Не указано название автомобиля');
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		if (
			!isset($arParams['CAR_MODEL_ID']) &&
			isset($arParams['CAR_MODEL_TEXT']) &&
			isset($arParams['CAR_BRANDS_ID'])
		)
		{
			if ($arParams['CAR_MODEL_ID'] = CarModel::addNewModel($arParams['CAR_BRANDS_ID'],$arParams['CAR_MODEL_TEXT']))
			{
				unset($arParams['CAR_MODEL_TEXT']);
			}
			else
			{
				unset($arParams['CAR_MODEL_ID']);
				unset($arParams['CAR_MODEL_TEXT']);
			}
		}

		if ($insertID = self::addNewCarDB($arParams))
		{
			return $insertID;
		}
		else
		{
			return false;
		}

	}

	/**
	 * Обновляет данные по автомобилю из формы
	 *
	 * @param array $arPost Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses CarModel::addNewModel
	 * @uses MyCar::updateCarDB
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return bool
	 */
	public static function updateCarFromPost ($arPost)
	{
		$arParams = array ();
		//Проверка полей
		Fields::validateFields($arPost,$arParams);

		if (
			!isset($arParams['CAR_MODEL_ID']) &&
			isset($arParams['CAR_MODEL_TEXT']) &&
			isset($arParams['CAR_BRANDS_ID'])
		)
		{
			if ($arParams['CAR_MODEL_ID'] = CarModel::addNewModel($arParams['CAR_BRANDS_ID'],$arParams['CAR_MODEL_TEXT']))
			{
				unset($arParams['CAR_MODEL_TEXT']);
			}
			else
			{
				unset($arParams['CAR_MODEL_ID']);
				unset($arParams['CAR_MODEL_TEXT']);
			}
		}

		$res = self::updateCarDB($arPost['car_id'], $arParams);
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
	 * Проверяет можно ли удалить автомобиль, т.е. нет ли данных, ссылающихся на данный автомобиль
	 *
	 * @param int $carID ID автомобиля
	 *
	 * @uses MyCarTable::checkTableLinks
	 *
	 * @throws Exception\ArgumentNullException если ID автомобиля меньше или равно 0
	 *
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
	 * @uses MyCarTable::getTableName
	 * @uses MyCarTable::getMapArray
	 * @uses MyCarTable::getTableLinks
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException если ID автомобиля меньше или равно 0
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
	 * @uses MyCarTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	public static function uncheckDefaultAllCars()
	{
		$helper = new CoreLib\SqlHelper(MyCarTable::getTableName());
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
	 * Возвращает массив данных обо всех автомобилях, либо о заданном
	 *
	 * @param bool      $bActive    Флаг - выбирать только активные
	 * @param null|int  $carID      ID автомобиля
	 *
	 * @uses MyCarTable::getList
	 *
	 * @return array
	 */
	public static function getList ($bActive=true,$carID=null)
	{
		$arList = array(
			'select' => self::$arCarFields,
			'order' => array('SORT'=>'ASC','NAME'=>'ASC')
		);
		if ($bActive)
		{
			$arList['filter'] = array('ACTIVE'=>true);
		}
		if (!is_null($carID) && intval($carID)>0)
		{
			$arList['filter'] = array_merge($arList['filter'],array('MY_CAR_ID'=>$carID));
		}
		$arResult = MyCarTable::getList($arList);

		return $arResult;
	}

	/**
	 * Возвращает количество дней владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCar::getList
	 *
	 * @return int
	 */
	public static function getOwnershipDays ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = self::getDefaultCarID();
		}

		$carInfo = self::getList(true,$carID);
		if (isset($carInfo[0]))
		{
			$carInfo = $carInfo[0];
		}

		return intval(((time()-strtotime($carInfo['DATE_BUY']))/(60*60*24)));
	}

	/**
	 * Возвращает количество месяцев владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getOwnershipDays
	 *
	 * @return int
	 */
	public static function getOwnershipMonths ($carID=null)
	{
		$days = self::getOwnershipDays($carID);
		if ($days>0)
		{
			return intval($days/30.4);
		}

		return 0;
	}

	/**
	 * Возвращает количество лет владения автомобилем
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getOwnershipDays
	 *
	 * @return int
	 */
	public static function getOwnershipYears ($carID=null)
	{
		$days = self::getOwnershipDays($carID);
		if ($days>0)
		{
			return intval($days/365);
		}

		return 0;
	}

	/**
	 * Возвращает массив параметров указанного автомобиля
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCarTable::getList
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
		if ($arResult && isset($arResult[0]))
		{
			$arResult = $arResult[0];
		}

		return $arResult;
	}

	/**
	 * Возвращает ID автомобиля по-умолчанию
	 *
	 * @uses MyCarTable::getList
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
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if ($arRes)
		{
			return $arRes['ID'];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Выводит тег select, содержащий список автомобилей
	 *
	 * @param string $strBoxName        Название тега select
	 * @param mixed  $strSelectedVal    Значение по-умолчанию
	 * @param string $field1            Прочие параметры тега select
	 *
	 * @uses MyCar::getList
	 * @uses MyCar::getDefaultCarID
	 * @uses SelectBox Функция вывода тега select
	 *
	 * @return string
	 */
	public static function showSelectCars ($strBoxName, $strSelectedVal = null, $field1="class=\"typeselect\"")
	{
		$arCars = static::getList();
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
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCarTable::getList
	 *
	 * @return bool|float Значение одометра
	 */
	public static function getBuyCarOdo ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = self::getDefaultCarID();
		}

		$arRes = MyCarTable::getList(array(
			'select' => array('MILEAGE'),
			'filter' => array('ID'=>$carID),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if ($arRes)
		{
			return floatval($arRes['MILEAGE']);
		}

		return 0;
	}

	/**
	 * Функция проверяет необходимость прохождения ТО и продление страховки.
	 *
	 * Если находит - создает массив сообщений, которые потом выводятся на экран.
	 *
	 * @uses MyCar::getList
	 * @uses Odo::getCurrentMileage
	 * @uses MSergeev\Core\Lib\DateHelper
	 * @uses MSergeev\Core\Lib\Loc
	 *
	 * @return array Массив с напоминаниями
	 */
	public static function checkAlerts ()
	{
		$arAlerts = array();
		$arCars = static::getList();
		$dateHelper = new CoreLib\DateHelper();

		$time = time();
		foreach ($arCars as &$arCar)
		{
			//Проверка необходимости ТО
			$arCar['CURRENT_MILEAGE'] = Odo::getCurrentMileage ($arCar['ID']);
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_ts_green',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_ts_yellow',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_ts_red',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_osago_green',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_osago_yellow',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_osago_red',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}
			elseif ($carOsagoDay < 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'osago',
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_osago_red2',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}

			if ($carGtoDay > 5 && $carGtoDay <= 30)
			{
				$arAlerts[] = array(
					'COLOR' => 'green',
					'TYPE' => 'gto',
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_gto_green',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_gto_yellow',array(
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
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_gto_red',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}
			elseif ($carGtoDay < 0)
			{
				$arAlerts[] = array(
					'COLOR' => 'red',
					'TYPE' => 'gto',
					'TEXT' => CoreLib\Loc::getPackMessage('icar','mycars_alert_gto_red2',array(
						'CAR_NAME'=>$arCar['NAME']
					))
				);
			}

			//msDebug($arCar);
		}
		unset($arCar);


		return $arAlerts;
	}

	/**
	 * Добавляет новый автомобиль в DB
	 *
	 * @param array $arData Массив данных по автомобилю
	 *
	 * @uses MyCarTable::getMapArray
	 * @uses MyCarTable::getTableName
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throw MSergeev\Core\Exception\\ArgumentNullException если массив данных пуст
	 *
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	protected static function addNewCarDB($arData=array())
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddNewCar'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$arInsert));
				}
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

		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddNewCar'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($arInsert,$res->getInsertId()));
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

	/**
	 * Обновляет данные по автомобилю в DB
	 *
	 * @param int   $primary    ID обновляемого элемента
	 * @param array $arData     Массив обновляемых полей таблицы
	 *
	 * @uses MyCarTable::getMapArray
	 * @uses MyCarTable::getTableName
	 * @uses MSergeev\Core\Lib\Events
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @throw MSergeev\Core\Exception\ArgumentNullException
	 * @throw MSergeev\Core\Exception\ArgumentOutOfRangeException
	 *
	 * @return \MSergeev\Core\Lib\DBResult
	 */
	protected static function updateCarDB ($primary, $arData=array())
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateCar'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$arUpdate,&$primary));
				}
			}
		}

		if (isset($arUpdate['DEFAULT']) && $arUpdate['DEFAULT'])
		{
			static::uncheckDefaultAllCars();
		}

		$query = new Query('update');
		$query->setUpdateParams(
			$arUpdate,
			$primary,
			MyCarTable::getTableName(),
			MyCarTable::getMapArray()
		);
		if (isset($arUpdate['ID']) && intval($arUpdate['ID']) > 0)
			$query->setUpdateParams(
				null,
				intval($arUpdate['ID'])
			);
		$res = $query->exec();

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateCar'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array($arUpdate,$primary));
				}
			}
		}

		return $res;
	}

}