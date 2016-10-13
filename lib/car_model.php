<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Entity\Query;
use MSergeev\Core\Exception\ArgumentNullException;
use MSergeev\Packages\Icar\Tables\CarModelTable;

class CarModel
{
	public static function getHtmlSelect($brand=null, $selected=0, $name='car_model')
	{
		$arGetList = array(
			'select' => array(
				'ID' => 'VALUE',
				'NAME'
			),
			'filter' => array(
				'ACTIVE' => true
			),
			'order' => array(
				'SORT' => 'ASC',
				'NAME' => 'ASC'
			)
		);
		if (!is_null($brand))
		{
			$arGetList['filter']['BRANDS_ID'] = intval($brand);
		}
		$arValues = CarModelTable::getList($arGetList);

		if ($selected>0)
			return SelectBox($name,$arValues,'-- Выбрать --',$selected);
		else
			return SelectBox($name,$arValues,'-- Выбрать --');
	}

	public static function addNewModel ($brandID=null, $modelName=null)
	{
		try
		{
			if (is_null($modelName))
			{
				throw new ArgumentNullException('model_name');
			}
			else
			{
				$modelName = htmlspecialchars($modelName);
			}

			if (is_null($brandID))
			{
				throw new ArgumentNullException('brandID');
			}

			$arInsert[0] = array(
				'BRANDS_ID' => $brandID,
				'NAME' => $modelName
			);
			$query = new Query('insert');
			$query->setInsertParams(
				$arInsert,
				CarModelTable::getTableName(),
				CarModelTable::getMapArray()
			);
			$res = $query->exec();

			if ($res->getResult())
			{
				return $res->getInsertId();
			}

		}
		catch (ArgumentNullException $e)
		{
			$e->showException();
		}
	}

}