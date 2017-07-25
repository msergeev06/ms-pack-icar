<?php
/**
 * MSergeev\Packages\Icar\Lib\Repair
 * Ремонт
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Packages\Icar\Tables\RepairTable;
use MSergeev\Core\Lib as CoreLib;

/**
 * Class Repair
 *
 * Events:
 * OnBeforeAddNewRepair - Перед добавлением записи о ремонте (массив полей записи)
 * OnAfterAddNewRepair - После добавления записи о ремонте (массив полей записи, ID добавленной записи)
 * OnBeforeUpdateRepair - Перед изменением записи о ремонте (массив изменяемых полей, ID изменяемой записи)
 * OnAfterUpdateRepair - После изменения записи о ремонте (массив измененных полей, ID измененной записи)
 * OnBeforeDeleteRepair - Перед удалением записи о ремонте (ID удаляемой записи)
 * OnAfterDeleteRepair - После попытки удаления записи о ремонте (ID удаляемой записи, флаг успешного удаления)
 */
class Repair
{
	/**
	 * @var array Массив возвращаемых из формы значений
	 */
	private static $arTableFields = array(
		'ID',
		'MY_CAR_ID',
		'MY_CAR_ID.NAME' => 'MY_CAR_NAME',
		'MY_CAR_ID.CAR_NUMBER' => 'MY_CAR_NUMBER',
		'DATE',
		'COST',
		'EXECUTOR_ID',
		'EXECUTOR_ID.NAME' => "EXECUTOR_NAME",
		'EXECUTOR_ID.CODE' => "EXECUTOR_CODE",
		'NAME',
		'ODO',
		'REASON_REPLACEMENT_ID',
		'REASON_REPLACEMENT_ID.NAME' => 'REASON_REPLACEMENT_NAME',
		'REASON_REPLACEMENT_ID.CODE' => 'REASON_REPLACEMENT_CODE',
		'TS_ID',
		'TS_ID.TS_NUM' => 'TS_NUM',
		'ACCIDENT_ID',
		'ACCIDENT_ID.DESCRIPTION' => 'ACCIDENT_DESCRIPTION',
		'WHO_PAID_ID',
		'WHO_PAID_ID.NAME' => 'WHO_PAID_NAME',
		'WHO_PAID_ID.CODE' => 'WHO_PAID_CODE',
		'POINTS_ID',
		'POINTS_ID.NAME' => 'POINT_NAME',
		'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
		'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
		'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
		'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
		'DESCRIPTION' => 'INFO'
	);

	/**
	 * Возвращает тег <select> со списком произведенных ремонтов
	 *
	 * @api
	 *
	 * @param int    $carID             ID автомобиля
	 * @param string $strBoxName        Свойство name тега <select>
	 * @param string $strDetText        Текст пустого значения
	 * @param string $strSelectedVal    Значение по-умолчанию
	 * @param string $field1            Дополнительные свойства тега <select>
	 *
	 * @uses Repair::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectRepairList ($carID, $strBoxName, $strDetText='', $strSelectedVal = "null", $field1="class=\"repairlistselect\"")
	{
		if ($strDetText=='')
		{
			$strDetText='Не выбрано';
		}
		$arRes = self::getList($carID);
		if ($arRes)
		{
			$arValue = array();
			foreach ($arRes as $ar_res)
			{
				$arValue[] = array(
					'NAME' => $ar_res['DATE'].' '.$ar_res['NAME'],
					'VALUE' => $ar_res['ID']
				);
			}

			return SelectBox($strBoxName, $arValue, $strDetText, $strSelectedVal, $field1);
		}

	}

	/**
	 * Возвращает список записей о произведенных ремонтах, либо указанную запись
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля
	 * @param null|int  $getID  ID записи, если необходимо вернуть только ее
	 * @param int       $limit  Лимит записей
	 * @param int       $offset Смещение, для постраничной навигации
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairTable::getList
	 *
	 * @return array|bool
	 */
	public static function getList ($carID=null, $getID=null,$limit=0,$offset=0)
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

