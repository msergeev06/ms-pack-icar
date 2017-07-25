<?php
/**
 * MSergeev\Packages\Icar\Lib\Ts
 * Прохождение ТО
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Date;
use MSergeev\Core\Entity\Query;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;

/**
 * Class Ts
 *
 * Events:
 * OnBeforeAddNewTs - Перед добавлением записи о прохождении ТО (массив полей записи)
 * OnAfterAddNewTs - После добавления записи о прохождении ТО (массив полей записи, ID добавленной записи)
 * OnBeforeUpdateTs - Перед изменением записи о прохождении ТО (массив изменяемых полей, ID записи)
 * OnAfterUpdateTs - После изменения записи о прохождении ТО (массив измененных полей, ID измененной записи)
 * OnBeforeDeleteTs - Перед удалением записи о прохождении ТО (ID удаляемой записи)
 * OnAfterDeleteTs - После попытки удаления записи о прохождении ТО (ID удаляемой записи, флаг успешности удаления)
 */
class Ts
{
	/**
	 * @var array Массив возвращаемых из таблицы полей
	 */
	private static $arTableFields = array(
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
	);
	/**
	 * @var int Максимальное число ТО в списке
	 */
	protected static $maxTsSelect = 25;

	/**
	 * Возвращает общую сумму расходов на ТО для автомобиля
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses TsTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalMaintenanceCosts ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}
		$helper = new SqlHelper(Tables\TsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getSumFunction('COST','SUM')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			return floatval($ar_res['SUM']);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Возвращает максимальное значение одометра из записей прохождения ТО
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses TsTable::getTableName
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

		$helper = new SqlHelper(Tables\TsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			.$helper->getMaxFunction('ODO','MAX_ODO')."\n"
			."FROM\n\t"
			.$helper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$helper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			if (isset($ar_res['MAX_ODO']))
			{
				return floatval($ar_res['MAX_ODO']);
			}
		}

		return floatval(0);
	}

	/**
	 * Возвращает список всех расходов на ТО для автомобиля, либо указанный
	 *
	 * @api
	 *
	 * @param null|int $carID   ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param null|int $getID   ID записи, если нужна только 1
	 * @param int  $limit       Лимит вывода
	 * @param int  $offset      Смещение вывода
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses TsTable::getList
	 *
	 * @return array|bool
	 */
	public static function getList($carID=null,$getID=null,$limit=0,$offset=0)
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

		//msDebug($arList);

		$arRes = Tables\TsTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Возвращает тег <select> со списком ТО
	 *
	 * @api
	 *
	 * @param int    $carID             ID автомобиля
	 * @param string $strBoxName        Параметр name тега <select>
	 * @param string $strDetText        Текст по-умолчанию (выбранный)
	 * @param string $strSelectedVal    Выбранный пункт тега <select>
	 * @param string $field1            Прочие параметры тега <select>
	 *
	 * @uses Ts::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectTsList ($carID, $strBoxName, $strDetText='Не выбрано', $strSelectedVal = "null", $field1="class=\"tslistselect\"")
	{
		$arRes = self::getList($carID);
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

	/**
	 * Возвращает html код таблицы записей о расходах на ТО
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses Ts::getList
	 * @uses IcarWebixHelper
	 * @uses MSergeev\Core\Lib\DateHelper
	 * @uses MSergeev\Core\Lib\Tools::getSitePath
	 * @uses MSergeev\Core\Lib\Loader::getTemplate
	 * @uses MSergeev\Core\Lib\Webix::showDataTable
	 *
	 * @return bool|void
	 */
	public static function showListTable ($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$arList = static::getList($carID);
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
					'edit' => "<a class='table_button' href='edit.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='delete.php?id=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
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

			CoreLib\Webix::showDataTable($arData);
			return true;
		}
		else
		{
			echo 'Нет данных о прохождении ТО';
			return false;
		}
	}

