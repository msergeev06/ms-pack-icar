<?php
/**
 * MSergeev\Packages\Icar\Lib\Points
 * Путевые точки
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Entity;
use MSergeev\Core\Exception;
use MSergeev\Packages\Icar\Tables;
use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Yandexmap\Lib\YandexMap;

/**
 * Class Points
 *
 * Events:
 * OnBeforeCreateNewPoint - Перед созданием путевой точки (массив параметров путевой точки)
 * OnAfterCreateNewPoint - После создания путевой точки (массив параметров путевой точки и ID добавленной точки)
 * OnBeforeGetCoordsByAddressYandex - Перед попыткой получения координат по адресу из Яндекс(строка адреса)
 * OnAfterGetCoordsByAddressYandex - После удачной попытки получения координат по адресу из Яндекс (строка адреса, массив координат)
 * OnBeforeIncreasePointPopular - Перед увеличением популярности точки (ID путевой точки)
 * OnAfterIncreasePointPopular - После увеличения популярности точки (ID путевой точки)
 */
class Points
{
	/**
	 * @var array Массив возвращаемых из таблицы полей путевых точек
	 */
	private static $arTableFields = array(
		'ID',
		'ACTIVE',
		'NAME',
		'POINT_TYPES_ID',
		'POINT_TYPES_ID.NAME' => 'POINT_TYPES_NAME',
		'POINT_TYPES_ID.CODE' => 'POINT_TYPES_CODE',
		'POINT_TYPES_ID.DEFAULT' => 'POINT_TYPES_DEFAULT',
		'ADDRESS',
		'LATITUDE',
		'LONGITUDE',
		'RADIUS',
		'POPULAR'
	);

	/**
	 * @var array Массив возвращаемых из таблицы полей типов путевых точек
	 */
	private static $arTypesTableFields = array(
		'ID',
		'ACTIVE',
		'SORT',
		'NAME',
		'CODE',
		'DEFAULT'
	);

	/**
	 * @const Радиус земли
	 */
	const EARTH_RADIUS = 6372795;

	/**
	 * @var array|null Список путевых точек
	 */
	protected static $arPointsList=null;

	/**
	 * @var bool|null Флаг активности путевых точек
	 */
	protected static $bPointsListActive=null;

	/**
	 * @var array|null Массив типов путевых точек
	 */
	protected static $arPointTypesList=null;

	/**
	 * @var bool|null Флаг активности типов путевых точек
	 */
	protected static $bPointTypesListActive=null;

	/**
	 * Возвращает <select> с существующими точками, отсортированными в порядке убывания популярности
	 *
	 * @api
	 *
	 * @param string        $strBoxName         Параметр name тега <select>
	 * @param string        $strSelectedVal     Какое значение будет отмечено (selected)
	 * @param string        $field1             Прочие параметры тега
	 * @param string|array  $pointType          Массив типов путевых точек
	 *
	 * @uses Points::getPointsList
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 * @uses SelectBox
	 *
	 * @return string
	 */
	public static function showSelectPoints ($strBoxName='point', $strSelectedVal='null', $field1='class="typeselect"',$pointType='null')
	{
		if ($arPoints = static::getPointsList($pointType))
		{
			$arValues = array();
			foreach ($arPoints as $arPoint)
			{
				$arValues[] = array(
					'NAME' => '['.mb_substr($arPoint['POINT_TYPES_CODE'],0,4,'utf-8').'] ('.$arPoint['ID'].') '.$arPoint['NAME'],
					'VALUE' => $arPoint['ID']
				);
			}
			return SelectBox($strBoxName,$arValues,CoreLib\Loc::getPackMessage('icar','all_select_default'),$strSelectedVal,$field1);
		}
		else
		{
			return '['.CoreLib\Loc::getPackMessage('icar','all_no_point').']';
		}
	}

