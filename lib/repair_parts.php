<?php
/**
 * MSergeev\Packages\Icar\Lib\RepairParts
 * Запасные части
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Entity\Query;

/**
 * Class RepairParts
 *
 *
 * Events:
 * OnBeforeAddNewRepairParts - Перед добавлением записи о покупке запчасти (массив полей записи)
 * OnAfterAddNewRepairParts - После добавления записи о покупке запчасти (массив полей записи, ID добавленной записи)
 * OnBeforeUpdateRepairParts - Перед изменением записи о покупке запчасти (массив изменяемых полей, ID изменяемой записи)
 * OnAfterUpdateRepairParts - После попытки изменения записи о покупки запчасти (массив измененных полей, ID записи, флаг успешности обновления)
 * OnBeforeDeleteRepairParts - Перед удалением записи о покупке запчасти (ID удаляемой записи)
 * OnAfterDeleteRepairParts - После попытки удаления записи о покупке запчасти (ID удаляемой записи, флаг успешности удаления)
 */
class RepairParts
{
	/**
	 * @var array Массив возвращаемых из базы полей
	 */
	private static $arTableFields = array(
		'ID',
		'MY_CAR_ID',
		'MY_CAR_ID.NAME' => 'MY_CAR_NAME',
		'MY_CAR_ID.CAR_NUMBER' => 'MY_CAR_NUMBER',
		'DATE',
		'NAME',
		'STORAGE_ID',
		'STORAGE_ID.NAME' => 'STORAGE_NAME',
		'STORAGE_ID.CODE' => 'STORAGE_CODE',
		'CATALOG_NUMBER',
		'NUMBER',
		'COST',
		'REASON_REPLACEMENT_ID',
		'REASON_REPLACEMENT_ID.NAME' => 'REASON_REPLACEMENT_NAME',
		'REASON_REPLACEMENT_ID.CODE' => 'REASON_REPLACEMENT_CODE',
		'TS_ID',
		'TS_ID.TS_NUM' => 'TS_NUM',
		'ACCIDENT_ID',
		'ACCIDENT_ID.DESCRIPTION'=>'ACCIDENT_DESCRIPTION',
		'REPAIR_ID',
		'REPAIR_ID.NAME'=>'REPAIR_NAME',
		'WHO_PAID_ID',
		'WHO_PAID_ID.NAME' => 'WHO_PAID_NAME',
		'WHO_PAID_ID.CODE' => 'WHO_PAID_CODE',
		'ODO',
		'POINTS_ID',
		'POINTS_ID.NAME' => 'POINT_NAME',
		'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
		'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
		'POINTS_ID.POINT_TYPES_ID.CODE' => 'POINT_TYPE_CODE',
		'POINTS_ID.ADDRESS' => 'POINT_ADDRESS',
		'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
		'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
		'DESCRIPTION'
	);

	/**
	 * Возвращает сумму расходов на запчасти за все время
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairPartsTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCosts($carID=null)
	{
		if (is_null($carID))
		{
			$carID = MyCar::getDefaultCarID();
		}

		$sqlHelper = new CoreLib\SqlHelper(Tables\RepairPartsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."SUM(".$sqlHelper->wrapFieldQuotes('NUMBER')." * "
			.$sqlHelper->wrapFieldQuotes('COST').") AS SUMM\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
			return floatval($fuelCosts);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Возвращает сумму расходов на запчасти за Год
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    Флаг: true - текущий год, false - прошлый год
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairPartsTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCostsYear($carID=null, $now=true)
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

		$sqlHelper = new CoreLib\SqlHelper(Tables\RepairPartsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."SUM(".$sqlHelper->wrapFieldQuotes('NUMBER')." * "
			.$sqlHelper->wrapFieldQuotes('COST').") AS SUMM\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." >= '".$year."-01-01' AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." <= '".$year."-12-31'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
			return floatval($fuelCosts);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Возвращает сумму расходов на запчасти за Месяц
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 * @param bool      $now    Флаг: true - текущий месяц, false - прошлый месяц
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairPartsTable::getTableName
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return float
	 */
	public static function getTotalCostsMonth($carID=null, $now=true)
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

