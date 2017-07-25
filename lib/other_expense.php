<?php
/**
 * MSergeev\Packages\Icar\Lib\OtherExpense
 * Прочие расходы
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;

/**
 * Class OtherExpense
 *
 * Events:
 * OnBeforeAddNewOtherExpense - Перед добавлением записи о прочих расходах (массив полей записи)
 * OnAfterAddNewOtherExpense - После добавления записи о прочих расходах (массив полей записи и ID записи)
 * OnBeforeUpdateOtherExpense - Перед изменением записи о прочих расходах (массив изменяемых полей и ID изменяемой записи)
 * OnAfterUpdateOtherExpense - После изменения записи о прочих расходах (массив измененных полей и ID измененной записи)
 */
class OtherExpense
{
	/**
	 * @var array $arTableFields Массив возвращаемых из таблицы полей
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
		'FLOW_TYPE_ID',
		'FLOW_TYPE_ID.NAME' => 'FLOW_TYPE_NAME',
		'FLOW_TYPE_ID.CODE' => 'FLOW_TYPE_CODE',
		'NAME',
		'CATALOG_NUMBER',
		'COST',
		'NUMBER',
		'SUM',
		'ODO',
		'POINTS_ID',
		'POINTS_ID.NAME' => 'POINT_NAME',
		'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
		'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
		'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
		'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
		'DESCRIPTION' => 'INFO',
		'CHECK',
		'CHECK.WIDTH' => 'CHECK_WIDTH',
		'CHECK.HEIGHT' => 'CHECK_HEIGHT',
		'CHECK.SUBDIR' => 'CHECK_SUBDIR',
		'CHECK.FILE_NAME' => 'CHECK_FILE_NAME',
		'CHECK.DESCRIPTION' => 'CHECK_DESCRIPTION'
	);

	/**
	 * Возвращает таблицу со списком Прочих расходов
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpense::getList
	 * @uses IcarWebixHelper
	 * @uses MSergeev\Core\Lib\DateHelper
	 * @uses MSergeev\Core\Lib\Tools::getSitePath
	 * @uses MSergeev\Core\Lib\Loader::getTemplate
	 * @uses Msergeev\Core\Lib\Webix::showDataTable
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
			echo 'Нет записей о прочих расходах';
			return false;
		}
	}

	/**
	 * Возвращает сумму Прочих расходов за все время
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpenseTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\OtherExpenseTable::getTableName());
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
	 * Возвращает сумму Прочих расходов за Год
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    Флаг: true - текущий год, false - прошлый год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpenseTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\OtherExpenseTable::getTableName());
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
	 * Возвращает сумму Прочих расходов за Месяц
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    Флаг: true - текущий месяц, false - прошлый месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpenseTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\OtherExpenseTable::getTableName());
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
	 * Возвращает сумму максимального чека (расхода)
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpenseTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @return float
	 */
	public static function getMaxCheck ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$helper = new CoreLib\SqlHelper(Tables\OtherExpenseTable::getTableName());
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
	 * Возвращает сумму минимального чека (расхода)
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpenseTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\OtherExpenseTable::getTableName());
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
	 * Возвращает список записей о Прочих расходах, либо указанную запись
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля
	 * @param null|int  $getID  ID записи, если нужно вернуть только ее
	 * @param int       $limit  Лимит возвращаемых записей
	 * @param int       $offset Смещение выборки, для постранички
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses OtherExpenseTable::getList
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
				'DATE' => 'DESC',
				'ID' => 'DESC'
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

		$arRes = Tables\OtherExpenseTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Добавляет запись о Прочих расходах из формы
	 *
	 * @api
	 *
	 * @param null|array $arPost    Массив POST параметров
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses OtherExpense::addDB
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив POST данных
	 *
	 * @return bool
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
			Errors::addError('FORMAT:DATE', 'Не верный формат даты');
		}

		if (!isset($arAdd['NAME']))
		{
			Errors::addError('EMPTY:NAME', 'Не указано название');
		}

		if (!isset($arAdd['FLOW_TYPE_ID']))
		{
			Errors::addError('EMPTY:FLOW_TYPE_ID', 'Не указан тип расхода');
		}

		if (!isset($arAdd['COST']))
		{
			Errors::addError('EMPTY:COST', 'Не указана цена за единицу');
		}

