<?php
/**
 * MSergeev\Packages\Icar\Lib\OptionalEquip
 * Дополнительное оборудование
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Exception;
use MSergeev\Core\Entity\Query;

/**
 * Class OptionalEquip
 *
 *
 * Events:
 * OnBeforeAddNewOptionalEquip - Перед добавлением записи о дополнительном оборудовании. Передается массив полей записи
 * OnAfterAddNewOptionalEquip - После добавления записи о доп. оборудовании. Передается массив добавленных полей и ID записи
 * OnBeforeUpdateOptionalEquip - Перед изменением записи о доп. оборуд. Передается массив изменяемых полей и ID записи
 * OnAfterUpdateOptionalEquip - После изменения записи о доп. оборуд. Передается массив измененных полей и ID записи
 */
class OptionalEquip
{
	/**
	 * @var array $arTableFields Массив получаемых из таблицы полей
	 *
	 * @private
	 * @static
	 */
	private static $arTableFields = array(
		'ID',
		'MY_CAR_ID',
		'MY_CAR_ID.NAME' => 'MY_CAR_NAME',
		'MY_CAR_ID.CAR_NUMBER' => 'MY_CAR_NUMBER',
		'DATE',
		'COST',
		'NUMBER',
		'SUM',
		'ODO',
		'NAME',
		'CATALOG_NUMBER',
		'POINTS_ID',
		'POINTS_ID.NAME' => 'POINT_NAME',
		'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
		'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
		'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
		'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
		'DESCRIPTION' => 'INFO'
	);

	/**
	 * Возвращает сумму расходов на Дополнительное оборудование за все время
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquipTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCosts ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\OptionalEquipTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('SUM','SUM')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['SUM']))
			{
				return floatval($ar_res['SUM']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает сумму расходов на Дополнительное оборудование за Год
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    Флаг: true - текущий год, false - прошлый год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquipTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCostsYear ($carID=null, $now=true)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		if ($now===true)
		{
			$year = date('Y');
		}
		else
		{
			$year = date('Y')-1;
		}

		$helper = new CoreLib\SqlHelper(Tables\OptionalEquipTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('SUM','SUM')."\n"
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
			if (isset($ar_res['SUM']))
			{
				return floatval($ar_res['SUM']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает сумму расходов на Дополнительное оборудование за Месяц
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    Флаг: true - текущий месяц, false - прошлый месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquipTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCostsMonth ($carID=null, $now=true)
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

		$helper = new CoreLib\SqlHelper(Tables\OptionalEquipTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('SUM','SUM')."\n"
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
			if (isset($ar_res['SUM']))
			{
				return floatval($ar_res['SUM']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает максимальный чек (расход)
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquipTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\OptionalEquipTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('SUM','MAX')."\n"
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
	 * Возвращает минимальный чек (расход)
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquipTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\OptionalEquipTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMinFunction('SUM','MIN')."\n"
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
	 * Возвращает список расходов на дополнительное оборудование, либо указанную запись
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param null|int  $getID  ID записи, если нужно вернуть одну
	 * @param int       $limit  Лимит записей
	 * @param int       $offset Смещение записей, для постраничного вывода
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquipTable::getList
	 *
	 * @return array|bool
	 */
	public static function getList ($carID=null, $getID=null, $limit=0, $offset=0)
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
			'select' => self::$arTableFields,
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

		$arRes = Tables\OptionalEquipTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Выводит таблицу с расходами на Дополнительное оборудование
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OptionalEquip::getList
	 * @uses IcarWebixHelper
	 * @uses MSergeev\Core\Lib\Tools::getSitePath
	 * @uses MSergeev\Core\Lib\Loader::getTemplate
	 * @uses MSergeev\Core\Lib\Webix::showDataTable
	 * @uses MSergeev\Core\Lib\DateHelper
	 *
	 * @return bool|void
	 */
	public static function showListTable ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arList = self::getList($carID);
		//msDebug($arList);