	/**
	 * Возвращает <select> с существующими типами точек
	 *
	 * @api
	 *
	 * @param string        $strBoxName         Параметр name тега <select>
	 * @param string        $strSelectedVal     Какое значение будет отмечено (selected)
	 * @param string        $field              Прочие параметры тега
	 *
	 * @uses Points::getTypesList
	 * @uses MSergeev\Core\Lib\Loc::getPackMessage
	 *
	 * @return string
	 */
	public static function showSelectPointTypes ($strBoxName="point-type", $strSelectedVal='null', $field='class="typeselect"')
	{
		if ($arTypes = static::getTypesList())
		{
			$arValues = array();
			foreach ($arTypes as $ar_type)
			{
				$arValues[] = array(
					'NAME' => '['.$ar_type['CODE'].'] '.$ar_type['NAME'],
					'VALUE' => $ar_type['CODE']
				);
			}

			return SelectBox($strBoxName, $arValues,CoreLib\Loc::getPackMessage('icar','all_select_default'),$strSelectedVal,$field);
		}
		else
		{
			return '['.CoreLib\Loc::getPackMessage('icar','all_no_types').']';
		}
	}

	/**
	 * Возвращает список типов путевых точек
	 *
	 * @param bool $bActive Флаг: true - только активные, false - все
	 *
	 * @uses PointTypesTable::getList
	 *
	 * @return array|bool
	 */
	public static function getTypesList ($bActive=true)
	{
		$arParams = array(
			'select' => self::$arTypesTableFields,
			'order' => array('SORT'=>'ASC')
		);
		if ($bActive)
		{
			$arParams['filter'] = array('ACTIVE'=>true);
		}

		return Tables\PointTypesTable::getList($arParams);
	}