/*		if (!isset($arAdd['NUMBER']))
		{
			Errors::addError('EMPTY:NUMBER', 'Не указано количество');
		}*/

		if (isset($arAdd['COST']) && isset($arAdd['NUMBER']))
		{
			$arAdd['SUM'] = floatval($arAdd['COST'] * $arAdd['NUMBER']);
		}

		if (!isset($arAdd['ODO']))
		{
			$arAdd['ODO'] = 0;
		}

		if (!isset($arAdd['POINTS_ID']))
		{
			Errors::addError('EMPTY:POINTS_ID', 'Не указана путевая точка');
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		return self::addDB($arAdd);
	}

	/**
	 * Обновляет запись о Прочих расходах из формы
	 *
	 * @api
	 *
	 * @param null|array $arPost    Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses OtherExpense::getList
	 * @uses OtherExpense::updateDB
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив POST данных
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
			$arData = self::getList($arUpdate['MY_CAR_ID'],$arPost['id']);
		}
		else
		{
			return false;
		}
		foreach ($arUpdate as $field=>$value)
		{
			//TODO: Разобраться с костылем
			//Костыльный костыль. Глюк с полем CATALOG_NUMBER. Принудительно обновляем всегда
			if ((!isset($arData[$field]) && $field!='CATALOG_NUMBER') || ($arData[$field]==$arUpdate[$field]))
			{
				unset($arUpdate[$field]);
			}
		}
		if (!empty($arUpdate))
		{
			return self::updateDB ($arPost['id'],$arUpdate);
		}
		else
		{
			return true;
		}

	}

	/**
	 * Удаляет запись Прочего расхода
	 *
	 * @api
	 *
	 * @param int $deleteID    ID удаляемой записи
	 *
	 * @uses OtherExpenseTable::getTableName
	 * @uses OtherExpenseTable::getMapArray
	 * @uses OtherExpenseTable::getTableLinks
	 *
	 * @throws Exception\ArgumentNullException Если не указан ID удаляемой записи
	 *
	 * @return bool
	 */
	public static function deleteRecord ($deleteID=null)
	{
		try
		{
			if (is_null($deleteID))
			{
				throw new Exception\ArgumentNullException('$deleteID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$query = new Query('delete');
		$query->setDeleteParams(
			$deleteID,
			null,
			Tables\OtherExpenseTable::getTableName(),
			Tables\OtherExpenseTable::getMapArray(),
			Tables\OtherExpenseTable::getTableLinks()
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
	 * Добавляет запись о прочих расходах в DB
	 *
	 * @param array $arAdd Массив добавляемых значений
	 *
	 * @uses OtherExpenseTable::getTableName
	 * @uses OtherExpenseTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив полей добавляемой записи
	 *
	 * @return bool|int
	 */
	protected static function addDB (array $arAdd=null)
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddNewOtherExpense'))
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
			Tables\OtherExpenseTable::getTableName(),
			Tables\OtherExpenseTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddNewOtherExpense'))
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
			if (isset($arData['CHECK']) && !is_null($arData['CHECK']))
			{
				CoreLib\File::deleteFile($arData['CHECK']);
			}
			return false;
		}
	}

	/**
	 * Обновляет данные о прочих расходах в DB
	 *
	 * @param null|int      $primary    ID записи
	 * @param null|array    $arUpdate   Массив изменяемых полей
	 *
	 * @uses OtherExpenseTable::getTableName
	 * @uses OtherExpenseTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throw Exception\ArgumentNullException Если не передан ID изменяемой записи или массив изменяемых полей
	 * @throw Exception\ArgumentTypeException Если вместо массива изменяемых полей передан не массив
	 *
	 * @return bool
	 */
	protected static function updateDB ($primary=null, array $arUpdate=null)
	{
		try
		{
			if (is_null($primary))
			{
				throw new Exception\ArgumentNullException('$primary');
			}
			if (is_null($arUpdate))
			{
				throw new Exception\ArgumentNullException('$arUpdate');
			}
			if (!is_array($arUpdate))
			{
				throw new Exception\ArgumentTypeException('$arUpdate','array');
			}
			if (empty($arUpdate))
			{
				return true;
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateOtherExpense'))
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
			Tables\OtherExpenseTable::getTableName(),
			Tables\OtherExpenseTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateOtherExpense'))
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