		$arRes = RepairTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Возвращает сумму расходов произведенных ремонтов за все время
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @return float
	 */
	public static function getTotalCosts ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new SqlHelper(RepairTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('COST','SUM')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID').' = '.$carID;

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
	 * Возвращает сумму расходов произведенных ремонтов за Год
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      @now    Флаг: true - текущий год, false - прошлый год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
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

		$helper = new SqlHelper(RepairTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('COST','SUM')."\n"
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
	 * Возвращает сумму расходов произведенных ремонтов за Месяц
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      @now    Флаг: true - текущий месяц, false - прошлый месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
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

		$helper = new SqlHelper(RepairTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('COST','SUM')."\n"
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
	 * Возвращает значение максимального чека (расхода)
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @return float
	 */
	public static function getMaxCheck ($carID=null)
	{
		if(is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new SqlHelper(RepairTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('COST','MAX')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID').' = '.$carID;

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
	 * Возвращает значение минимального чека (расхода)
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @return float
	 */
	public static function getMinCheck ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new SqlHelper(RepairTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMinFunction('COST','MIN')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID').' = '.$carID;

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
	 * Добавляет запись о произведенном ремонте из формы
	 *
	 * @api
	 *
	 * @param null|array $post  Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses Odo::getCurrentOdo
	 * @uses ReasonReplacement::getCodeById
	 * @uses Repair::addDB
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не задан
	 *
	 * @return bool|int
	 */
	public static function addFromPost ($post=null)
	{
		try {
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('$post');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die ($e->showException());
		}

		$arAdd = array();
		Fields::validateFields($post,$arAdd);
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
			Errors::addError('COST', 'Не указана стоимость');
		}

		if (!isset($arAdd['EXECUTOR_ID']))
		{
			Errors::addError('EXECUTOR_ID', 'Не указан исполнитель работ');
		}

		if (!isset($arAdd['NAME']))
		{
			Errors::addError('NAME', 'Не указано название работ');
		}

		if (!isset($arAdd['ODO']))
		{
			$arAdd['ODO'] = Odo::getCurrentOdo($arAdd['MY_CAR_ID']);
		}

		if (!isset($arAdd['REASON_REPLACEMENT_ID']))
		{
			Errors::addError('REASON_REPLACEMENT_ID', 'Не указана причина производимых работ');
		}
		else
		{
			$reasonCode = ReasonReplacement::getCodeById($arAdd['REASON_REPLACEMENT_ID']);
			switch ($reasonCode)
			{
				case 'ts':
					if (!isset($arAdd['TS_ID']))
					{
						Errors::addError('TS_ID', 'Не указан номер ТО','WARNING');
					}
					break;
				case 'accident':
					if (!isset($arAdd['ACCIDENT_ID']))
					{
						Errors::addError('ACCIDENT_ID', 'Не указано ДТП', 'WARNING');
					}
					break;
				default:
					break;
			}
		}

		if (!isset($arAdd['WHO_PAID_ID']))
		{
			Errors::addError('WHO_PAID_ID', 'Не указано кто платил');
		}

		if (!isset($arAdd['POINTS_ID']))
		{
			Errors::addError('POINTS_ID', 'Не указана путевая точка');
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		if ($insertID = static::addDB($arAdd))
		{
			return $insertID;
		}
		else
		{
			return false;
		}

	}

	/**
	 * Обновляет запись о произведенном ремонте данными из формы
	 *
	 * @api
	 *
	 * @param null|array $post  Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses Repair::getList
	 * @uses Repair::updateDB
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не задан
	 *
	 * @return bool
	 */
	public static function updateFromPost ($post=null)
	{
		try {
			if (is_null($post))
			{
				throw new Exception\ArgumentNullException('$post');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			die ($e->showException());
		}

		$arUpdate = array();
		Fields::validateFields($post,$arUpdate);
		if (isset($post['id']) && isset($arUpdate['MY_CAR_ID']))
		{
			$arRepair = self::getList($arUpdate['MY_CAR_ID'],$post['id']);
		}
		foreach ($arUpdate as $field=>$value)
		{
			if (!isset($arRepair[$field]) || ($arRepair[$field] == $arUpdate['field']))
			{
				unset($arUpdate[$field]);
			}
		}
		if (empty($arUpdate))
		{
			return true;
		}


		return self::updateDB($post['id'],$arUpdate);
	}

	/**
	 * Возвращает таблицу со списком расходов по произведенным ремонтам
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Repair::getList
	 * @uses IcarWebixHelper
	 * @uses MSergeev\Core\Lib\DateHelper
	 * @uses MSergeev\Core\Lib\Tools::getSitePath
	 * @uses MSergeev\Core\Lib\Loader::getTemplate
	 * @uses MSergeev\Core\Lib\Webix::showDataTable
	 *
	 * @return bool
	 */
	public static function showListTable ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arList = self::getList($carID);

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
					'odo' => "=".$list['ODO'],
					'name' => $list['NAME'],
					'cost' => "=".$list['COST'],
					'executors_name' => $list['EXECUTOR_NAME'],
					'reason_replacement_name' => $list['REASON_REPLACEMENT_NAME'],
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
					$webixHelper->getColumnArray('ODO'),
					$webixHelper->getColumnArray('NAME'),
					$webixHelper->getColumnArray('COST', array(
							'footer'=>'={ content:"summColumn" }'
						)
					),
					$webixHelper->getColumnArray('EXECUTORS'),
					$webixHelper->getColumnArray('REASON_REPLACEMENT'),
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
			echo 'Нет записей о произведенном ремонте';
			return false;
		}
	}

	/**
	 * Удаляет указанную запись о произведенном ремонте
	 *
	 * @api
	 *
	 * @param null|int $repairID ID записи о ремонте
	 *
	 * @uses RepairTable::getTableName
	 * @uses RepairTable::getMapArray
	 * @uses RepairTable::getTableLinks
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если ID записи не указано
	 *
	 * @return bool
	 */
	public static function deleteRecord ($repairID=null)
	{
		try
		{
			if (is_null($repairID))
			{
				throw new Exception\ArgumentNullException('$repairPartsID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeDeleteRepair'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$repairID));
				}
			}
		}

		$query = new Query('delete');
		$query->setDeleteParams(
			$repairID,
			null,
			RepairTable::getTableName(),
			RepairTable::getMapArray(),
			RepairTable::getTableLinks()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteRepair'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($repairID,true));
					}
				}
			}

			return true;
		}
		else
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteRepair'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($repairID,false));
					}
				}
			}

			return false;
		}
	}

	/**
	 * Добавляет данные о произведенном ремонте в DB из обработанного массива
	 *
	 * @param array $arAdd Массив с добавляемыми полями
	 *
	 * @uses RepairTable::getTableName
	 * @uses RepairTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return bool|int
	 */
	protected static function addDB ($arAdd=array())
	{
		if (empty($arAdd))
		{
			return false;
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddNewRepair'))
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
			RepairTable::getTableName(),
			RepairTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddNewRepair'))
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
	 * Обновляет запись о ремонте в DB из обработанного массива
	 *
	 * @param null|int  $primary    ID изменяемой записи
	 * @param array     $arUpdate   Массив изменений
	 *
	 * @uses RepairTable::getTableName
	 * @uses RepairTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не задан ID изменяемой записи или массив изменяемых полей
	 * @throws Exception\ArgumentOutOfRangeException Если ID изменяемой записи <= 0
	 *
	 * @return bool
	 */
	protected static function updateDB ($primary=null, $arUpdate=array())
	{
		$bException = false;
		try
		{
			if (is_null($primary))
			{
				throw new Exception\ArgumentNullException('$primary');
			}
			if ($primary<=0)
			{
				throw new Exception\ArgumentOutOfRangeException('$primary','1');
			}
			if (empty($arUpdate))
			{
				throw new Exception\ArgumentNullException('$arUpdate');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			$bException = true;
		}
		catch (Exception\ArgumentOutOfRangeException $e2)
		{
			$e2->showException();
			$bException = true;
		}
		if ($bException)
		{
			return false;
		}

		if (isset($arUpdate['ID']))
		{
			unset($arUpdate['ID']);
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateRepair'))
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
			RepairTable::getTableName(),
			RepairTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateRepair'))
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