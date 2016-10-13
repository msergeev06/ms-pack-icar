<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Entity\Query;


class RepairParts
{
	protected static $arError=array();

	public static function showErrorList()
	{
		foreach (static::$arError as $key=>$value)
		{
			echo '<br>';
			echo '* '.$value;
		}
	}

	public static function getTotalRepairPartsCostsFormatted($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		return Main::moneyFormat(static::getTotalRepairPartsCosts($carID));
	}

	protected static function getTotalRepairPartsCosts($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper();
		$fuelTable = Tables\RepairPartsTable::getTableName();
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."SUM(".$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('NUMBER')." * "
			.$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('COST').") AS SUMM\n"
			."FROM\n\t".$sqlHelper->wrapQuotes($fuelTable)."\n"
			."WHERE\n\t".$sqlHelper->wrapQuotes($fuelTable).'.'
			.$sqlHelper->wrapQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
			return floatval($fuelCosts);
		}
		else
		{
			return 0;
		}
	}

	public static function addRepairPartsFromPost($post=null)
	{
		try
		{
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('$arPost');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die($e->showException());
		}
		//msDebug($post);

		$arAdd = array();
		if (!isset($post['my_car']) || intval($post['my_car'])<=0)
		{
			$arAdd['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}
		else
		{
			$arAdd['MY_CAR_ID'] = intval($post['my_car']);
		}
		if (!isset($post['date']) || !CoreLib\DateHelper::checkDate($post['date']))
		{
			static::$arError['DATE'] = 'Неверный формат даты';
			return false;
		}
		else
		{
			if (!$arAdd['DATE'] = CoreLib\DateHelper::validateDate($post['date']))
			{
				static::$arError['DATE'] = 'Неверный формат даты';
				return false;
			}
		}
		if (!isset($post['name']))
		{
			static::$arError['NAME'] = 'Не указано имя';
			return false;
		}
		else
		{
			$arAdd['NAME'] = htmlspecialchars(trim($post['name']));
		}
		if (!isset($post['storage']) || intval($post['storage'])<=0)
		{
			static::$arError['STORAGE_ID'] = 'Не указано место хранения';
			return false;
		}
		else
		{
			$arAdd['STORAGE_ID'] = intval($post['storage']);
		}
		if (isset($post['catalog_number']) && strlen($post['catalog_number'])>0)
		{
			$arAdd['CATALOG_NUMBER'] = htmlspecialchars(trim($post['catalog_number']));
		}
		if (!isset($post['number']) || floatval($post['number'])<=0)
		{
			$arAdd['NUMBER'] = 1;
		}
		else
		{
			$arAdd['NUMBER'] = CoreLib\Tools::validateFloatVal($post['number']);
		}
		if (!isset($post['cost']) || floatval($post['cost'])<=0)
		{
			$arAdd['COST'] = 0;
		}
		else
		{
			$arAdd['COST'] = CoreLib\Tools::validateFloatVal($post['cost']);
		}
		if (!isset($post['reason']) || intval($post['reason'])<=0)
		{
			static::$arError['REASON_REPLACEMENT_ID'] = 'Не указана причина замены';
			return false;
		}
		else
		{
			$arAdd['REASON_REPLACEMENT_ID'] = intval($post['reason']);
		}
		$reasonCode = ReasonReplacement::getCodeById($arAdd['REASON_REPLACEMENT_ID']);
		switch ($reasonCode)
		{
			case 'ts':
				$arAdd['TS_ID'] = ((intval($post['reason_ts'])>0)?intval($post['reason_ts']):0);
				break;
			case 'breakdown':
				$arAdd['REPAIR_ID'] = ((intval($post['reason_breakdown'])>0)?intval($post['reason_breakdown']):0);
				break;
			case 'tuning':
				$arAdd['REPAIR_ID'] = ((intval($post['reason_tuning'])>0)?intval($post['reason_tuning']):0);
				break;
			case 'upgrade':
				$arAdd['REPAIR_ID'] = ((intval($post['reason_upgrade'])>0)?intval($post['reason_upgrade']):0);
				break;
			case 'tire':
				break;
			case 'accident':
				$arAdd['ACCIDENT_ID'] = ((intval($post['reason_dtp'])>0)?intval($post['reason_dtp']):0);
				break;
			default:
				static::$arError['REASON_REPLACEMENT_DETAIL'] = 'Не указана дополнительная информация по причине замены';
				return false;
		}
		if (!isset($post['who_paid']) || intval($post['who_paid'])<=0)
		{
			static::$arError['WHO_PAID_ID'] = 'Не указано кто платил';
			return false;
		}
		else
		{
			$arAdd['WHO_PAID_ID'] = intval($post['who_paid']);
		}
		if (!isset($post['odo']) || floatval($post['odo'])<=0)
		{
			$arAdd['ODO'] = 0;
		}
		else
		{
			$arAdd['ODO'] = CoreLib\Tools::validateFloatVal($post['odo']);
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
				if (isset($post['newpoint_name']) && strlen($post['newpoint_name'])>0)
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
				if (!$arAdd['POINTS_ID'] = Points::createNewPoint($arPoint))
				{
					unset($arAdd['POINTS_ID']);
					static::$arError['POINTS_ADD'] = 'Ошибка добавления путевой точки';
					return false;
				}
			}
			else
			{
				static::$arError['POINTS_ID'] = 'Не указана путевая точка';
				return false;
			}
		}
		if (isset($post['comment']) && strlen($post['comment'])>0)
		{
			$arAdd['DESCRIPTION'] = trim(htmlspecialchars($post['comment']));
		}

		return static::addRepairParts($arAdd);
	}

	public static function showListTable ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arList = static::getRepairPartsList($carID);

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
					'date' => $list['DATE'],
					'timestamp' => "=".$dateHelper->getDateTimestamp($list['DATE']),
					'name' => $list['NAME'],
					'catalog_num' => $list['CATALOG_NUMBER'],
					'cost' => "=".$list['COST'],
					'number' => "=".$list['NUMBER'],
					'sum' => "=".($list['COST']*$list['NUMBER']),
					'odo' => "=".$list['ODO'],
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
					$webixHelper->getColumnArray('NAME'),
					$webixHelper->getColumnArray('CATALOG_NUMBER'),
					$webixHelper->getColumnArray('SUM', array(
							'footer'=>'={ content:"summColumn" }'
						)
					),
					$webixHelper->getColumnArray('COST'),
					$webixHelper->getColumnArray('NUMBER'),
					$webixHelper->getColumnArray('ODO'),
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
			echo 'Нет записей о приобретенных запчастях';
			return false;
		}
	}

	protected static function getRepairPartsList ($carID=null,$getID=null,$limit=0,$offset=0)
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
				'MY_CAR_ID',
				'DATE',
				'NAME',
				'STORAGE_ID',
				'CATALOG_NUMBER',
				'NUMBER',
				'COST',
				'REASON_REPLACEMENT_ID',
				'TS_ID',
				'ACCIDENT_ID',
				'REPAIR_ID',
				'WHO_PAID_ID',
				'ODO',
				'POINTS_ID',
				/*'POINTS_ID.NAME' => 'POINT_NAME',
				'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
				'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
				'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
				'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',*/
				'DESCRIPTION' => 'INFO'
			),
			'filter' => $arFilter,
			'order' => array('DATE'=>'ASC','ID'=>'ASC')
		);
		if ($limit > 0)
		{
			$arList['limit'] = $limit;
		}
		if ($offset > 0)
		{
			$arList['offset'] = $offset;
		}


		$arRes = Tables\RepairPartsTable::getList($arList);
		if ($arRes)
		{
			foreach ($arRes as $key=>&$ar_res)
			{
				if ($ar_res['MY_CAR_ID']>0)
				{
					$arRes2 = Tables\MyCarTable::getList(array(
						'select' => array(
							'NAME' => 'MY_CAR_NAME',
							'CAR_NUMBER' => 'MY_CAR_NUMBER'
						),
						'filter' => array('ID'=>$ar_res['MY_CAR_ID']),
						'limit' => 1
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['STORAGE_ID']>0)
				{
					$arRes2 = Tables\StorageTable::getList(array(
						'select' => array(
							'NAME' => 'STORAGE_NAME',
							'CODE' => 'STORAGE_CODE'
						),
						'filter' => array('ID'=>$ar_res['STORAGE_ID']),
						'limit' => 1
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['REASON_REPLACEMENT_ID']>0)
				{
					$arRes2 = Tables\ReasonReplacementTable::getList(array(
						'select' => array(
							'NAME' => 'REASON_REPLACEMENT_NAME',
							'CODE' => 'REASON_REPLACEMENT_CODE'
						)
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['TS_ID']>0)
				{
					$arRes2 = Tables\TsTable::getList(array(
						'select' => (array('TS_NUM')),
						'filter' => array('ID'=>$ar_res['TS_ID']),
						'limit' => 1
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['ACCIDENT_ID']>0)
				{
					$arRes2 = Tables\AccidentTable::getList(array(
						'select' => array('DESCRIPTION'=>'ACCIDENT_DESCRIPTION'),
						'filter' => array('ID'=>$ar_res['ACCIDENT_ID']),
						'limit' => 1
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['REPAIR_ID']>0)
				{
					$arRes2 = Tables\RepairTable::getList(array(
						'select' => array('NAME'=>'REPAIR_NAME'),
						'filter' => array('ID'=>$ar_res['REPAIR_ID']),
						'limit' => 1
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['WHO_PAID_ID']>0)
				{
					$arRes2 = Tables\WhoPaidTable::getList(array(
						'select' => array(
							'NAME' => 'WHO_PAID_NAME',
							'CODE' => 'WHO_PAID_CODE'
						)
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}

				if ($ar_res['POINTS_ID']>0)
				{
					$arRes2 = Tables\PointsTable::getList(array(
						'select' => array(
							'NAME' => 'POINT_NAME',
							'LATITUDE' => 'POINT_LATITUDE',
							'LONGITUDE' => 'POINT_LONGITUDE',
							'POINT_TYPES_ID' => 'POINT_TYPE_ID',
							'POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME'
						),
						'filter' => array('ID'=>$ar_res['POINTS_ID']),
						'limit' => 1
					));
					if ($arRes2)
					{
						$arRes2 = $arRes2[0];
						if (isset($arRes2['ID']))
						{
							unset($arRes2['ID']);
						}
						$ar_res = array_merge($ar_res,$arRes2);
					}
				}
			}
			unset($ar_res);
		}
		//msDebug($arRes);
		

		return $arRes;
	}

	protected static function addRepairParts($arAdd=null)
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
			Tables\RepairPartsTable::getTableName(),
			Tables\RepairPartsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			return $res->getInsertId();
		}
		else
		{
			static::$arError['ADD'] = 'Ошибка добавления данных';
			return false;
		}
	}
}