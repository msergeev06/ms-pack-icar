<?php

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Packages\Icar\Tables\CarGearboxTable;

class CarGearbox
{
	public static function getHtmlSelect($selected=0,$name='car_gearbox')
	{
		$arValues = CarGearboxTable::getList(array(
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
		));

		if ($selected>0)
			return SelectBox($name,$arValues,'-- Выбрать --',$selected);
		else
			return SelectBox($name,$arValues,'-- Выбрать --');
	}
}