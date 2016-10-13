<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception;
use MSergeev\Core\Lib\SqlHelper;
use MSergeev\Packages\Icar\Tables;

class Points
{
	protected static $arPointsList=null;
	protected static $bPointsListActive=null;

	protected static $arPointTypesList=null;
	protected static $bPointTypesListActive=null;

	public static function showSelectPoints ($strBoxName='point', $strSelectedVal='null', $field1='class="typeselect"',$pointType=null)
	{
		if ($arPoints = static::getPointsList($pointType))
		{
			$arValues = array();
			foreach ($arPoints as $arPoint)
			{
				$arValues[] = array(
					'NAME' => '['.mb_substr(static::getPointTypeCodeByID($arPoint['POINT_TYPES_ID']),0,4,'utf-8').'] ('.$arPoint['ID'].') '.$arPoint['NAME'],
					'VALUE' => $arPoint['ID']
				);
			}

			return SelectBox($strBoxName,$arValues,'--- Выбрать ---',$strSelectedVal,$field1);
		}
		else
		{
			return '[Нет путевых точек]';
		}
	}

	public static function getPointsList ($types=null,$bActive=true)
	{
		$arList = array();
		if (is_null(static::$arPointsList)
			|| (!is_null(static::$arPointsList) && $bActive != static::$bPointsListActive)
		)
		{
			if ($bActive)
			{
				$arList['filter'] = array("ACTIVE" => true);
			}
			$arList['order'] = array('POPULAR'=>'DESC');
			if ($arResult = Tables\PointsTable::getList($arList))
			{
				static::$arPointsList = $arResult;
				static::$bPointsListActive = $bActive;
			}
			else
			{
				return false;
			}
		}

		$arPoints = static::$arPointsList;
		if (is_null($types))
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

	public static function getPointTypesList($bActive=true)
	{
		$arList = array();
		if (is_null(static::$arPointTypesList)
			|| (!is_null(static::$arPointTypesList) && $bActive !== static::$bPointTypesListActive)
		)
		{
			if ($bActive)
			{
				$arList['filter'] = array ('ACTIVE' => TRUE);
			}
			$arList['order'] = array ('SORT' => 'ASC');
			if ($arResult = Tables\PointTypesTable::getList ($arList))
			{
				static::$arPointTypesList = $arResult;
				static::$bPointTypesListActive = $bActive;
				return $arResult;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return static::$arPointTypesList;
		}
	}

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

		$arTypes = static::getPointTypesList();
		foreach ($arTypes as $arType)
		{
			if ($arType['ID'] == intval($typeID))
			{
				return $arType['CODE'];
			}
		}

		return false;
	}

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

		$arPointTypes = static::getPointTypesList();
		foreach ($arPointTypes as $arType)
		{
			if ($arType['CODE'] == $code)
			{
				return $arType['ID'];
			}
		}
	}

	public static function getDefaultPointTypeID ()
	{
		$arTypes = static::getPointTypes();
		foreach ($arTypes as $arType)
		{
			if ($arType['DEFAULT'])
			{
				return $arType['ID'];
			}
		}

		return false;
	}

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

		$query = new Query('insert');
		$query->setInsertParams(
			$arAdd,
			Tables\PointsTable::getTableName(),
			Tables\PointsTable::getMapArray()
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

		$arCoords = array();
		$xmlStr = @file_get_contents ("https://geocode-maps.yandex.ru/1.x/?geocode=".urlencode ($address));
		if ($xmlStr)
		{
			$xml = simplexml_load_string ($xmlStr);
			$arValues = $xml->GeoObjectCollection->featureMember;
			$arCoords["all"] = $arValues->GeoObject->Point->pos;
			list($arCoords["lon"], $arCoords["lat"]) = explode (" ", $arCoords["all"]);

			return $arCoords;
		}
		else
		{
			return false;
		}

	}

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
			$helper = new SqlHelper();
			$query = new Query ('update');
			$sql = "UPDATE ".$helper->wrapQuotes(Tables\PointsTable::getTableName())." SET ".$helper->wrapQuotes('POPULAR')." = ".$helper->wrapQuotes('POPULAR')." + 1 WHERE ID = ".$pointID;
			$query->setQueryBuildParts($sql);
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
			'filter' => array('ID'=>$pointID)
		));
		if (isset($arRes[0]['POPULAR']))
		{
			$nowPopular = $arRes[0]['POPULAR'];
		}
		else
		{
			return false;
		}

		//TODO: Проверить работу кода
		$arRes = Tables\PointsTable::getList(array(
			'select' => array('ID'),
			'filter' => array(
				'>POPULAR'=>$nowPopular,
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