		$sqlHelper = new CoreLib\SqlHelper(Tables\RepairPartsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."SUM(".$sqlHelper->wrapFieldQuotes('NUMBER')." * "
			.$sqlHelper->wrapFieldQuotes('COST').") AS SUMM\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID." AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." >= '".$monthYear."-01' AND\n\t"
			.$sqlHelper->wrapFieldQuotes('DATE')." <= '".$monthYear."-".$days."'";
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['SUMM'];
			return floatval($fuelCosts);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Возвращает значение максимального чека (расхода)
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairPartsTable::getTableName
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

		$sqlHelper = new CoreLib\SqlHelper(Tables\RepairPartsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."MAX(".$sqlHelper->wrapFieldQuotes('NUMBER')." * "
			.$sqlHelper->wrapFieldQuotes('COST').") AS MAX\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['MAX'];
			return floatval($fuelCosts);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Возвращает значение минимального чека (расхода)
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairPartsTable::getTableName
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

		$sqlHelper = new CoreLib\SqlHelper(Tables\RepairPartsTable::getTableName());
		$query = new Query('select');
		$sql = "SELECT\n\t"
			."MIN(".$sqlHelper->wrapFieldQuotes('NUMBER')." * "
			.$sqlHelper->wrapFieldQuotes('COST').") AS MIN\n"
			."FROM\n\t"
			.$sqlHelper->wrapTableQuotes()."\n"
			."WHERE\n\t"
			.$sqlHelper->wrapFieldQuotes('MY_CAR_ID')." = ".$carID;
		$query->setQueryBuildParts($sql);
		$res = $query->exec();
		if ($ar_res = $res->fetch())
		{
			$fuelCosts = $ar_res['MIN'];
			return floatval($fuelCosts);
		}
		else
		{
			return floatval(0);
		}
	}

	/**
	 * Возвращает максимальное значение одометра из записей о покупке запчастей
	 *
	 * @api
	 *
	 * @param int|null $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairPartsTable::getTableName
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

		$helper = new CoreLib\SqlHelper(Tables\RepairPartsTable::getTableName());
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
	 * Обрабатывает данные из формы добавления запчастей
	 *
	 * @api
	 *
	 * @param array $post  Массив значений из формы
	 *
	 * @uses Fields::validateFields
	 * @uses MyCar::getDefaultCarID
	 * @uses Errors::addError
	 * @uses Errors::issetErrors
	 * @uses ReasonReplacement::getCodeById
	 * @uses RepairParts::addDB
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не передан
	 *
	 * @return bool|int
	 */
	public static function addFromPost(array $post=null)
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
		Fields::validateFields($post,$arAdd);
		if (!isset($arAdd['MY_CAR_ID']))
		{
			$arAdd['MY_CAR_ID'] = MyCar::getDefaultCarID();
		}

		if (!isset($arAdd['NAME']))
		{
			Errors::addError('NAME','Не указано имя');
		}

		if (!isset($arAdd['STORAGE_ID']))
		{
			Errors::addError('STORAGE_ID','Не указано место хранения');
		}

		if (!isset($arAdd['NUMBER']))
		{
			$arAdd['NUMBER'] = 1;
		}

		if (!isset($arAdd['REASON_REPLACEMENT_ID']))
		{
			Errors::addError('REASON_REPLACEMENT_ID','Не указана причина замены','WARNING');
		}
		else
		{
			$reasonCode = ReasonReplacement::getCodeById($arAdd['REASON_REPLACEMENT_ID']);
			switch ($reasonCode)
			{
				case 'ts':
					if (!isset($arData['TS_ID']))
					{
						Errors::addError('TS_ID','Не выбрана запись прохождения ТО','WARNING');
					}
					break;
				case 'breakdown':
					if (!isset($arData['REPAIR_ID']))
					{
						Errors::addError('REPAIR_ID','Не выбрана запись проведения ремонта','WARNING');
					}
					break;
				case 'tuning':
					if (!isset($arData['REPAIR_ID']))
					{
						Errors::addError('REPAIR_ID','Не выбрана запись проведения ремонта','WARNING');
					}
					break;
				case 'upgrade':
					if (!isset($arData['REPAIR_ID']))
					{
						Errors::addError('REPAIR_ID','Не выбрана запись проведения ремонта','WARNING');
					}
					break;
				case 'tire':
					break;
				case 'accident':
					if (!isset($arAdd['ACCIDENT_ID']))
					{
						Errors::addError('ACCIDENT_ID','Не выбрана запись о ДТП','WARNING');
					}
					break;
				default:
					return false;
			}
		}

		if (Errors::issetErrors())
		{
			return false;
		}

		return static::addDB($arAdd);
	}

