<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Lib\TableHelper;

class CarBodyTable extends DataManager {
	public static function getTableName () {
		return 'ms_icar_car_body';
	}
	public static function getTableTitle() {
        return 'Тип кузова';
    }
	public static function getTableLinks() {
		return array(
			'ID'=> array(
				'ms_icar_my_car' => 'CAR_BODY_ID'
			)
		);
	}

	public static function getMap () {
		return array(
			new Entity\IntegerField ('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID типа кузова'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\StringField ('NAME', array(
				'require' => true,
				'title' => 'Название типа кузова'
			)),
			new Entity\StringField ('CODE', array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode()",
					'column' => 'NAME'
				),
				'title' => 'Код типа кузова'
			))
		);
	}
	public static function getArrayDefaultValues () {
		return array(
			array(
				'ID' => 1,
				'NAME' => 'Седан',
				'CODE' => 'sedan',
				'SORT' => 10
			),
			array(
				'ID' => 2,
				'NAME' => 'Универсал',
				'CODE' => 'wagon',
				'SORT' => 20
			),
			array(
				'ID' => 3,
				'NAME' => 'Минивэн',
				'CODE' => 'minivan',
				'SORT' => 30
			),
			array(
				'ID' => 4,
				'NAME' => 'Хэтчбек',
				'CODE' => 'hatchback',
				'SORT' => 40
			),
			array(
				'ID' => 5,
				'NAME' => 'Вседорожник',
				'CODE' => 'suv',
				'SORT' => 50
			),
			array(
				'ID' => 6,
				'NAME' => 'Кроссовер',
				'CODE' => 'crossover',
				'SORT' => 60
			),
			array(
				'ID' => 7,
				'NAME' => 'Купе',
				'CODE' => 'coupe',
				'SORT' => 70
			),
			array(
				'ID' => 8,
				'NAME' => 'Пикап',
				'CODE' => 'pickup',
				'SORT' => 80
			),
			array(
				'ID' => 9,
				'NAME' => 'Кабриолет',
				'CODE' => 'cabriolet',
				'SORT' => 90
			),
			array(
				'ID' => 10,
				'NAME' => 'Фургон',
				'CODE' => 'van',
				'SORT' => 100
			),
			array(
				'ID' => 11,
				'NAME' => 'Автобус',
				'CODE' => 'bus',
				'SORT' => 110
			)
		);
	}
}