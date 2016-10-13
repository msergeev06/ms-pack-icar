<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\AccidentTable;

class Accident
{
	public static function showSelectAccidentList ($carID, $strBoxName, $strDetText='Не выбрано', $strSelectedVal = "null", $field1="class=\"accidentlistselect\"")
	{
		$arRes = self::getAccidentList($carID);
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


	public static function getAccidentList ($carID=null, $getID=null,$limit=0,$offset=0)
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
			),
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

		return $arRes;
	}
}