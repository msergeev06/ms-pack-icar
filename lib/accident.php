<?php
/**
 * MSergeev\Packages\Icar\Lib\Accident
 * Раздел ДТП
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\AccidentTable;
use MSergeev\Core\Lib\Loc;

/**
 * Class Accident
 *
 * @static
 */
class Accident
{
	/**
	 * @var array Массив получаемых полей из базы
	 * @private
	 * @static
	 */
	private static $arAccidentFields = array(
		'ID',
		'MY_CAR_ID',
		'MY_CAR_ID.NAME' => 'MY_CAR_NAME',
		'MY_CAR_ID.CAR_NUMBER' => 'MY_CAR_NUMBER',
		'ACCIDENT_DATE',
		'ODO',
		'YOU_INSURANCE_ID',
		'YOU_INSURANCE_ID.NAME' => 'YOU_INSURANCE_NAME',
		'SECOND_INSURANCE_ID',
		'SECOND_INSURANCE_ID.NAME' => 'SECOND_INSURANCE_NAME',
		'DAMAGE_PARTS',
		'EXECUTOR_ID',
		'EXECUTOR_ID.NAME' => "EXECUTOR_NAME",
		'EXECUTOR_ID.CODE' => "EXECUTOR_CODE",
		'WHO_PAID_ID',
		'WHO_PAID_ID.NAME' => 'WHO_PAID_NAME',
		'WHO_PAID_ID.CODE' => 'WHO_PAID_CODE',
		'INSURANCE_PAID',
		'POINTS_ID',
		'POINTS_ID.NAME' => 'POINT_NAME',
		'POINTS_ID.LATITUDE' => 'POINT_LATITUDE',
		'POINTS_ID.LONGITUDE' => 'POINT_LONGITUDE',
		'POINTS_ID.POINT_TYPES_ID' => 'POINT_TYPE_ID',
		'POINTS_ID.POINT_TYPES_ID.NAME' => 'POINT_TYPE_NAME',
		'DESCRIPTION' => 'INFO'
	);

	/**
	 * Возвращает тег <select> со списком произошедших ДТП
	 *
	 * @api
	 *
	 * @param int    $carID             ID автомобиля
	 * @param string $strBoxName        Название тега <select>
	 * @param string $strDetText        Текст нулевого значения тега <select>
	 * @param string $strSelectedVal    Нулевое значение тега <select>
	 * @param string $field1            Дополнительные данные тега <select>
	 *
	 * @uses Accident::getList
	 * @uses Msergeev\Core\Lib\Loc::getPackMessage
	 * @uses SelectBox Функция вывода тега <select>
	 *
	 * @return string
	 */
	public static function showSelectAccidentList ($carID, $strBoxName, $strDetText='', $strSelectedVal = "null", $field1="class=\"accidentlistselect\"")
	{
		if ($strDetText == '')
		{
			$strDetText = Loc::getPackMessage('icar','dtp_not_select');
		}
		$arRes = self::getList($carID);
		if ($arRes)
		{
			$arValue = array();
			foreach ($arRes as $ar_res)
			{
				$arValue[] = array(
					'NAME' => $ar_res['DATE'].' '.substr($ar_res['INFO'],0,20),
					'VALUE' => $ar_res['ID']
				);
			}

			return SelectBox($strBoxName, $arValue, $strDetText, $strSelectedVal, $field1);
		}
	}

	/**
	 * Возвращает массив, содержащий список произошедших ДТП, либо false
	 *
	 * @api
	 *
	 * @param int|null  $carID      ID автомобиля, если null берется автомобиль по-умолчанию
	 * @param int|null  $getID      ID записи о ДТП, либо null, если нужны все
	 * @param int       $limit      Количество возвращаемых строк
	 * @param int       $offset     Начиная с какой по счету записи возвращать
	 *
	 * @uses MyCar::getDefaultCarID
	 * @uses AccidentTable::getList
	 *
	 * @return array|bool   Массив со списком ДТП, либо false
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
			'select' => self::$arAccidentFields,
			'filter' => $arFilter,
			'order' => array(
				'ACCIDENT_DATE' => 'ASC',
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

		$arRes = AccidentTable::getList($arList);
		if ($arRes && intval($arList['limit'])==1 && isset($arRes[0]))
		{
			$arRes = $arRes[0];
		}
		if ($arRes && !isset($arRes[0]))
		{
			$arRes = array($arRes);
		}

		return $arRes;
	}
}