	/**
	 * Обновляет данные о приоретенных запчастях из формы
	 *
	 * @api
	 *
	 * @param array $post  Массив POST данных
	 *
	 * @uses Fields::validateFields
	 * @uses RepairParts::getList
	 * @uses RepairParts::updateDB
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если массив POST данных не передан
	 *
	 * @return bool
	 */
	public static function updateFromPost (array $post=null)
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

		$arUpdate = array();
		Fields::validateFields($post,$arUpdate);
		if (isset($post['id']))
		{
			$arRepairParts = self::getList($arUpdate['MY_CAR_ID'],$post['id']);
		}

/*
		if (isset($post['my_car']) && $post['my_car']>0)
		{
			$arUpdate['MY_CAR_ID'] = $post['my_car'];
		}

		if (isset($post['date']))
		{
			if (!$arUpdate['DATE'] = $post['date'])
			{
				unset($arUpdate['DATE']);
			}
		}

		if (isset($post['name']))
		{
			$arUpdate['NAME'] = $post['name'];
		}

		if (isset($post['storage']) && $post['storage']>0)
		{
			$arUpdate['STORAGE_ID'] = $post['storage'];
		}

		if (isset($post['catalog_number']) && strlen($post['catalog_number'])>0)
		{
			$arUpdate['CATALOG_NUMBER'] = $post['catalog_number'];
		}

		if (isset($post['number']) && $post['number']>0)
		{
			$arUpdate['NUMBER'] = $post['number'];
		}

		if (!isset($post['cost']) || $post['cost']<=0)
		{
			$arUpdate['COST'] = 0;
		}
		else
		{
			$arUpdate['COST'] = $post['cost'];
		}

		if (isset($post['reason']) && $post['reason']>0)
		{
			$arUpdate['REASON_REPLACEMENT_ID'] = $post['reason'];
			$reasonCode = ReasonReplacement::getCodeById($arUpdate['REASON_REPLACEMENT_ID']);
			switch ($reasonCode)
			{
				case 'ts':
					if (isset($post['reason_ts']) && $post['reason_ts']>0)
					{
						$arUpdate['TS_ID'] = $post['reason_ts'];
					}
					break;
				case 'breakdown':
					if (isset($post['reason_breakdown']) && $post['reason_breakdown']>0)
					{
						$arUpdate['REPAIR_ID'] = $post['reason_breakdown'];
					}
					break;
				case 'tuning':
					if (isset($post['reason_tuning']) && $post['reason_tuning']>0)
					{
						$arUpdate['REPAIR_ID'] = $post['reason_tuning'];
					}
					break;
				case 'upgrade':
					if (isset($post['reason_upgrade']) && $post['reason_upgrade']>0)
					{
						$arUpdate['REPAIR_ID'] = $post['reason_upgrade'];
					}
					break;
				case 'tire':
					break;
				case 'accident':
					if (isset($post['reason_dtp']) && $post['reason_dtp']>0)
					{
						$arUpdate['ACCIDENT_ID'] = $post['reason_dtp'];
					}
					break;
				default:
					return false;
			}
		}

		if (isset($post['who_paid']) && $post['who_paid']>0)
		{
			$arUpdate['WHO_PAID_ID'] = $post['who_paid'];
		}

		if (!isset($post['odo']) || $post['odo']<=0)
		{
			$arUpdate['ODO'] = 0;
		}
		else
		{
			$arUpdate['ODO'] = $post['odo'];
		}

		if (isset($post['start_point']) && $post['start_point']>0)
		{
			$arUpdate['POINTS_ID'] = $post['start_point'];
		}
		else
		{
			if (isset($post['newpoint_address']) || (isset($post['newpoint_lat']) && isset($post['newpoint_lon'])))
			{
				if (!$arUpdate['POINTS_ID'] = Points::createPointFromForm($post,'start','service'))
				{
					unset($arUpdate['POINTS_ID']);
				}
			}
		}
		if (isset($post['comment']) && strlen($post['comment'])>0)
		{
			$arUpdate['DESCRIPTION'] = $post['comment'];
		}
*/

		foreach ($arUpdate as $field=>$value)
		{
			if (!isset($arRepairParts[$field]) || ($arRepairParts[$field] == $arUpdate[$field]))
			{
				unset($arUpdate[$field]);
			}
		}