	/**
	 * Возвращает массив путевых точек
	 *
	 * @api
	 *
	 * @param string|array  $types      Тип путевой точки или массив значений
	 * @param bool          $bActive    Флаг, если true - только активные точки
	 *
	 * @uses PointsTable::getList
	 * @uses Points::getPointTypeIdByCode
	 *
	 * @return array|bool
	 */
	public static function getPointsList ($types='null',$bActive=true)
	{
		$arList = array();
		//if (is_null(static::$arPointsList)
		//	|| (!is_null(static::$arPointsList) && $bActive != static::$bPointsListActive)
		//)
		//{
			if ($bActive)
			{
				$arList['filter'] = array("ACTIVE" => true);
			}
			$arList['order'] = array('POPULAR'=>'DESC');
			$arList['select'] = self::$arTableFields;
			if ($arResult = Tables\PointsTable::getList($arList))
			{
				static::$arPointsList = $arResult;
				//static::$bPointsListActive = $bActive;
			}
			else
			{
				return false;
			}
		//}

		$arPoints = static::$arPointsList;
		//msDebug($types);
		//msDebug($arPoints);
		if ($types=='null' || is_null($types))
		{
			return $arPoints;
		}
		else
		{
			$arPointsTemp = $arPoints;
			$arPoints = array();
			$arTypes = array();
			if (is_array($types))
			{
				foreach ($types as $pointType)
				{
					$arTypes[] = intval(static::getPointTypeIdByCode($pointType));
				}
			}
			else
			{
				$arTypes[] = intval(static::getPointTypeIdByCode($types));
			}
			foreach ($arPointsTemp as $point)
			{
				if (in_array(intval($point['POINT_TYPES_ID']),$arTypes))
				{
					$arPoints[] = $point;
				}
			}
			if (!empty($arPoints))
			{
				return $arPoints;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Возвращает информацию по заданной точке
	 *
	 * @api
	 *
	 * @param int $pointID ID путевой точки
	 *
	 * @uses PointsTable::getList
	 *
	 * @return array|bool
	 */
	public static function getPointInfo ($pointID)
	{
		$pointID = intval($pointID);
		$arRes = Tables\PointsTable::getList(array(
			'select' => static::$arTableFields,
			'filter' => array('ID'=>$pointID),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		return $arRes;
	}

	/**
	 * Возвращает код типа путевой точки по его ID
	 *
	 * @api
	 *
	 * @param null|int  $typeID ID типа путевой точки
	 *
	 * @uses PointTypesTable::getList
	 *
	 * @throws Exception\ArgumentNullException Если не задан ID типа путевой точки
	 *
	 * @return bool|string
	 */
	public static function getPointTypeCodeByID ($typeID=null)
	{
		try
		{
			if (is_null($typeID))
			{
				throw new Exception\ArgumentNullException('typeID');
			}

		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arRes = Tables\PointTypesTable::getList(array(
			'select' => array('CODE'),
			'filter' => array('ID'=>intval($typeID)),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if ($arRes)
		{
			return $arRes['CODE'];
		}

		return false;
	}

	/**
	 * Возвращает ID типа путевых точек по его коду
	 *
	 * @api
	 *
	 * @param null|string $code Код типа путевых точек
	 *
	 * @uses PointTypesTable::getList
	 *
	 * @throws Exception\ArgumentNullException Если не передан код типа путевых точек
	 *
	 * @return bool||int
	 */
	public static function getPointTypeIdByCode ($code=null)
	{
		try
		{
			if (is_null($code))
			{
				throw new Exception\ArgumentNullException('pointTypeCode');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arRes = Tables\PointTypesTable::getList(array(
			'select' => array('ID'),
			'filter' => array('CODE'=>$code),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}

		if ($arRes)
		{
			return $arRes['ID'];
		}

		return false;
	}

	/**
	 * Возвращает ID типа путевых точек по-умолчанию
	 *
	 * @api
	 *
	 * @uses PointTypesTable::getList
	 *
	 * @return bool|int
	 */
	public static function getDefaultPointTypeID ()
	{
		$arRes = Tables\PointTypesTable::getList(array(
			'select' => array('ID'),
			'filter' => array('DEFAULT'=>true),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if ($arRes)
		{
			return $arRes['ID'];
		}

		return false;
	}

	/**
	 * Создает новую путевую точку
	 *
	 * @api
	 *
	 * @param array $arPoint Массив параметров путевой точки
	 *
	 * @uses Points::getDefaultPointTypeID
	 * @uses Points::getCoordsByAddressYandex
	 * @uses PointsTable::getTableName
	 * @uses PointsTable::getMapArray
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Entity\Query
	 * @uses MSergeev\Core\Lib\DBResult
	 *
	 * @return bool|int
	 */
	public static function createNewPoint ($arPoint=array())
	{
		if (!isset($arPoint['NAME']))
		{
			$arPoint['NAME'] = 'ПТ';
			$arPoint['AUTO'] = true;
		}
		else
		{
			$arPoint['AUTO'] = false;
		}
		if (!isset($arPoint['TYPE']))
		{
			$arPoint['TYPE'] = static::getDefaultPointTypeID();
		}
		if (
			(!isset($arPoint['LON']) || strlen($arPoint['LON'])<2)
			|| (!isset($arPoint['LAT']) || strlen($arPoint['LAT'])<2)
		)
		{
			if (isset($arPoint['ADDRESS']) && strlen($arPoint['ADDRESS'])>3)
			{
				if ($arCoords = static::getCoordsByAddressYandex($arPoint['ADDRESS']))
				{
					$arPoint['LON'] = $arCoords['lon'];
					$arPoint['LAT'] = $arCoords['lat'];
				}
				else
				{
					//TODO:Вывод ошибки о том, что данные не добавлены, так как нет ответа яндекс
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		if ($arPoint['AUTO'])
		{
			$arPoint['NAME'] .= ' ('.$arPoint['LON'].', '.$arPoint['LAT'].')';
		}

		$arAdd[] = array(
			'NAME' => $arPoint['NAME'],
			'POINT_TYPES_ID' => $arPoint['TYPE'],
			'ADDRESS' => $arPoint['ADDRESS'],
			'LATITUDE' => $arPoint['LAT'],
			'LONGITUDE' => $arPoint['LON']
		);

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeCreateNewPoint'))
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
			Tables\PointsTable::getTableName(),
			Tables\PointsTable::getMapArray()
		);
		$res = $query->exec();
		if ($res->getResult())
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterCreateNewPoint'))
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
	 * Возвращает координаты для точки по ее адресу. Использует сервис Яндекс
	 *
	 * @api
	 *
	 * @param null|string $address Адрес путевой точки
	 *
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 *
	 * @throw Exception\ArgumentNullException Если не указан адрес
	 *
	 * @return array|bool
	 */
	public static function getCoordsByAddressYandex ($address=null)
	{
		try
		{
			if (is_null($address) || strlen($address)<5)
			{
				throw new Exception\ArgumentNullException('address');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeGetCoordsByAddressYandex'))
		{
			foreach ($arEvents as $sort=>$ar_events)
			{
				foreach ($ar_events as $arEvent)
				{
					CoreLib\Events::executePackageEvent($arEvent,array(&$address));
				}
			}
		}

		$arCoords = array();
		$xmlStr = @file_get_contents ("https://geocode-maps.yandex.ru/1.x/?geocode=".urlencode ($address));
		if ($xmlStr)
		{
			$xml = simplexml_load_string ($xmlStr);
			$arValues = $xml->GeoObjectCollection->featureMember;
			$arCoords["all"] = $arValues->GeoObject->Point->pos;
			list($arCoords["lon"], $arCoords["lat"]) = explode (" ", $arCoords["all"]);

			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterGetCoordsByAddressYandex'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array($address,$arCoords));
					}
				}
			}

			return $arCoords;
		}
		else
		{
			return false;
		}

	}

	/**
	 * Увеличивает популярность точки, если необходимо
	 *
	 * @api
	 *
	 * @param null|int $pointID ID путевой точки
	 *
	 * @uses Points::checkNeedIncreasePointPopular
	 * @uses PointsTable::getTableName
	 * @uses MSergeev\Core\Lib\Events::getPackageEvents
	 * @uses MSergeev\Core\Lib\Events::executePackageEvent
	 * @uses MSergeev\Core\Lib\SqlHelper
	 * @uses MSergeev\Core\Entity\Query
	 *
	 * @throws Exception\ArgumentNullException Если не указан ID путевой точки
	 *
	 * @return bool
	 */
	public static function increasePointPopular ($pointID=null)
	{
		try
		{
			if (is_null($pointID))
			{
				throw new Exception\ArgumentNullException('pointID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		if (static::checkNeedIncreasePointPopular($pointID))
		{
			if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnBeforeIncreasePointPopular'))
			{
				foreach ($arEvents as $sort=>$ar_events)
				{
					foreach ($ar_events as $arEvent)
					{
						CoreLib\Events::executePackageEvent($arEvent,array(&$pointID));
					}
				}
			}

			$helper = new CoreLib\SqlHelper(Tables\PointsTable::getTableName());
			$query = new Query ('update');
			$sql = "UPDATE\n\t"
				.$helper->wrapTableQuotes()."\n"
				."SET\n\t"
				.$helper->wrapFieldQuotes('POPULAR')." = "
				.$helper->wrapFieldQuotes('POPULAR')." + 1\n"
				."WHERE\n\t"
				.$helper->wrapFieldQuotes('ID')." = ".$pointID;
			$query->setQueryBuildParts($sql);
			$res = $query->exec();
			if ($res->getResult())
			{
				if ($arEvents = CoreLib\Events::getPackageEvents('icar','OnAfterIncreasePointPopular'))
				{
					foreach ($arEvents as $sort=>$ar_events)
					{
						foreach ($ar_events as $arEvent)
						{
							CoreLib\Events::executePackageEvent($arEvent,array($pointID));
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

	/**
	 * Создает путевую точку из данных формы
	 *
	 * @api
	 * TODO: Проверить правильность работы функции, в связи с добавлением поля "Тип путевой точки"
	 *
	 * @param array     $post  Массив POST данных
	 * @param string    $name  Название точки ('start' или 'end')
	 * @param string    $type  Тип путевой точки
	 *
	 * @uses Points::getPointTypeIdByCode
	 * @uses Points::createNewPoint
	 *
	 * @return bool|int
	 */
	public static function createPointFromForm ($post=array(), $name='start', $type='waypoint')
	{
		$arPoint = array();
		if (isset($post[$name.'_name']) && strlen($post[$name.'_name'])>3)
		{
			$arPoint['NAME'] = $post[$name.'_name'];
		}
		if (isset($post[$name.'_address']) && strlen($post[$name.'_address'])>5)
		{
			$arPoint['ADDRESS'] = $post[$name.'_address'];
		}
		if (
			(isset($post[$name.'_lat']) && strlen($post[$name.'_lat'])>2)
			&& (isset($post[$name.'_lon']) && strlen($post[$name.'_lon'])>2)
		)
		{
			$arPoint['LON'] = $post[$name.'_lon'];
			$arPoint['LAT'] = $post[$name.'_lat'];
		}
		$arPoint['TYPE'] = self::getPointTypeIdByCode($type);

		return self::createNewPoint($arPoint);
	}

	/**
	 * Выводит таблицу со списком путевых точек
	 *
	 * @api
	 *
	 * @uses Points::getPointsList
	 * @uses IcarWebixHelper
	 * @uses MSergeev\Core\Lib\Tools::getSitePath
	 * @uses MSergeev\Core\Lib\Tools::cropString
	 * @uses MSergeev\Core\Lib\Loader::getTemplate
	 * @uses MSergeev\Core\Lib\Webix::showDataTable
	 *
	 * @return bool
	 */
	public static function showListTable ()
	{
		$arList = self::getPointsList('null', false);
		//msDebug($arList);
		$bYandex = false;
		if (CoreLib\Loader::issetPackage('yandexmap'))
		{
			$bYandex = CoreLib\Loader::IncludePackage('yandexmap');
		}

		if ($arList)
		{
			echo '<div id="tsList"></div><div id="tsPager"></div>';

			$imgSrcPath = CoreLib\Tools::getSitePath(CoreLib\Loader::getTemplate('icar')."images/");
/*
		'ID',
		'NAME',
		'POINT_TYPES_ID',
		'POINT_TYPES_ID.NAME' => 'POINT_TYPES_NAME',
		'POINT_TYPES_ID.CODE' => 'POINT_TYPES_CODE',
		'POINT_TYPES_ID.DEFAULT' => 'POINT_TYPES_DEFAULT',
		'ADDRESS',
		'LATITUDE',
		'LONGITUDE',
		'POPULAR'
 */
			$arDatas = array();
			msDebug($arList);
			foreach ($arList as $list)
			{
				//$arInfo = array(
				$arDatas[] = array(
					'id' => $list['ID'],
					'active' => ($list['ACTIVE'])?CoreLib\Loc::getPackMessage('icar','all_yes'):CoreLib\Loc::getPackMessage('icar','all_no'),
					'name' => (!is_null($list['NAME']))?addslashes($list['NAME']):'',
					'point_type_name' => (!is_null($list['POINT_TYPES_NAME']))?addslashes($list['POINT_TYPES_NAME']):'',
					'address' => !is_null($list['ADDRESS'])?addslashes(CoreLib\Tools::cropString($list['ADDRESS'])):'',
					'address_full' => !is_null($list['ADDRESS'])?addslashes($list['ADDRESS']):'',
					'latitude' => !is_null($list['LATITUDE'])?$list['LATITUDE']:'',
					'longitude' => !is_null($list['LONGITUDE'])?$list['LONGITUDE']:'',
					'popular' => intval($list['POPULAR']),
					'radius' => intval($list['RADIUS']),
					'yandex_map' => (!is_null($list['LONGITUDE']) && !is_null($list['LATITUDE']))
						?addslashes((($bYandex)
							?YandexMap::showImgPoint($list['LATITUDE'],$list['LONGITUDE'],$list['RADIUS'],600,450)
							:'')
						)
						:'',
					'info' => (!is_null($list['INFO']))?((strlen($list['INFO'])>0)?"<img src='".$imgSrcPath."info.png'>":""):'',
					'comment' => (!is_null($list['INFO']))?addslashes($list['INFO']):'',
					'edit' => "<a class='table_button' href='edit.php?id=".$list['ID']."'><img src='".$imgSrcPath."edit.png'></a>",
					'delete' => "<a class='table_button' href='delete.php?id=".$list['ID']."'><img src='".$imgSrcPath."delete.png'></a>"
				);
				//$arDatas[] = $arInfo;
			}
			//msDebug($arDatas);

			$webixHelper = new IcarWebixHelper();

			$webixHelper->addFunctionSortByTimestamp();

			$arData = array(
				'grid' => 'tsGrid',
				'container' => 'tsList',
				'footer' => true,
				'tooltip' => true,
				'pager' => array('container'=>'tsPager'),
				'columns' => array(
					$webixHelper->getColumnArray('INT',array(
						'id' => 'id',
						'header' => 'ID',
						'tooltip' => '#yandex_map#'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'active',
						'header' => 'Активна'
					)),
					$webixHelper->getColumnArray('NAME'),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'point_type_name',
						'header' => 'Тип'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'address',
						'header' => 'Адрес',
						'tooltip' => '#address_full#'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'longitude',
						'header' => 'Широта'
					)),
					$webixHelper->getColumnArray('STRING',array(
						'id' => 'latitude',
						'header' => 'Долгота'
					)),
					$webixHelper->getColumnArray('INT',array(
						'id' => 'popular',
						'header' => 'Популярность'
					)),
					$webixHelper->getColumnArray('INT',array(
						'id' => 'radius',
						'header' => 'Радиус'
					)),
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
			echo CoreLib\Loc::getPackMessage('icar','points_no_points');
			return false;
		}
	}

	/**
	 * Возвращает расстояние в метрах между двумя точками (по координатам точек)
	 *
	 * @param array $arPoint1 Массив данных путевой точки 1
	 * @param array $arPoint2 Массив данных путевой точки 2
	 *
	 * @uses Points::EARTH_RADIUS
	 *
	 * @return float
	 */
	public static function calculateDistanceBetween2Point (array $arPoint1, array $arPoint2)
	{
		if (
			!isset($arPoint1['LATITUDE']) ||
			!isset($arPoint2['LATITUDE']) ||
			!isset($arPoint1['LONGITUDE']) ||
			!isset($arPoint2['LONGITUDE'])
		)
		{
			return false;
		}

		// перевести координаты в радианы
		$lat1 = $arPoint1['LATITUDE'] * M_PI / 180;
		$lon1 = $arPoint1['LONGITUDE'] * M_PI / 180;
		$lat2 = $arPoint2['LATITUDE'] * M_PI / 180;
		$lon2 = $arPoint2['LONGITUDE'] * M_PI / 180;

		// косинусы и синусы широт и разницы долгот
		$cl1 = cos($lat1);
		$cl2 = cos($lat2);
		$sl1 = sin($lat1);
		$sl2 = sin($lat2);
		$delta = $lon2 - $lon1;
		$cdelta = cos($delta);
		$sdelta = sin($delta);

		// вычисления длины большого круга
		$y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
		$x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

		$ad = atan2($y, $x);
		$dist = $ad * self::EARTH_RADIUS;

		return $dist;
	}

	/**
	 * Возвращает массив данных точки, которая находится ближе всего к заданным координатам
	 *
	 * Используется в мобильной версии
	 *
	 * @api
	 *
	 * @param string|float  $lat        Широта места
	 * @param string|float  $lon        Долгота места
	 * @param string|array  $pointType  Тип или массив типов путевых точек
	 * @param bool|float    $dist       Дистанция, на которой считается, что путевая точка рядом. Если не указано,
	 *                                  расстояние берется из данных точки
	 *
	 * @uses Points::getPointsList
	 * @uses Points::calculateDistanceBetween2Point
	 *
	 * @return array|bool
	 */
	public static function getPointNear ($lat, $lon, $pointType='null', $dist=false)
	{
		//msDebug($pointType);

		if ($pointType!='null' && !is_null($pointType))
		{
			if (!is_array($pointType))
			{
				$pointType = array($pointType);
			}
		}
		else
		{
			$pointType = null;
		}
		$arPoints = self::getPointsList($pointType,false);
		//msDebug($arPoints);
		if ($arPoints)
		{
			foreach ($arPoints as $ar_point)
			{
				$pointDist = self::calculateDistanceBetween2Point(
					array('LATITUDE'=>$lat,'LONGITUDE'=>$lon),
					$ar_point
				);
				if ($dist!==false)
				{
					$checkDist = $dist;
				}
				else
				{
					$checkDist = $ar_point['RADIUS'];
				}
				if ($pointDist!==false && $pointDist<=$checkDist)
				{
					return $ar_point;
				}
			}
		}

		return false;
	}

	/**
	 * Получает данных GPS из строки
	 *
	 * @api
	 *
	 * Используется в мобильной версии
	 *
	 * Строка вида: "[[LATITUDE1],[LONGITUDE1]]|[%LOC]/[[LATITUDE2],[LONGITUDE2]]|[%LOCN]"
	 * где LATITUDE1 и LONGITUDE1 - коодинаты по GPS,
	 * а LATITUDE2 и LONGITUDE2 - координаты по сети мобильных данных
	 * Вместо координат могут быть установлены значения %LOC и %LOCN соответственно.
	 * Это означает, что координаты по GPS или по моб. сети соответственно не получены
	 *
	 * @param string        $strGPS     Строка GPS координат указанного формата
	 * @param string|array  $pointType  Символьный код или массив кодов типов путевых точек
	 *
	 * @uses Points::getPointNear
	 *
	 * @return array|bool
	 */
	public static function getGpsFromString ($strGPS,$pointType='null')
	{
		list($lat,$lon) = explode(',',$strGPS);
		return self::getPointNear($lat,$lon,$pointType,false);



/*		list($gps,$net) = explode('/',$strGPS);
		if ($gps!='%LOC')
		{
			list($lat,$lon) = explode(',',$gps);
			return self::getPointNear($lat,$lon,$pointType,false);
		}
		elseif ($net!='%LOCN')
		{
			list($lat,$lon) = explode(',',$net);
			return self::getPointNear($lat,$lon,$pointType,false);
		}
		return false;*/
	}

	/**
	 * Проверяет необходимость увеличения популярности путевой точки
	 *
	 * @param int $pointID ID путевой точки
	 *
	 * @uses PointsTable::getList
	 *
	 * @throws Exception\ArgumentNullException Если ID путевой точки не указано
	 *
	 * @return bool
	 */
	protected static function checkNeedIncreasePointPopular ($pointID=null)
	{
		try
		{
			if (is_null($pointID))
			{
				throw new Exception\ArgumentNullException('pointID');
			}
		}
		catch (Exception\ArgumentNullException $e)
		{
			$e->showException();
			return false;
		}

		$arRes = Tables\PointsTable::getList(array(
			'select' => array('POPULAR'),
			'filter' => array('ID'=>$pointID),
			'limit' => 1
		));
		if ($arRes && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if (isset($arRes['POPULAR']))
		{
			$nowPopular = $arRes['POPULAR'];
		}
		else
		{
			return false;
		}

		//TODO: Проверить работу кода
		$arRes = Tables\PointsTable::getList(array(
			'select' => array('ID'),
			'filter' => array(
				'>=POPULAR'=>$nowPopular,
				'<>ID' => $pointID
			)
		));

/*		$helper = new SqlHelper();
		$query = new Query('select');
		$sql = 'SELECT '.$helper->wrapQuotes('ID').' FROM '
			.$helper->wrapQuotes(Tables\PointsTable::getTableName())
			.' WHERE '.$helper->wrapQuotes('POPULAR').' >= '.$nowPopular.' AND '
			.$helper->wrapQuotes('ID').' <> '.$pointID;
		$query->setQueryBuildParts($sql);
		$query->setTableMap(Tables\PointsTable::getMapArray());
		$query->setTableName(Tables\PointsTable::getTableName());
		$res = $query->exec();*/
		//if ($ar_res = $res->fetch())
		if ($arRes)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}