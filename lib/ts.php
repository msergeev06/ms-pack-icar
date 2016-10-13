<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Date;
use MSergeev\Core\Entity\Query;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;

class Ts
{
	protected static $maxTsSelect = 25;
	public static function getTotalMaintenanceCosts ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		$helper = new SqlHelper();
		$query = new Query('select');
		$sql = "SELECT\n\t".$helper->getSumFunction('COST','SUM')."\nFROM\n\t"
			.$helper->wrapQuotes(Tables\TsTable::getTableName())."\nWHERE\n\t"
			.$helper->wrapQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['SUM']);
		}
		else
		{
			return 0;
		}
	}

	public static function getTotalMaintenanceCostsFormatted($carID=null)
	{
		return Main::moneyFormat(static::getTotalMaintenanceCosts($carID));
	}

	public static function getTsList($carID=null,$getID=null,$limit=0,$offset=0)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		$arFilter = array();
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
				'TS_NUM',
				'MY_CAR_ID',
				'MY_CAR_ID.NAME' => 'MY_CAR_NAME',
				'MY_CAR_ID.CAR_NUMBER' => 'MY_CAR_NUMBER',
				'DATE',
				'EXECUTORS_ID',
				'EXECUTORS_ID.NAME' => "EXECUTORS_NAME",
				'EXECUTORS_ID.CODE' => "EXECUTORS_CODE",
				'COST',
				'ODO',
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

		//msDebug($arList);

		$arRes = Tables\TsTable::getList($arList);

		return $arRes;
	}

	public static function showSelectTsList ($carID, $strBoxName, $strDetText='Не выбрано', $strSelectedVal = "null", $field1="class=\"tslistselect\"")
	{
		$arRes = self::getTsList($carID);
		if ($arRes)
		{
			$arValue = array();
			foreach ($arRes as $ar_res)
			{
				$arValue[] = array(
					'NAME' => $ar_res['DATE'].' ТО-'.$ar_res['TS_NUM'],
					'VALUE' => $ar_res['ID']
				);
			}

			return SelectBox($strBoxName, $arValue, $strDetText, $strSelectedVal, $field1);
		}

	}

	public static function showListTable ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arList = static::getTsList($carID);
		if ($arList)
		{
			echo '<div id="tsList"></div><div id="tsPager"></div>';

			$dateHelper = new CoreLib\DateHelper();
			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('icar')."images/");
			//msDebug($arList);

			$arDatas = array();
			foreach ($arList as $list)
			{
				$arDatas[] = array(
					'id' => $list['ID'],
					'ts' => "ТО-".$list['TS_NUM'],
					'date' => $list['DATE'],
					'timestamp' => "=".$dateHelper->getDateTimestamp($list['DATE']),
					'odo' => "=".$list['ODO'],
					'cost' => "=".$list['COST'],
					'executors_name' => $list['EXECUTORS_NAME'],
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
				'grid' => 'tsGrid',
				'container' => 'tsList',
				'footer' => true,
				'tooltip' => true,
				'pager' => array('container'=>'tsPager'),
				'columns' => array(
					$webixHelper->getColumnArray('DATE',array(
						'footer'=>'={text:"Итого:", colspan:3}'
					)),
					$webixHelper->getColumnArray('TS'),
					$webixHelper->getColumnArray('ODO'),
					$webixHelper->getColumnArray('EXECUTORS'),
					$webixHelper->getColumnArray('COST', array(
							'footer'=>'={ content:"summColumn" }'
						)
					),
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
			echo 'Нет данных о прохождении ТО';
			return false;
		}
	}

	public static function showSelectTsNum ($selectName, $selected = "null")
	{
		$arValues = array();
		for ($i=0; $i<=static::$maxTsSelect; $i++)
		{
			$arValues[] = array(
				'NAME' => 'ТО-'.$i,
				'VALUE' => $i
			);
		}
		return SelectBox($selectName,$arValues,"",$selected);
	}

	public static function showSelectExecutor ($selectName, $selected = "null")
	{
		$arValues = Tables\ExecutorTable::getList(array(
			'select' => array(
				"NAME" => "NAME",
				"ID" => "VALUE"
			),
			'order' => array(
				"SORT" => "ASC",
				"NAME" => "ASC"
			)
		));
		return SelectBox ($selectName,$arValues,"",$selected);
	}

	public static function addTsFromPost ($post=null)
	{
		try
		{
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('_POST');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arAdd = array();
		if (!isset($post['my_car']) || intval($post['my_car'])<=0)
		{
			$arAdd['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}
		else
		{
			$arAdd['MY_CAR_ID'] = intval($post['my_car']);
		}
		if (!isset($post['ts_num']))
		{
			return false;
		}
		else
		{
			$arAdd['TS_NUM'] = intval($post['ts_num']);
		}
		if (!isset($post['date']) || !CoreLib\DateHelper::checkDate($post['date']))
		{
			return false;
		}
		else
		{
			if (!$arAdd['DATE'] = CoreLib\DateHelper::validateDate($post['date']))
			{
				return false;
			}
		}
		if (!isset($post['executor']) || intval($post['executor'])<=0)
		{
			return false;
		}
		else
		{
			$arAdd['EXECUTORS_ID'] = intval($post['executor']);
		}
		if (!isset($post['cost']))
		{
			return false;
		}
		else
		{
			$post['cost'] = str_replace(" ","",$post['cost']);
			$post['cost'] = str_replace(",",".",$post['cost']);
			$post['cost'] = floatval($post['cost']);
			$arAdd['COST'] = $post['cost'];
		}
		if (!isset($post['odo']))
		{
			return false;
		}
		else
		{
			$post['odo'] = str_replace(" ","",$post['odo']);
			$post['odo'] = str_replace(",",".",$post['odo']);
			$post['odo'] = floatval($post['odo']);
			$arAdd['ODO'] = $post['odo'];
		}
		if (isset($post['ts_point']) && intval($post['ts_point'])>0)
		{
			$arAdd['POINTS_ID'] = intval($post['ts_point']);
		}
		else
		{
			if (isset($post['newpoint_address']) || (isset($post['newpoint_lat']) && isset($post['newpoint_lon'])))
			{
				$arPoint = array();
				if (isset($post['newpoint_name']) && strlen($post['newpoint_name'])>3)
				{
					$arPoint['NAME'] = $post['newpoint_name'];
				}
				else
				{
					$arPoint['NAME'] = '[auto] Сервис';
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
				$arPoint['TYPE'] = Points::getPointTypeIdByCode('service');
				$arAdd['POINTS_ID'] = Points::createNewPoint($arPoint);
			}
			else
			{
				return false;
			}
		}
		if (isset($post['comment']) && strlen($post['comment'])>0)
		{
			$arAdd['DESCRIPTION'] = trim(htmlspecialchars($post['comment']));
		}

		if ($addTsID = static::addTs($arAdd))
		{
			CoreLib\Options::setOption('icar_last_ts_'.intval($arAdd['MY_CAR_ID']),$arAdd['TS_NUM']);
			CoreLib\Options::setOption('icar_last_executor_'.intval($arAdd['MY_CAR_ID']),$arAdd['EXECUTORS_ID']);
			CoreLib\Options::setOption('icar_last_executor_'.intval($arAdd['MY_CAR_ID']).'_point',$arAdd['POINTS_ID']);
			return $addTsID;
		}
		else
		{
			return false;
		}
	}

	protected static function addTs ($arAdd=null)
	{
		try
		{
			if (is_null($arAdd))
			{
				throw new Exception\ArgumentNullException('arAdd');
			}
			elseif (!is_array($arAdd))
			{
				throw new Exception\ArgumentTypeException('arAdd','array');
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
		//msDebug($arAdd);

		$query = new Query('insert');
		$query->setInsertParams(
			$arAdd,
			Tables\TsTable::getTableName(),
			Tables\TsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			return $res->getInsertId();
		}
		else
		{
			return false;
		}
	}

	public static function updateTsFromPost ($tsID=null, $post=null)
	{
		try
		{
			if (is_null($tsID))
			{
				throw new Exception\ArgumentNullException('tsID');
			}
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('_POST');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		//msDebug($post);

		$arUpdate = array();
		if (isset($post['my_car']) || intval($post['my_car'])<=0)
		{
			$arUpdate['MY_CAR_ID'] = intval($post['my_car']);
		}
		if (isset($post['ts_num']))
		{
			$arUpdate['TS_NUM'] = intval($post['ts_num']);
		}
		if (isset($post['date']) && CoreLib\DateHelper::checkDate($post['date']))
		{
			$arUpdate['DATE'] = CoreLib\DateHelper::validateDate($post['date']);
		}
		if (isset($post['executor']) && intval($post['executor'])<=0)
		{
			$arUpdate['EXECUTORS_ID'] = intval($post['executor']);
		}
		if (isset($post['cost']))
		{
			$post['cost'] = str_replace(" ","",$post['cost']);
			$post['cost'] = str_replace(",",".",$post['cost']);
			$post['cost'] = floatval($post['cost']);
			$arUpdate['COST'] = $post['cost'];
		}
		if (isset($post['odo']))
		{
			$post['odo'] = str_replace(" ","",$post['odo']);
			$post['odo'] = str_replace(",",".",$post['odo']);
			$post['odo'] = floatval($post['odo']);
			$arUpdate['ODO'] = $post['odo'];
		}
		if (isset($post['ts_point']) && intval($post['ts_point'])>0)
		{
			$arUpdate['POINTS_ID'] = intval($post['ts_point']);
		}
		else
		{
			if (isset($post['newpoint_address']) || (isset($post['newpoint_lat']) && isset($post['newpoint_lon'])))
			{
				$arPoint = array();
				if (isset($post['newpoint_name']) && strlen($post['newpoint_name'])>3)
				{
					$arPoint['NAME'] = $post['newpoint_name'];
				}
				else
				{
					$arPoint['NAME'] = '[auto] Сервис';
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
				$arPoint['TYPE'] = Points::getPointTypeIdByCode('service');
				$arUpdate['POINTS_ID'] = Points::createNewPoint($arPoint);
			}
		}
		if (isset($post['comment']) && strlen($post['comment'])>0)
		{
			$arUpdate['DESCRIPTION'] = trim(htmlspecialchars($post['comment']));
		}

		if ($res = static::updateTs($tsID, $arUpdate))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	protected static function updateTs ($tsID=null, $arUpdate=null)
	{
		try
		{
			if (is_null($tsID))
			{
				throw new Exception\ArgumentNullException('tsID');
			}
			if (is_null($arUpdate))
			{
				throw new Exception\ArgumentNullException('arUpdate');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arCompare = Tables\TsTable::getList(array(
			'filter' => array('ID'=>$tsID)
		));
		$arCompare = $arCompare[0];
		$date = new Date($arCompare['DATE'],'db');
		$arCompare['DATE'] = $date->getDate("d.m.Y");
		foreach ($arUpdate as $key=>$update)
		{
			if ($arCompare[$key] == $arUpdate[$key])
			{
				unset($arUpdate[$key]);
			}
		}

		if (empty($arUpdate))
		{
			return true;
		}
		else
		{
			$query = new Query('update');
			$query->setUpdateParams(
				$arUpdate,
				intval($tsID),
				Tables\TsTable::getTableName(),
				Tables\TsTable::getMapArray()
			);
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
	}


	//TODO: Потестить удаление при связанных записях
	public static function deleteTs($tsID=null)
	{
		try
		{
			if (is_null($tsID))
			{
				throw new Exception\ArgumentNullException('tsID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$query = new Query('delete');
		$query->setDeleteParams($tsID,
			null,
			Tables\TsTable::getTableName(),
			Tables\TsTable::getMapArray(),
			Tables\TsTable::getTableLinks()
		);
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

}