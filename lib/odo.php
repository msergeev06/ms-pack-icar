<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Entity\Query;

class Odo
{
	/**
	 * Функция обрабатывает данные о маршруте из формы, для сохранении в БЛ
	 *
	 * @param null $post
	 *
	 * @return bool|int
	 */
	public static function addNewRouteFromPost ($post=null)
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
		if (isset($post['my_car']))
		{
			$arData['MY_CAR_ID'] = intval($post['my_car']);
		}
		else
		{
			$arData['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}
		if (isset($post['date']))
		{
			$arData['DATE'] = $post['date'];
		}
		else
		{
			$arData['DATE'] = date('d.m.Y');
		}
		if (isset($post['odo']))
		{
			$arData['ODO'] = $post['odo'];
		}
		if (isset($post['start_point']) && intval($post['start_point'])>0)
		{
			$arData['START_POINTS_ID'] = $post['start_point'];
		}
		else
		{
			$arPoint = array();
			if (isset($post['start_name']) && strlen($post['start_name'])>3)
			{
				$arPoint['NAME'] = $post['start_name'];
			}
			if (isset($post['start_address']) && strlen($post['start_address'])>5)
			{
				$arPoint['ADDRESS'] = $post['start_address'];
			}
			if (
				(isset($post['start_lat']) && strlen($post['start_lat'])>2)
				&& (isset($post['start_lon']) && strlen($post['start_lon'])>2)
			)
			{
				$arPoint['LON'] = $post['start_lon'];
				$arPoint['LAT'] = $post['start_lat'];
			}
			$arPoint['TYPE'] = Points::getPointTypeIdByCode('waypoint');
			$arData['START_POINTS_ID'] = Points::createNewPoint($arPoint);
		}
		if (isset($post['end_start']) && intval($post['end_start'])==1)
		{
			$arData['END_START'] = true;
		}
		else
		{
			$arData['END_START'] = false;
			if (isset($post['end_point']) && intval($post['end_point'])>0)
			{
				$arData['END_POINTS_ID'] = $post['end_point'];
				$arData['end_point_num'] = true;
			}
			else
			{
				$arPoint = array();
				if (isset($post['end_name']) && strlen($post['end_name'])>3)
				{
					$arPoint['NAME'] = $post['end_name'];
				}
				if (isset($post['end_address']) && strlen($post['end_address'])>5)
				{
					$arPoint['ADDRESS'] = $post['end_address'];
				}
				if (
					(isset($post['end_lat']) && strlen($post['end_lat'])>2)
					&& (isset($post['end_lon']) && strlen($post['end_lon'])>2)
				)
				{
					$arPoint['LON'] = $post['end_lon'];
					$arPoint['LAT'] = $post['end_lat'];
				}
				$arPoint['TYPE'] = Points::getPointTypeIdByCode('waypoint');
				$arData['END_POINTS_ID'] = Points::createNewPoint($arPoint);
			}
		}

		return static::addNewRoute($arData);
	}

	/**
	 * Возвращает максимальное значение одометра на основе маршрутов
	 *
	 * @param int $carID
	 *
	 * @return int
	 */
	public static function getMaxOdo ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arRes = Tables\RoutsTable::getList(array(
			                                    'select' => array('ODO'),
			                                    'filter' => array(
				                                    'MY_CAR_ID' => intval($carID),
				                                    '>ODO' => 0
			                                    ),
			                                    'order' => array('DATE'=>'DESC'),
			                                    'limit' => 1
		                                    ));
		if ($arRes)
		{
			return $arRes[0]['ODO'];
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Выводит график пройденного пути по дням для заданного временного промежутка
	 *
	 * @param string    $from       date
	 * @param string    $to         date
	 * @param int       $carID
	 * @param string    $xTitle
	 * @param string    $yTitle
	 *
	 * @return string
	 */
	public static function showChartsOdo ($from=null, $to=null, $carID=null, $xTitle='', $yTitle='')
	{
		if ($xTitle=='')
		{
			$xTitle="Дата";
		}
		if ($yTitle=='')
		{
			$yTitle="Километраж (км.)";
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

		$title = 'Данные за период: с '.$from.' по '.$to;

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
			return 'Нет данных за указанный период';
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
					$name = '<span style="color: red;">'.$day." (".$dateHelper->getShortNameDayOfWeek($dayOfWeek).")</span>";
				}
				else
				{
					$name = $day." (".$dateHelper->getShortNameDayOfWeek($dayOfWeek).")";
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
	 * Функция добавляет новый машрут и возвращает ID записи, либо false
	 *
	 * @param array $arData
	 *
	 * @return bool|int
	 */
	protected static function addNewRoute($arData=array())
	{
		try
		{
			if (empty($arData))
			{
				throw new Exception\ArgumentNullException('arNewRote');
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

		$query = new Query('insert');
		$query->setInsertParams(
			array(0=>$arData),
			Tables\RoutsTable::getTableName(),
			Tables\RoutsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
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
	 * @param int $carID
	 * @param string $date
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

		//TODO: Проверить работу кода
		$arRes2 = Tables\OdoTable::getList(array(
			'select' => array('ID','DATE','ODO'),
			'filter' => array(
				'MY_CAR_ID' => $carID,
				'>=DATE' => $arResult['FIRST_DAY']
			),
			'order' => array('DATE'=>'ASC')
		));
		$arResult['ODO_TABLE'] = array();
		foreach ($arRes2 as $ar_res)
		{
			$arResult['ODO_TABLE'][$ar_res['DATE']] = array(
				'ID' => $ar_res['ID'],
				'ODO' => $ar_res['ODO']
			);
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

}