		if (!empty($arUpdate))
		{
			$arUpdate['ID'] = $post['id'];
			$res = self::updateDB($arUpdate);
			if ($res===false)
			{
				return false;
			}
			elseif ($res->getResult())
			{
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

	/**
	 * Возвращает таблицу всех запчастей для автомобиля
	 *
	 * @api
	 *
	 * @param null|int $carID ID автомобиля, если null - будет выбран автомобиль по-умолчанию
	 *
	 * @uses MyCar::getDefaultCarID
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

			CoreLib\Webix::showDataTable($arData);
			return true;
		}
		else
		{
			echo 'Нет записей о приобретенных запчастях';
			return false;
		}
	}

	/**
	 * Возвращает информацию по расходам на запчасти, либо на узаканную запчасть
	 *
	 * @api
	 *
	 * @param null|int  $carID  ID автомобиля
	 * @param null|int  $getID  ID записи
	 * @param int       $limit  Лимит записей
	 * @param int       $offset Смещение
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses RepairParts::$arTableFields
	 * @uses RepairPartsTable::getList
	 *
	 * @return array|bool
	 */
	public static function getList ($carID=null,$getID=null,$limit=0,$offset=0)
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
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Удаляет запись о приобретенных запчастях
	 *
	 * @api
	 *
	 * @param int $repairPartsID   ID записи
	 *
	 * @uses RepairPartsTable::getTableName
	 * @uses RepairPartsTable::getMapArray
	 * @uses RepairPartsTable::getTableLinks
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не указан ID удаляемой записи
	 *
	 * @return bool
	 */
	public static function deleteRecord($repairPartsID=null)
	{
		try
		{
			if (is_null($repairPartsID))
			{
				throw new Exception\ArgumentNullException('$repairPartsID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeDeleteRepairParts'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$repairPartsID));
				}
			}
		}

		$query = new Query('delete');
		$query->setDeleteParams(
			$repairPartsID,
			null,
			Tables\RepairPartsTable::getTableName(),
			Tables\RepairPartsTable::getMapArray(),
			Tables\RepairPartsTable::getTableLinks()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteRepairParts'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($repairPartsID,true));
					}
				}
			}

			return true;
		}
		else
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterDeleteRepairParts'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($repairPartsID,false));
					}
				}
			}

			return false;
		}
	}

	/**
	 * Добавляет значения о новой запчасти в базу
	 *
	 * @param null|array $arAdd Массив обработанных данных
	 *
	 * @uses RepairPartsTable::getTableName
	 * @uses RepairPartsTable::getMapArray
	 * @uses Errors::addError
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив POST данных
	 *
	 * @return bool|int
	 */
	protected static function addDB ($arAdd=null)
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

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeAddNewRepairParts'))
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
			Tables\RepairPartsTable::getTableName(),
			Tables\RepairPartsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterAddNewRepairParts'))
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
			Errors::addError('REPAIR_PARTS_ADD','Ошибка добавления данных');
			return false;
		}
	}

	/**
	 * Обновляет данные о приобретенных запчастях в DB из обработанного массива
	 *
	 * @param array $arUpdate  Массив изменений
	 *
	 * @uses RepairPartsTable::getTableName
	 * @uses RepairPartsTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @throws Exception\ArgumentNullException Если не передан массив изменяемых полей
	 * @throws Exception\ArgumentTypeException Если передан не массив
	 *
	 * @return bool|CoreLib\DBResult
	 */
	protected static function updateDB ($arUpdate=null)
	{
		try
		{
			if (is_null($arUpdate))
			{
				throw new Exception\ArgumentNullException('$arUpdate');
			}
			elseif (!is_array($arUpdate))
			{
				throw new Exception\ArgumentTypeException('$arUpdate','array');
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

		if (isset($arUpdate['ID']))
		{
			$updateID = $arUpdate['ID'];
			unset($arUpdate['ID']);

			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeUpdateRepairParts'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array(&$arUpdate,&$updateID));
					}
				}
			}

			$query = new Query('update');
			$query->setUpdateParams(
				$arUpdate,
				$updateID,
				Tables\RepairPartsTable::getTableName(),
				Tables\RepairPartsTable::getMapArray()
			);
			$res = $query->exec();

			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterUpdateRepairParts'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($arUpdate,$updateID,$res->getResult()));
					}
				}
			}

			return $res;
		}
		else
		{
			return false;
		}
	}

}