<?php
/**
 * MSergeev\Packages\Icar\Lib\Odo
 * Пробег и маршруты
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Exception;
use MSergeev\Core\Lib\Events;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Entity\Query;
use MSergeev\Core\Lib\Loc;
use MSergeev\Core\Lib\DateHelper;
use MSergeev\Core\Lib\Options;

/**
 * Class Odo
 *
 * Events:
 * OnBeforeAddNewRoute - Перед добавлением нового маршрута. Передается массив полей
 * OnAfterAddNewRoute - После добавления нового маршрута. Передается массив полей и ID записи
 */
class Odo
{
	/**
	 * @var array Массив полей маршрута
	 */
	private static $arRoutsFields = array(
		'ID',
		'MY_CAR_ID',
		'MY_CAR_ID.NAME'                        => 'MY_CAR_NAME',
		'MY_CAR_ID.CAR_NUMBER'                  => 'MY_CAR_NUMBER',
		'DATE',
		'START_POINTS_ID',
		'START_POINTS_ID.NAME'                  => 'START_POINT_NAME',
		'START_POINTS_ID.POINT_TYPES_ID'        => 'START_POINT_TYPE_ID',
		'START_POINTS_ID.POINT_TYPES_ID.NAME'   => 'START_POINT_TYPE_NAME',
		'START_POINTS_ID.POINT_TYPES_ID.CODE'   => 'START_POINT_TYPE_CODE',
		'START_POINTS_ID.ADDRESS'               => 'START_POINT_ADDRESS',
		'START_POINTS_ID.LATITUDE'              => 'START_POINT_LATITUDE',
		'START_POINTS_ID.LONGITUDE'             => 'START_POINT_LONGITUDE',
		'START_POINTS_ID.RADIUS'                => 'START_POINT_RADIUS',
		'START_POINTS_ID.POPULAR'               => 'START_POINT_POPULAR',
		'END_START',
		'END_POINTS_ID',
		'END_POINTS_ID.NAME'                    => 'END_POINT_NAME',
		'END_POINTS_ID.POINT_TYPES_ID'          => 'END_POINT_TYPE_ID',
		'END_POINTS_ID.POINT_TYPES_ID.NAME'     => 'END_POINT_TYPE_NAME',
		'END_POINTS_ID.POINT_TYPES_ID.CODE'     => 'END_POINT_TYPE_CODE',
		'END_POINTS_ID.ADDRESS'                 => 'END_POINT_ADDRESS',
		'END_POINTS_ID.LATITUDE'                => 'END_POINT_LATITUDE',
		'END_POINTS_ID.LONGITUDE'               => 'END_POINT_LONGITUDE',
		'END_POINTS_ID.RADIUS'                  => 'END_POINT_RADIUS',
		'END_POINTS_ID.POPULAR'                 => 'END_POINT_POPULAR',
		'ODO'
	);

