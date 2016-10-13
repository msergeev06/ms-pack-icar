<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;

class Fuel
{
	//protected static $bRecalculateExpence = false;

	/**
	 * Возвращает сумму расходов на топливо
	 *
	 * @param int   $carID
	 *
	 * @return int
	 */
	public static function getTotalFuelCosts ($carID=null)
	{
		$fuelCosts = 0;
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper();
		$fuelTable = Tables\FuelTable::getTableName();
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."SUM(".$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('SUM').") AS SUMM\n"
			."FROM\n\t".$sqlHelper->wrapQuotes($fuelTable)."\n"
			."WHERE\n\t".$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
		}


		return $fuelCosts;
	}

	/**
	 * Возвращает отформатированную сумму расходов на топливо
	 *
	 * @param int $carID
	 *
	 * @return string
	 */
	public static function getTotalFuelCostsFormatted($carID=null)
	{
		return Main::moneyFormat(static::getTotalFuelCosts($carID));
	}

	/**
	 * Возвращает средний расход топлива
	 *
	 * @param int $carID
	 *
	 * @return float
	 */
	public static function getAverageFuelConsumption($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$averageFuel = 0;

		$arRes = Tables\FuelTable::getList(array(
			'select' => array('EXPENCE'),
			'filter' => array(
				'MY_CAR_ID' => $carID,
				'>EXPENCE' => 0
			)
		));
		if ($arRes)
		{
			$count = count($arRes);
			$sum = 0;
			foreach ($arRes as $ar_res)
			{
				$sum += $ar_res['EXPENCE'];
			}
			$averageFuel = $sum / $count;
		}

		return $averageFuel;
	}

	/**
	 * Возвращает количество израсходованного топлива
	 *
	 * @param int   $carID
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

		$sqlHelper = new CoreLib\SqlHelper();
		$fuelTable = Tables\FuelTable::getTableName();
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."SUM(".$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('LITER').") AS SUMM\n"
			."FROM\n\t".$sqlHelper->wrapQuotes($fuelTable)."\n"
			."WHERE\n\t".$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$total = $ar_res['SUMM'];
		}

		return $total;
	}

	/**
	 * Подготавливает данные из формы для добавления в БД
	 *
	 * @param array $post
	 *
	 * @return bool
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
		if (!isset($post['my_car']))
		{
			$arData['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}
		else
		{
			$arData['MY_CAR_ID'] = intval($post['my_car']);
		}
		if (isset($post['date']))
		{
			$arData['DATE'] = $post['date'];
		}
		if (isset($post['odo']))
		{
			if (floatval($post['odo'])==0)
			{
				$arData['ODO'] = MyCar::getBuyCarOdo($arData['MY_CAR_ID']);
			}
			else
			{
				$arData['ODO'] = floatval($post['odo']);
			}
		}
		if (isset($post['fuel_mark']))
		{
			$arData['FUELMARK_ID'] = intval($post['fuel_mark']);
		}
		if (isset($post['liters']))
		{
			$arData['LITER'] = floatval($post['liters']);
		}
		if (isset($post['cost_liter']))
		{
			$arData['LITER_COST'] = floatval($post['cost_liter']);
		}
		if (isset($arData['LITER']) && isset($arData['LITER_COST']))
		{
			$arData['SUM'] = floatval($arData['LITER_COST'] * $arData['LITER']);
		}
		if (isset($post['full_tank']) && intval($post['full_tank']) == 1)
		{
			$arData['FULL'] = true;
		}
		else
		{
			$arData['FULL'] = false;
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
		if (isset($post['comment']))
		{
			$arData['DESCRIPTION'] = trim($post['comment']);
		}
		if (isset($post['fuel_point']) && intval($post['fuel_point'])>0)
		{
			$arData['POINTS_ID'] = intval($post['fuel_point']);
		}
		else
		{
			$arPoint = array();
			if (isset($post['newpoint_name']) && strlen($post['newpoint_name'])>3)
			{
				$arPoint['NAME'] = $post['newpoint_name'];
			}
			else
			{
				$arPoint['NAME'] = '[auto] АЗС';
			}
			if (isset($post['newpoint_address']) && strlen($post['newpoint_address'])>5)
			{
				$arPoint['ADDRESS'] = $post['newpoint_address'];
			}
			if (
				(isset($post['newpoint_lat']) && strlen($post['newpoint_lat'])>2)
				&& (isset($post['newpoint_lon']) && strlen($post['newpoint_lon'])>2)
			)
			{
				$arPoint['LON'] = $post['newpoint_lon'];
				$arPoint['LAT'] = $post['newpoint_lat'];
			}
			$arPoint['TYPE'] = Points::getPointTypeIdByCode('fuel');
			$arData['POINTS_ID'] = Points::createNewPoint($arPoint);
		}

		return static::addFuel($arData);
	}

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

		$arFuel = static::getFuelList(null,intval($post['id']));
		$arFuel = $arFuel[0];

		$arUpdate = array();
		if (isset($post['my_car']) && intval($post['my_car'])!=intval($arFuel['MY_CAR_ID']))
		{
			$arUpdate['MY_CAR_ID'] = intval($post['my_car']);
		}
		if (isset($post['date']) && $post['date'] != $arFuel['DATE'])
		{
			$arUpdate['DATE'] = $post['date'];
		}
		if (isset($post['odo']))
		{
			if (floatval($post['odo'])==0)
			{
				$odo = MyCar::getBuyCarOdo($arUpdate['MY_CAR_ID']);
			}
			else
			{
				$odo = floatval($post['odo']);
			}

			if ($odo != $arFuel['ODO'])
			{
				$arUpdate['ODO'] = $odo;
			}
		}
		if (isset($post['fuel_mark']) && intval($post['fuel_mark']) != intval($arFuel['FUELMARK_ID']))
		{
			$arUpdate['FUELMARK_ID'] = intval($post['fuel_mark']);
		}
		if (isset($post['liters']) && floatval($post['liters']) != floatval($arFuel['LITER']))
		{
			$arUpdate['LITER'] = floatval($post['liters']);
		}
		if (isset($post['cost_liter']) && floatval($post['cost_liter']) != floatval($arFuel['LITER_COST']))
		{
			$arUpdate['LITER_COST'] = floatval($post['cost_liter']);
		}
		$sum = floatval($arFuel['LITER'] * $arFuel['LITER_COST']);
		if ($sum != $arFuel['SUM'])
		{
			$arUpdate['SUM'] = $sum;
		}
		if (isset($post['full_tank']) && intval($post['full_tank']) == 1)
		{
			$full = true;
		}
		else
		{
			$full = false;
		}
		if ($full !== $arFuel['FULL'])
		{
			$arUpdate['FULL'] = $full;
		}
/*		$expence = static::recalculateExpence($arFuel);
		if ($expence != $arFuel['EXPENCE'])
		{
			$arUpdate['EXPENCE'] = $expence;
		}*/
		if (isset($post['comment']) && $post['comment'] != $arFuel['INFO'])
		{
			$arUpdate['DESCRIPTION'] = trim($post['comment']);
		}
		if (
			isset($post['fuel_point'])
			&& intval($post['fuel_point'])>0
			&& intval($post['fuel_point']) != intval($arFuel['POINTS_ID'])
		)
		{
			$arUpdate['POINTS_ID'] = intval($post['fuel_point']);
		}
		elseif ($post['fuel_point']=='NULL')
		{
			$arPoint = array();
			if (isset($post['newpoint_name']) && strlen($post['newpoint_name'])>3)
			{
				$arPoint['NAME'] = $post['newpoint_name'];
			}
			else
			{
				$arPoint['NAME'] = '[auto] АЗС';
			}
			if (isset($post['newpoint_address']) && strlen($post['newpoint_address'])>5)
			{
				$arPoint['ADDRESS'] = $post['newpoint_address'];
			}
			if (
				(isset($post['newpoint_lat']) && strlen($post['newpoint_lat'])>2)
				&& (isset($post['newpoint_lon']) && strlen($post['newpoint_lon'])>2)
			)
			{
				$arPoint['LON'] = $post['newpoint_lon'];
				$arPoint['LAT'] = $post['newpoint_lat'];
			}
			$arPoint['TYPE'] = Points::getPointTypeIdByCode('fuel');
			$arUpdate['POINTS_ID'] = Points::createNewPoint($arPoint);
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
				static::recalculateExpence($arFuel);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
	}

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

		$query = new Query('delete');
		$query->setDeleteParams($fuelID,true,Tables\FuelTable::getTableName(),Tables\FuelTable::getMapArray(),Tables\FuelTable::getTableLinks());
		$res = $query->exec();
		if ($res->getResult())
		{
			//static::recalculateExpence();
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 * Возвращает <select> с марками топлива
	 *
	 * @param string $strBoxName
	 * @param string $strSelectedVal
	 * @param string $field1
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

			return SelectBox ($strBoxName, $arValues, '--- Выбрать ---', $strSelectedVal, $field1);
		}
		else
		{
			return '[Нет марок топлива]';
		}
	}

	public static function getFuelList ($carID=null,$getID=null,$limit=0,$offset=0)
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
			'select' => array(
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
				'EXPENCE',
				'POINTS_ID',
				'POINTS_ID.NAME' => 'POINT_NAME',
				'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
				'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
				'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
				'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
				'DESCRIPTION' => 'INFO'
			),
			'filter' => $arFilter,
			'order' => array(
				'DATE' => 'ASC',
				'ID' => 'ASC'
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



/*	public static function getFuelNumRows ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper();
		$sql = "SELECT\n\t".$helper->wrapQuotes('ID')."\nFROM\n\t"
			.$helper->wrapQuotes(Tables\FuelTable::getTableName())."\nWHERE\n\t"
			.$helper->wrapQuotes('MY_CAR_ID')." = ".$carID;
		$query = new Query('select');
		$query->setQueryBuildParts($sql);
		$res = $query->exec();

		return $res->getNumRows();
	}*/

	public static function showListTable ($carID = null, $div = null, $first=false)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arList = static::getFuelList($carID);
		if ($arList)
		{
			echo '<div id="fuelList"></div><div id="fuelPager"></div>';

			$dateHelper = new CoreLib\DateHelper();
			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('icar')."images/");

			//msDebug($arList);
			$arDatas = array();
			foreach ($arList as $list)
			{
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
					'expence' => "=".$list['EXPENCE'],
					'point_name' => $list['POINT_NAME'],
					'point_latitude' => $list['POINT_LATITUDE'],
					'point_longitude' => $list['POINT_LONGITUDE'],
					'yandex_map' => "<img src='https://static-maps.yandex.ru/1.x/?l=map&z=12&size=600,450&pt=".$list['POINT_LONGITUDE'].",".$list['POINT_LATITUDE'].",pm2blm'>",
					'point_type' => $list['POINT_TYPE_NAME'],
					'info' => (strlen($list['INFO'])>0)?"<img src='".$imgSrcPath."info.png'>":"",
					'comment' => $list['INFO'],
					'edit' => "<a href='edit.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a href='delete.php?id=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
				);
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
						'footer'=>'={text:"Итого:", colspan:3}'
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

			return CoreLib\Webix::showDataTable($arData);
		}
		else
		{
			echo 'Нет данных о заправках';
			return false;
		}
	}


	/**
	 * Возвращает массив всех марок топлива, по умолчанию выбирает только активные
	 *
	 * @param bool $bActive
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
	 * Добавляет данные о заправки в БД
	 *
	 * @param array $arData
	 *
	 * @return bool|int
	 */
	protected static function addFuel ($arData=null)
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

		$query = new Query('insert');
		$query->setInsertParams(
			$arData,
			Tables\FuelTable::getTableName(),
			Tables\FuelTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			Points::increasePointPopular($arData['POINTS_ID']);
			static::recalculateExpence($arData);
			return $res->getInsertId();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Функция пресчитывает расход топлива для всех записей, начиная с заданной
	 *
	 * @param array $arData
	 */
	protected static function recalculateExpence ($arData=null)
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

		if (!isset($arData['DATE']))
		{
			$arList = array(
				'select' => array('ID','DATE','ODO','LITER','FULL','EXPENCE'),
				'filter' => array(
					'MY_CAR_ID' => $arData['MY_CAR_ID']
				),
				'order' => array('DATE'=>'ASC','ID'=>'ASC')
			);
			$arRes = Tables\FuelTable::getList($arList);
			$bFirst = true;
			$buyOdo = MyCar::getBuyCarOdo($arData['MY_CAR_ID']);
			$lastOdo = 0;
			$sumLiter = 0;
			foreach($arRes as $ar_res)
			{
				if ($bFirst)
				{
					$bFirst = false;
					if ($ar_res['ODO']<=0)
					{
						$lastOdo = $buyOdo;
					}
				}
				if ($ar_res['ODO']<=0 || !$ar_res['FULL'])
				{
					$sumLiter += $ar_res['LITER'];
				}
				else
				{
					$mileage = $ar_res['ODO'] - $lastOdo;
					$sumLiter += $ar_res['LITER'];
					$expence = ($sumLiter*100)/$mileage;
					$expence = round($expence,2);
					if ($expence != $ar_res['EXPENCE'])
					{
						$arUpdate = array('EXPENCE' => $expence);
						$res = static::updateExpence($ar_res['ID'],$arUpdate);
						if (!$res)
						{
							//TODO: Добавить сообщения об ошибке
						}
					}
					$lastOdo = $ar_res['ODO'];
					$sumLiter = 0;
				}
			}
		}
		else
		{
			$arRes = Tables\FuelTable::getList(array(
				'select' => array('ID','DATE','ODO','LITER','FULL','EXPENCE'),
				'filter' => array(
					'MY_CAR_ID' => $arData['MY_CAR_ID'],
					'<DATE' => $arData['DATE'],
					'>EXPENCE' => 0
				),
				'order' => array('DATE'=>'DESC','ID'=>'DESC'),
				'limit' => 1
			));
			if (!$arRes)
			{
				static::recalculateExpence(array());
			}
			else
			{
				$arRes = $arRes[0];
				$lastOdo = $arRes['ODO'];
				$date = $arRes['DATE'];
				$sumLiter = 0;
				$arRes = Tables\FuelTable::getList(array(
					'select' => array('ID','DATE','ODO','LITER','FULL','EXPENCE'),
					'filter' => array(
						'MY_CAR_ID' => $arData['MY_CAR_ID'],
						'>DATE' => $date
					),
					'order' => array('DATE'=>'ASC','ID'=>'ASC')
				));
				if ($arRes)
				{
					foreach($arRes as $ar_res)
					{
						if ($ar_res['ODO']<=0 || !$ar_res['FULL'])
						{
							$sumLiter += $ar_res['LITER'];
						}
						else
						{
							$mileage = $ar_res['ODO'] - $lastOdo;
							$sumLiter += $ar_res['LITER'];
							$expence = ($sumLiter*100)/$mileage;
							$expence = round($expence,2);
							if ($expence != $ar_res['EXPENCE'])
							{
								$arUpdate = array('EXPENCE' => $expence);
								$res = static::updateExpence($ar_res['ID'],$arUpdate);
								if (!$res)
								{
									//TODO: Добавить сообщения об ошибке
								}
							}
							$lastOdo = $ar_res['ODO'];
							$sumLiter = 0;
						}
					}
				}
			}

		}

	}

	/**
	 * Обновляет значение расхода для указанной записи
	 *
	 * @param int   $primary
	 * @param array $arUpdate
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
		$query->setUpdateParams($arUpdate,$primary,Tables\FuelTable::getTableName(),Tables\FuelTable::getMapArray());
		$res = $query->exec();
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
	 * @param int   $fuelMark   ID марки топлива
	 * @param int   $carID      ID автомобиля
	 *
	 * @return bool
	 */
	protected static function setLastUseFuelMark ($fuelMark=null,$carID=null)
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
}