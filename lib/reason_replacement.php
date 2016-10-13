<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\ReasonReplacementTable;

class ReasonReplacement
{
	public static function showSelectReasonReplacementList ($strBoxName, $strDetText='--- Выбрать ---', $strSelectedVal = "null", $field1="class=\"reasonreplacementselect\"")
	{
		$arRes = ReasonReplacementTable::getList(array(
			'select' => array('ID'=>'VALUE','NAME'),
			'filter' => array('ACTIVE'=>true),
			'order' => array('SORT'=>'ASC')
		));

		return SelectBox($strBoxName, $arRes, $strDetText, $strSelectedVal, $field1);
	}

	public static function getCodeById ($reasonID=0)
	{
		if (intval($reasonID)>0)
		{
			$arRes = ReasonReplacementTable::getList(array(
				'select' => array('CODE'),
				'filter' => array('ID'=>intval($reasonID)),
				'limit' => 1
			));
			if ($arRes)
			{
				return $arRes[0]['CODE'];
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}

	}

}