	/**
	 * Функция обрабатывает данные о маршруте из формы, для сохранении в БД
	 *
	 * @api
	 *
	 * @param array $post Массив $_POST с данными
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses Odo::addNewRoute
	 *
	 * @throws Exception\ArgumentNullException Если массив POST не задан
	 *
	 * @return bool|int
	 */
	public static function addNewRouteFromPost (array $post=null)
	{
		try
		{
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException("_POST");
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arData = array();
		Fields::validateFields($post,$arData);
		if (!isset($arData['MY_CAR_ID']))
		{
			$arData['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}

		if (!isset($arData['DATE']))
		{
			$arData['DATE'] = date('d.m.Y');
		}

		if (isset($arData['POINTS_ID']))
		{
			$arData['START_POINTS_ID'] = $arData['POINTS_ID'];
			unset($arData['POINTS_ID']);
		}
		else
		{
			Errors::addError('START_POINTS_ID','Не указана начальная путевая точка');
		}

		if (!isset($arData['END_START']) || !$arData['END_START'])
		{
			if (!isset($arData['END_POINTS_ID']))
			{
				Errors::addError('END_POINTS_ID','Не указана конечная путевая точка');
			}
			$arData['END_START'] = false;
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		return static::addNewRoute($arData);
	}

	/**
	 * Возвращает максимальное значение одометра на основе маршрутов
	 *
	 * @api
	 *
	 * @param int $carID
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RoutsTable::getTableName
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

		$helper = new SqlHelper(Tables\RoutsTable::getTableName());

		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('ODO','ODO')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['ODO']);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Выводит график пройденного пути по дням для заданного временного промежутка
	 *
	 * @api
	 *
	 * @param string    $from       Начало временного промежутка
	 * @param string    $to         Окончание временного промежутка
	 * @param int|null  $carID      ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 * @param string    $xTitle     Название оси X - если не указано, будет выбрано из файла локализации
	 * @param string    $yTitle     Название оси Y - если не указано, будет выбрано из файла локализации
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCar::getCarByID
	 * @uses OdoTable::getList
	 * @uses MSergeev\Core\Lib\DateHelper
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 * @uses LineCharts Для отображения графика
	 *
	 * @return string
	 */
	public static function showChartsOdo ($from=null, $to=null, $carID=null, $xTitle='', $yTitle='')
	{
		if ($xTitle=='')
		{
			$xTitle=Loc::getPackMessage('icar','all_date');
		}
		if ($yTitle=='')
		{
			$yTitle=Loc::getPackMessage('icar','all_odo_km');
		}
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		else
		{
			$carID = intval($carID);
		}
		$arCar = MyCar::getCarByID($carID);
		if (is_null($from) || is_null($to))
		{
			$nowYear = date('Y');
			$nowMonth = date('m');
			$nowDay = date('d');

			if (intval($nowDay)>=1 && intval($nowDay) <=9)
			{
				$to = '0'.$nowDay;
			}
			else
			{
				$to = $nowDay;
			}
			$from = '01.'.$nowMonth.'.'.$nowYear;
			$to .= '.'.$nowMonth.'.'.$nowYear;
		}

		$title = Loc::getPackMessage('icar','all_data_period').': '
			.Loc::getPackMessage('icar','all_from').' '.$from.' '
			.Loc::getPackMessage('icar','all_to').' '.$to;

		$arRes = Tables\OdoTable::getList(array(
			'select' => array('DATE','ODO'),
			'filter' => array(
				'MY_CAR_ID' => $carID,
				'>=DATE' => $from,
				'<=DATE' => $to
			),
			'order' => array('DATE'=>'ASC')
		));
		if (!$arRes) {
			return Loc::getPackMessage('icar','all_period_no_data');
		}
		else
		{
			$dateHelper = new DateHelper();
			$arXAxis = array();
			foreach ($arRes as $ar_res)
			{
				list($day,$month,$year) = explode('.',$ar_res['DATE']);
				$dayOfWeek = $dateHelper->getDayOfWeekFromDate($ar_res['DATE']);
				if ($dayOfWeek === 0 || $dayOfWeek === 6)
				{
					$name = '<span style="color: red;">'.$day." ".DateHelper::getNameMonthShort(intval($month))
						." (".$dateHelper->getShortNameDayOfWeek($dayOfWeek).")</span>";
				}
				else
				{
					$name = $day." ".DateHelper::getNameMonthShort(intval($month))
						." (".$dateHelper->getShortNameDayOfWeek($dayOfWeek).")";
				}
				$arXAxis['NAME'][] = $name;
				$arXAxis['VALUE'][] = floatval($ar_res['ODO']);
			}

			$arData = array();
			$arData['title'] = 'Пробег';
			$arData['subtitle'] = $title;
			$arData['yAxis'] = $yTitle;
			$arData['valueSuffix'] = 'км.';

			$arData['xAxis'] = $arXAxis['NAME'];
			$arData['series'] = array(
				0 => array(
					'name' => $arCar['NAME'],
					'data' => $arXAxis['VALUE']
				)
			);

			return LineCharts($arData);
		}
	}

	/**
	 * Функция возвращает текущий пробег, находя максимальную запись в разных таблицах
	 *
	 * @api
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCar::getBuyCarOdo
	 * @uses Fuel::getMaxOdo
	 * @uses Odo::getMaxOdo
	 * @uses RepairParts::getMaxOdo
	 * @uses Ts::getMaxOdo
	 *
	 * @return float
	 */
	public static function getCurrentOdo ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$mileage = MyCar::getBuyCarOdo($carID);

		//Максимальный пробег в записях о заправках
		$res = Fuel::getMaxOdo($carID);
		if ($res>$mileage)
		{
			$mileage = $res;
		}

		//Максимальный пробег в записях о маршрутах
		$res = self::getMaxOdo($carID);
		if ($res>$mileage)
		{
			$mileage = $res;
		}

		//Максимальный пробег в записях о запчастях
		$res = RepairParts::getMaxOdo($carID);
		if ($res>$mileage)
		{
			$mileage = $res;
		}

		//Максимальный пробег в записях о прохождении ТО
		$res = Ts::getMaxOdo($carID);
		if ($res>$mileage)
		{
			$mileage = $res;
		}

		return round($mileage,2);
	}

	/**
	 * Возвращает значение текущего пробега
	 *
	 * @api
	 *
	 * Не забываем, что значение пробега может отличаться от значения одометра
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Odo::getCurrentOdo
	 * @uses MyCar::getBuyCarOdo
	 *
	 * @return float
	 */
	public static function getCurrentMileage ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$odo = self::getCurrentOdo($carID);
		$mileage = MyCar::getBuyCarOdo($carID);

		return round(($odo-$mileage),2);
	}

	/**
	 * Возвращает средний пробег в день
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Odo::getCurrentMileage
	 * @uses MyCar::getOwnershipDays
	 *
	 * @return float
	 */
	public static function getAverageMileageDay ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$mileage = self::getCurrentMileage($carID);
		$days = MyCar::getOwnershipDays($carID);

		if (intval($days)>0)
		{
			return round(($mileage/intval($days)),2);
		}

		return floatval(0);
	}

	/**
	 * Возвращает средний пробег в месяц
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Odo::getCurrentMileage
	 * @uses MyCar::getOwnershipDays
	 *
	 * @return float
	 */
	public static function getAverageMileageMonth ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$mileage = self::getCurrentMileage($carID);
		$days = MyCar::getOwnershipDays($carID);
		if (intval($days)>0)
		{
			$month = $days / 30;
			if (intval($month)>0)
			{
				return round(($mileage/$month),2);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает список маршрутов
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 * @param int       $limit  Лимит записей
	 * @param int       $offset Смещение
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RoutsTable::getList
	 *
	 * @return array|bool
	 */
	public static function getListRouts ($carID=null, $limit=0, $offset=0)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		$arParams = array(
			'select' => self::$arRoutsFields,
			'filter' => array('MY_CAR_ID'=>$carID),
			'order'  => array('DATE'=>'DESC')
		);
		if (intval($limit) > 0)
		{
			$arParams['limit'] = intval($limit);
			if (intval($offset > 0))
			{
				$arParams['offset'] = intval($offset);
			}
		}
		$arRes = Tables\RoutsTable::getList($arParams);
		if ($arRes && intval($arParams['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Функция добавляет новый машрут и возвращает ID записи, либо false
	 *
	 * @param array $arData Массив с даннымы нового маршрута
	 *
	 * @uses Points::increasePointPopular
	 * @uses Odo::updateDayOdometer
	 * @uses RoutsTable::getTableName
	 * @uses RoutsTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 * @uses MSergeev\Core\Lib\Options::setOption
	 *
	 * @throws Exception\ArgumentNullException Если массив данных не передан
	 *
	 * @return bool|int
	 */
	protected static function addNewRoute($arData=array())
	{
		try
		{
			if (empty($arData))
			{
				throw new Exception\ArgumentNullException('$arData');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if (isset($arData['end_point_num']))
		{
			$bEndPoint = true;
			unset($arData['end_point_num']);
		}
		else
		{
			$bEndPoint = false;
		}

		if ($arEvents = Events::getPackageEvents('icar','OnBeforeAddNewRoute'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					Events::executePackageEvent($arEvent,array(&$arData));
				}
			}
		}

		$query = new Query('insert');
		$query->setInsertParams(
			array(0=>$arData),
			Tables\RoutsTable::getTableName(),
			Tables\RoutsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = Events::getPackageEvents('icar','OnAfterAddNewRoute'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						Events::executePackageEvent($arEvent,array($arData,$res->getInsertId()));
					}
				}
			}

			if (!$arData['END_START'])
			{
				Options::setOption('icar_'.$arData['MY_CAR_ID'].'_last_point',$arData['END_POINTS_ID']);
			}
			else
			{
				Options::setOption('icar_'.$arData['MY_CAR_ID'].'_last_point',$arData['START_POINTS_ID']);
			}
			if ($bEndPoint)
			{
				Points::increasePointPopular($arData['END_POINTS_ID']);
			}
			static::updateDayOdometer($arData['MY_CAR_ID'],$arData['DATE']);

			return $res->getInsertId();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Функция высчитывает данные о пройденном расстоянии от выбранной даты или за все время
	 *
	 * @param int|null  $carID  ID автомобиля, если null - будет выбрал автомобиль по-молчанию
	 * @param string    $date   Дата, с которой необходимо начинать пересчет
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses MyCar::getBuyCarOdo
	 * @uses RoutsTable::getList
	 * @uses OdoTable::getList
	 * @uses OdoTable::getTableName
	 * @uses OdoTable::getMapArray
	 * @uses MSergeev\Core\Lib\DateHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses Msergeev\Core\Entity\DBResult
	 *
	 * @return void
	 */
	protected static function updateDayOdometer($carID=null,$date=null)
	{
		$dateHelper = new DateHelper();
		$arResult = array();
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if (is_null($date))
		{
			//TODO: Проверить работу кода
			$arRes = Tables\RoutsTable::getList(array(
				'select' => array('ID','DATE','ODO'),
				'filter' => array(
					'MY_CAR_ID' => $carID,
					'>ODO' => 0
				),
				'order' => array(
					'DATE' => 'ASC',
					'ID' => 'ASC'
				)
			));
			$arResult['BUY_ODO'] = MyCar::getBuyCarOdo($carID);
			$arResult['MAX_DATE_ODO'] = array();
			$bFirst = true;
			foreach ($arRes as $ar_res)
			{
				if ($bFirst)
				{
					$bFirst = false;
					$arResult['FIRST_DAY'] = $ar_res['DATE'];
				}
				$arResult['ROUTS'][$ar_res['ID']]['DATE'] = $ar_res['DATE'];
				$arResult['ROUTS'][$ar_res['ID']]['ODO'] = $ar_res['ODO'];
				if (
					!isset($arResult['MAX_DATE_ODO'][$ar_res['DATE']])
					|| $ar_res['ODO'] > $arResult['MAX_DATE_ODO'][$ar_res['DATE']]
				)
				{
					$arResult['MAX_DATE_ODO'][$ar_res['DATE']] = $ar_res['ODO'];
				}
			}
			$arResult['LAST_DAY'] = date('d.m.Y');
			$lastOdo = $arResult['BUY_ODO'];
			$arResult['DAY_ODO'] = array();
			foreach ($arResult['MAX_DATE_ODO'] as $day=>$odo)
			{
				$arResult['DAY_ODO'][$day] = round(($odo - $lastOdo),1);
				$lastOdo = $odo;
			}
			$arResult['ODO_ALL_DAYS'] = array();
			$now_day = $arResult['FIRST_DAY'];
			while ($now_day !== $arResult['LAST_DAY'])
			{
				if (isset($arResult['DAY_ODO'][$now_day]))
				{
					$arResult['ODO_ALL_DAYS'][$now_day] = $arResult['DAY_ODO'][$now_day];
				}
				else
				{
					$arResult['ODO_ALL_DAYS'][$now_day] = 0;
				}
				$now_day = $dateHelper->strToTime($now_day,'+1 day','site');
			}

		}
		else
		{
			$arResult['FIRST_DAY'] = $date;
			$arResult['LAST_DAY'] = date('d.m.Y');
			$arResult['BUY_ODO'] = MyCar::getBuyCarOdo($carID);

			//TODO: Проверить работу кода
			$arRes = Tables\RoutsTable::getList(array(
				'select' => array('ID','DATE','ODO'),
				'filter' => array(
					'MY_CAR_ID' => $carID,
					'>=DATE' => $date,
					'>ODO' => 0
				),
				'order' => array(
					'DATE' => 'ASC',
					'ID' => 'ASC'
				)
			));
			$arResult['MAX_DATE_ODO'] = array();
			//TODO: Исправить ошибку: Warning: Invalid argument supplied for foreach() (при указании пробега = 0, если это первая поездка за день)
			foreach ($arRes as $ar_res)
			{
				$arResult['ROUTS'][$ar_res['ID']]['DATE'] = $ar_res['DATE'];
				$arResult['ROUTS'][$ar_res['ID']]['ODO'] = $ar_res['ODO'];
				if (
					!isset($arResult['MAX_DATE_ODO'][$ar_res['DATE']])
					|| $ar_res['ODO'] > $arResult['MAX_DATE_ODO'][$ar_res['DATE']]
				)
				{
					$arResult['MAX_DATE_ODO'][$ar_res['DATE']] = $ar_res['ODO'];
				}
			}

			//TODO: Проверить работу кода
			$arRes2 = Tables\RoutsTable::getList(array(
				'select' => array('ID','DATE','ODO'),
				'filter' => array(
					'MY_CAR_ID' => $carID,
					'<DATE' => $date,
					'>ODO' => 0
				),
				'order' => array(
					'DATE' => 'DESC',
					'ID' => 'DESC'
				)
			));
			if ($arRes2)
			{
				$lastOdo = $arRes2[0]['ODO'];
				$arResult['LAST_RES'] = $arRes2[0];
			}
			else
			{
				$lastOdo = $arResult['BUY_ODO'];
			}

			$arResult['LAST_ODO'] = $lastOdo;

			$arResult['DAY_ODO'] = array();
			foreach ($arResult['MAX_DATE_ODO'] as $day=>$odo)
			{
				$arResult['DAY_ODO'][$day] = round(($odo - $lastOdo),1);
				$lastOdo = $odo;
			}

			$arResult['ODO_ALL_DAYS'] = array();
			$now_day = $arResult['FIRST_DAY'];
			while ($now_day !== $arResult['LAST_DAY'])
			{
				if (isset($arResult['DAY_ODO'][$now_day]))
				{
					$arResult['ODO_ALL_DAYS'][$now_day] = $arResult['DAY_ODO'][$now_day];
				}
				else
				{
					$arResult['ODO_ALL_DAYS'][$now_day] = 0;
				}
				$now_day = $dateHelper->strToTime($now_day,'+1 day','site');
			}
		}

		$arRes2 = Tables\OdoTable::getList(array(
			'select' => array('ID','DATE','ODO'),
			'filter' => array(
				'MY_CAR_ID' => $carID,
				'>=DATE' => $arResult['FIRST_DAY']
			),
			'order' => array('DATE'=>'ASC')
		));
		$arResult['ODO_TABLE'] = array();
		if ($arRes2){
			foreach ($arRes2 as $ar_res)
			{
				$arResult['ODO_TABLE'][$ar_res['DATE']] = array(
					'ID' => $ar_res['ID'],
					'ODO' => $ar_res['ODO']
				);
			}
		}

		$arResult['UPDATED'] = $arResult['INSERTED'] = array();
		foreach ($arResult['ODO_ALL_DAYS'] as $day=>$odo)
		{
			if (isset($arResult['ODO_TABLE'][$day]))
			{
				if ($odo != $arResult['ODO_TABLE'][$day]['ODO'])
				{
					$query = new Query('update');
					$query->setUpdateParams(
						array('ODO' => $odo),
						$arResult['ODO_TABLE'][$day]['ID'],
						Tables\OdoTable::getTableName(),
						Tables\OdoTable::getMapArray()
					);
					$res = $query->exec();
					$arResult['UPDATED'][$day] = $res->getResult();
				}
			}
			else
			{
				$query = new Query('insert');
				$arInsert[0] = array(
					'MY_CAR_ID' => $carID,
					'DATE' => $day,
					'ODO' => $odo
				);
				$query->setInsertParams(
					$arInsert,
					Tables\OdoTable::getTableName(),
					Tables\OdoTable::getMapArray()
				);
				$res = $query->exec();
				$arResult['INSERTED'][$day] = $res->getInsertId();
			}
		}

	}

	//TODO: Добавить функцию обновления маршрута
	//TODO: Добавить функцию удаления маршрута
	//TODO: Добавить функцию вывода таблицы со списком маршрутов
	//TODO: Добавить функцию получения списка маршрутов
}