		if ($arList)
		{
			echo '<div id="tsList"></div><div id="tsPager"></div>';

			$dateHelper = new CoreLib\DateHelper();
			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('icar')."images/");

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
					'sum' => "=".$list['SUM'],
					'odo' => "=".$list['ODO'],
					'point_name' => $list['POINT_NAME'],
					'point_latitude' => $list['POINT_LATITUDE'],
					'point_longitude' => $list['POINT_LONGITUDE'],
					'yandex_map' => "<img src='https://static-maps.yandex.ru/1.x/?l=map&z=12&size=600,450&pt=".$list['POINT_LONGITUDE'].",".$list['POINT_LATITUDE'].",pm2blm'>",
					'point_type' => $list['POINT_TYPE_NAME'],
					'info' => (strlen($list['INFO'])>0)?"<img src='".$imgSrcPath."info.png'>":"",
					'comment' => $list['INFO'],
					'edit' => "<a class='table_button' href='edit.php?id=".$list['ID']."&car=".$carID."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='delete.php?id=".$list['ID']."&car=".$carID."'><img src='".$imgSrcPath."delete.png'></a>"
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
					$webixHelper->getColumnArray('COST'),
					$webixHelper->getColumnArray('NUMBER'),
					$webixHelper->getColumnArray('SUM', array(
							'footer'=>'={ content:"summColumn" }'
						)
					),
					$webixHelper->getColumnArray('ODO'),
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
			echo 'Нет записей о дополнительном оборудовании';
			return false;
		}
	}

	/**
	 * Добавляет значения расхода на Дополнительное оборудование из формы
	 *
	 * @api
	 *
	 * @param array $arPost Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses Odo::getCurrentOdo
	 * @uses OptionalEquip::addDB
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не передан
	 *
	 * @return bool|int
	 */
	public static function addFromPost (array $arPost=null)
	{
		try
		{
			if(is_null($arPost))
			{
				throw new Exception\ArgumentNullException('$arPost');
			}
		}
		catch(Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arAdd = array();
		Fields::validateFields($arPost,$arAdd);
		if (!isset($arAdd['MY_CAR_ID']))
		{
			$arAdd['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}

		if (!isset($arAdd['DATE']))
		{
			Errors::addError('DATE', 'Не верный формат даты');
		}

		if (!isset($arAdd['COST']))
		{
			Errors::addError('COST', 'Не указана цена за единицу');
		}

		if (!isset($arAdd['NUMBER']))
		{
			Errors::addError('NUMBER', 'Не указано количество');
		}

		if (isset($arAdd['COST']) && isset($arAdd['NUMBER']))
		{
			$arAdd['SUM'] = floatval($arAdd['COST'] * $arAdd['NUMBER']);
		}

		if (!isset($arAdd['NAME']))
		{
			Errors::addError('NAME', 'Не указано название');
		}

		if (!isset($arAdd['ODO']))
		{
			if (isset($arAdd['MY_CAR_ID']))
			{
				$arAdd['ODO'] = Odo::getCurrentOdo($arAdd['MY_CAR_ID']);
			}
			else
			{
				Errors::addError('ODO', 'Не указан пробег');
			}
		}

		if (!isset($arAdd['POINTS_ID']))
		{
			Errors::addError('POINTS_ID', 'Не указана путевая точка');
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		return self::addDB($arAdd);
	}

	/**
	 * Обновляет запись о расходе на Дополнительное оборудование из формы
	 *
	 * @api
	 *
	 * @param array $arPost Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses OptionalEquip::getList
	 * @uses OptionalEquip::updateDB
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не передан
	 *
	 * @return bool
	 */
	public static function updateFromPost (array $arPost=null)
	{
		try
		{
			if(is_null($arPost))
			{
				throw new Exception\ArgumentNullException('$arPost');
			}
		}
		catch(Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arUpdate = array();
		Fields::validateFields($arPost,$arUpdate);
		if (isset($arPost['id']) && isset($arUpdate['MY_CAR_ID']))
		{
			$arList = self::getList($arUpdate['MY_CAR_ID'],$arPost['id']);
		}
		else
		{
			return false;
		}
		foreach ($arUpdate as $field=>$value)
		{
			if (!isset($arList[$field]) || ($arUpdate[$field] == $arList[$field]))
			{
				unset($arUpdate[$field]);
			}
		}

		if (!empty($arUpdate))
		{
			return self::updateDB($arPost['id'],$arUpdate);
		}
		else
		{
			return true;
		}
	}

	/**
	 * Удаляет запись о расходе на Дополнительное оборудование
	 *
	 * @api
	 *
	 * @param int $optionalEquipID ID записи
	 *
	 * @uses OptionalEquipTable::getTableName
	 * @uses OptionalEquipTable::getMapArray
	 * @uses OptionalEquipTable::getTableLinks
	 *
	 * @throws Exception\ArgumentNullException Если ID записи не указано
	 *
	 * @return bool
	 */
	public static function deleteRecord ($optionalEquipID=null)
	{
		try
		{
			if (is_null($optionalEquipID))
			{
				throw new Exception\ArgumentNullException('$repairPartsID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$query = new Query('delete');
		$query->setDeleteParams(
			$optionalEquipID,
			null,
			Tables\OptionalEquipTable::getTableName(),
			Tables\OptionalEquipTable::getMapArray(),
			Tables\OptionalEquipTable::getTableLinks()
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

	/**
	 * Добавляет обработанные данные о расходе на Дополнительное оборудование в DB
	 *
	 * @param null|array $arAdd Массив добавляемых параметров
	 *
	 * @uses OptionalEquipTable::getTableName
	 * @uses OptionalEquipTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив данных для добавления
	 *
	 * @return bool|int
	 */
	protected static function addDB ($arAdd=null)
	{
		try
		{
			if(is_null($arAdd))
			{
				throw new Exception\ArgumentNullException('$arAdd');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddNewOptionalEquip'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$arAdd));
				}
			}
		}

		$query = new Query('insert');
		$query->setInsertParams(
			$arAdd,
			Tables\OptionalEquipTable::getTableName(),
			Tables\OptionalEquipTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddNewOptionalEquip'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($arAdd,$res->getInsertId()));
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
	 * Обновляет запись о расходе на Дополнительное оборудование из обработанного массива
	 *
	 * @param int           $primary    ID изменяемой записи
	 * @param null|array    $arUpdate   Массив изменяемых полей
	 *
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив обновляемых полей
	 *
	 * @return bool
	 */
	protected static function updateDB ($primary, $arUpdate=null)
	{
		try
		{
			if(is_null($arUpdate))
			{
				throw new Exception\ArgumentNullException('$arUpdate');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateOptionalEquip'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$arUpdate,&$primary));
				}
			}
		}

		$query = new Query('update');
		$query->setUpdateParams(
			$arUpdate,
			$primary,
			Tables\OptionalEquipTable::getTableName(),
			Tables\OptionalEquipTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateOptionalEquip'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($arUpdate,$primary));
					}
				}
			}

			return true;
		}
		else
		{
			return false;
		}
	}
}