	/**
	 * Возвращает тег <select> со списком ТО
	 *
	 * @api
	 *
	 * @param string $selectName    Параметр name тега <select>
	 * @param string $selected      Вариант, который будет выбран
	 * @param string $field1        Дополнительные параметры <select>
	 *
	 * @uses Ts::$maxTsSelect
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectTsNum ($selectName, $selected = "null", $field1='')
	{
		$arValues = array();
		for ($i=0; $i<=static::$maxTsSelect; $i++)
		{
			$arValues[] = array(
				'NAME' => 'ТО-'.$i,
				'VALUE' => $i
			);
		}
		return SelectBox($selectName,$arValues,"",$selected,$field1);
	}

	/**
	 * Возвращает тег <select> со списком исполнителей работ
	 *
	 * @api
	 *
	 * @param string $selectName    Параметр name тега <select>
	 * @param string $selected      Вариант, который будет выбран
	 * @param string $field         Дополнительные параметры <select>
	 *
	 * @uses ExecutorTable::getList
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectExecutor ($selectName, $selected = "null", $field='')
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
		return SelectBox ($selectName,$arValues,"",$selected,$field);
	}

	/**
	 * Осуществляет проверку параметров формы добавления новой записи о расходах на ТО и добавляет запись
	 *
	 * @api
	 *
	 * @param null|array $post  Массив $_POST формы
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses Ts::addDB
	 * @uses MSergeev\Core\Lib\Options::setOption
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не задан
	 *
	 * @return bool|int
	 */
	public static function addFromPost (array $post=null)
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
		Fields::validateFields($post,$arAdd);
		if (!isset($arAdd['MY_CAR_ID']))
		{
			$arAdd['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}

		if (!isset($arAdd['TS_NUM']))
		{
			Errors::addError('TS_NUM','Не указан номер ТО');
		}

		if (!isset($arAdd['DATE']))
		{
			Errors::addError('DATE','Неверный формат даты');
		}

		//TODO: Исправить поле на EXECUTOR_ID
		if (!isset($arAdd['EXECUTOR_ID']))
		{
			Errors::addError('EXECUTOR_ID','Не указан исполнитель работ');
		}
		else
		{
			$arAdd['EXECUTORS_ID'] = $arAdd['EXECUTOR_ID'];
			unset($arAdd['EXECUTOR_ID']);
		}

		if (!isset($arAdd['COST']))
		{
			$arAdd['COST'] = 0;
		}

		if (!isset($arAdd['POINTS_ID']))
		{
			Errors::addError('POINTS_ID','Не указана путевая точка');
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		if ($addTsID = static::addDB($arAdd))
		{
			CoreLib\Options::setOption('icar_last_ts_'.$arAdd['MY_CAR_ID'],$arAdd['TS_NUM']);
			CoreLib\Options::setOption('icar_last_executor_'.$arAdd['MY_CAR_ID'],$arAdd['EXECUTOR_ID']);
			CoreLib\Options::setOption('icar_last_executor_'.$arAdd['MY_CAR_ID'].'_point',$arAdd['POINTS_ID']);
			return $addTsID;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Осуществляет проверку параметров формы редактирования записи о расходах на ТО и обновляет запись
	 *
	 * @api
	 *
	 * @param null|int      $tsID   ID редактируемой записи
	 * @param null|array    $post   Массив новых значений
	 *
	 * @uses Fields::validateFields
	 * @uses Ts::updateDB
	 *
	 * @throws Exception\ArgumentNullException Если ID изменяемой записи или массив изменяемых полей не заданы
	 *
	 * @return bool
	 */
	public static function updateFromPost ($tsID=null, $post=null)
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
		Fields::validateFields($post, $arUpdate);

		if ($res = self::updateDB($tsID, $arUpdate))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	//TODO: Потестить удаление при связанных записях
	/**
	 * Удаляет запись о прохождении ТО
	 *
	 * @api
	 *
	 * @param null|int $tsID    ID удаляемой записи
	 *
	 * @uses TsTable::getTableName
	 * @uses TsTable::getMapArray
	 * @uses TsTable::getTableLinks
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если ID удаляемой записи не задан
	 *
	 * @return bool
	 */
	public static function deleteRecord ($tsID=null)
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeDeleteTs'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$tsID));
				}
			}
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
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteTs'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($tsID,true));
					}
				}
			}

			return true;
		}
		else
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteTs'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($tsID,false));
					}
				}
			}

			return false;
		}
	}

	/**
	 * Добавляет новую запись в DB о расходах на ТО
	 *
	 * @param array $arAdd  Массив обработанных данных о прохождении ТО
	 *
	 * @uses TsTable::getTableName
	 * @uses TsTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не задан
	 * @throws Exception\ArgumentTypeException Если вместо массива передан не массив
	 *
	 * @return bool|int
	 */
	protected static function addDB (array $arAdd=null)
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddNewTs'))
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
			Tables\TsTable::getTableName(),
			Tables\TsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddNewTs'))
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
	 * Обновляет данный указанной записи о прохождении ТО
	 *
	 * @param null|int      $tsID       ID редактируемой записи
	 * @param null|array    $arUpdate   Массив новых данных
	 *
	 * @uses TsTable::getList
	 * @uses MSergeev\Core\Entity\Date
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не указан ID изменяемой записи или массив изменяемых полей
	 *
	 * @return bool
	 */
	protected static function updateDB ($tsID=null, $arUpdate=null)
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

		$arCompare = Tables\TsTable::getList(
			array(
				'filter' => array('ID'=>$tsID),
				'limit' => 1
			)
		);
		if ($arCompare && isset($arCompare[0]))
		{
			$arCompare = $arCompare[0];
		}
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
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateTs'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array(&$arUpdate,&$tsID));
					}
				}
			}

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
				if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateTs'))
				{
					foreach ($arEvents as $sort=>$ar_events)
					{
						foreach ($ar_events as $arEvent)
						{
							CoreLib\Events::executePackageEvent($arEvent,array($arUpdate,$tsID));
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

}