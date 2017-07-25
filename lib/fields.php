<?php
/**
 * MSergeev\Packages\Icar\Lib\Fields
 * Поля. Содержит функции показа и обработки полей форм
 *
 * @package MSergeev\Packages\Icar
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2016 Mikhail Sergeev
 */

namespace MSergeev\Packages\Icar\Lib;

use MSergeev\Core\Lib as CoreLib;
use MSergeev\Packages\Yandexmap\Lib\YandexMap;

/**
 * Class Fields
 * @package MSergeev\Packages\Icar\Lib
 */
class Fields
{
	/**
	 * Возвращает поле формы выбора Автомобиля
	 *
	 * @api
	 *
	 * @param int    $carID     Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarIdField ($carID=0, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_car');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.MyCar::showSelectCars("my_car",$carID,'class="myCar form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает скрытое поле формы с ID автомобиля
	 *
	 * @param int $carID ID автомобиля
	 *
	 * @return string
	 */
	public static function showCarIdHiddenField ($carID=0)
	{
		$echo = "<input type=\"hidden\" name=\"car_id\" value=\"".$carID."\">";

		return $echo;
	}

	/**
	 * Возвращает поле формы Название автомобиля
	 *
	 * @api
	 *
	 * @param string $carName   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarNameField ($carName='', $fieldName='')
	{
		if ($fieldName == '')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_name');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_name\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputText('car_name',$carName,'class="car_name form-control"')."</div></div>";

		/*
		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputText('car_name',$carName,'class="car_name"')."</td><td>&nbsp;</td></tr>";
		*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Активность
	 *
	 * @api
	 *
	 * @param bool   $carActive Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarActiveField ($carActive=true, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_active');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_active\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\SelectBoxBool('car_active',(($carActive) ? 1 : 0),'','','class="car_active form-control"')."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\SelectBoxBool('car_active',(($carActive) ? 1 : 0))."</td><td>&nbsp;</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Сортировка
	 *
	 * @api
	 *
	 * @param int    $carSort   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarSortField ($carSort=500, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_sort');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_sort\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_sort',$carSort,1,false,1,'class="car_sort form-control"')."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_sort',$carSort,1,false,1,'class="car_sort"')."</td><td>&nbsp;</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поля формы "Бренд", "Модель" и "Название новой модели" автомобиля
	 *
	 * @api
	 *
	 * @param int    $brandID           Значение по-умолчанию для поля Бренд
	 * @param int    $modelID           Значение по-умолчанию для поля Модель
	 * @param string $brandFieldName    Альтернативное название поля Бренд
	 * @param string $modelFieldName    Альтернативное название поля Модель
	 * @param string $modelAddFieldName Альтернативное название поля Название новой модели
	 *
	 * @return string
	 */
	public static function showCarBrandAndModelField ($brandID=0, $modelID=0, $brandFieldName='', $modelFieldName='', $modelAddFieldName='')
	{
		if ($brandFieldName=='')
		{
			$brandFieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_brand');
		}
		if ($modelFieldName=='')
		{
			$modelFieldName = CoreLib\Loc::getPackMessage('icar','mycars_select_model');
		}
		if ($modelAddFieldName=='')
		{
			$modelAddFieldName = CoreLib\Loc::getPackMessage('icar','mycars_add_model');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_brand\" class=\"col-sm-2 control-label\">"
			.$brandFieldName."</label><div class=\"col-sm-10\">"
			.CarBrand::getHtmlSelect($brandID)."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$brandFieldName.":</td><td class=\"value\">"
			.CarBrand::getHtmlSelect($brandID)."</td><td>&nbsp;</td></tr>";*/

		if ($brandID > 0)
		{
			$echo .= "<div class=\"form-group\"><label for=\"car_model\" class=\"col-sm-2 control-label\">"
				.$modelFieldName."</label><div class=\"col-sm-10\">"
				.CarModel::getHtmlSelect ($brandID, $modelID)."</div></div>";

/*			$echo .= "<tr><td class=\"td_model name\">".$modelFieldName.":</td><td class=\"td_model value\">"
				.CarModel::getHtmlSelect ($brandID, $modelID)."</td><td>&nbsp;</td></tr>";*/

			$echo .= "<div class=\"form-group add_new_model\"><label for=\"car_model_text\" class=\"col-sm-2 control-label\">"
				."или Добавьте новую"."</label><div class=\"col-sm-10\">"
				.InputText ('car_model_text', '', 'class="car_model_text form-control"')."</div></div>";

/*			$echo .= "<tr class='add_new_model'><td class=\"name\">или Добавьте новую</td><td class=\"value\">"
				.InputText ('car_model_text', '', 'class="car_model_text"')."</td></tr>";*/
		}
		else
		{
			$echo .= "<div class=\"form-group\"><label for=\"car_model_text\" class=\"col-sm-2 control-label model_text_title\">"
				.$modelAddFieldName."</label><div class=\"col-sm-10 model_text_field\">"
				.\InputText ('car_model_text', '', 'class="car_model_text form-control"')."</div></div>";

/*			$echo .= "<tr><td class=\"td_model name\">".$modelAddFieldName.":</td><td class=\"td_model value\">"
				.\InputText ('car_model_text', '', 'class="car_model_text"')."</td><td>&nbsp;</td></tr>";*/

			$echo .= "<div class=\"form-group add_new_model\" style=\"display: none;\"></div>";

			//$echo .= "<tr class='add_new_model' style='display: none;'></tr>";
		}

		$echo .= "<script type=\"text/javascript\">
		$(document).on(\"ready\",function(){
			$('#car_brand').on('change',function(){
				var brand_id = $(this).val();
				$.post(
					'".CoreLib\Config::getConfig("ICAR_TOOLS_ROOT").'get_select_model.php'."',
					{
						brand_id: brand_id
					},
					function(data) {
						//console.log(data);
						if (data.status == 'ok')
						{
							$('.model_text_title').html('".$modelFieldName."');
							$('.model_text_field').html(data.select);
							$('.add_new_model').html('<label for=\"car_model_text\" class=\"col-sm-2 control-label\">"
									."или Добавьте новую"."</label><div class=\"col-sm-10\">"
									.InputText ('car_model_text', '', 'class="car_model_text form-control"')."</div>');
							$('.add_new_model').show();
						}
						else
						{
							$('.model_text_title').html('".$modelAddFieldName."');
							$('.model_text_field').html('".\InputText('car_model_text','','class="car_model_text form-control"')."');

						}
					},
					\"json\"
				);
			});

		});
		</script>\n";


		return $echo;
	}

	/**
	 * Возвращает поле формы Год выпуска
	 *
	 * @api
	 *
	 * @param string $carYear   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarYearField ($carYear='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_year');
		}
		//CoreLib\Plugins::includeMaskedInput();

		$echo = "<div class=\"form-group\"><label for=\"car_year\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_year',$carYear,(intval(date("Y"))-100),date("Y"),1,'class="car_year form-control" placeholder="гггг"')."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_year',$carYear,(intval(date("Y"))-100),date("Y"),1,'class="car_year" placeholder="гггг"');*/

		return $echo;
	}

	/**
	 * Возвращает поле формы VIN
	 *
	 * @api
	 *
	 * @param string $carVin    Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarVinField ($carVin='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_vin');
		}
		CoreLib\Plugins::includeMaskedInput();
		$echo = "<div class=\"form-group\"><label for=\"car_vin\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputText('car_vin',$carVin,'class="car_vin form-control" autocapitalize="characters"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_num_and_big_lat')."</span>"
			."<script>\$(function(){\$.mask.definitions['h']='[0-9A-HJ-NPR-Z]';"
			."\$('.car_vin').mask('hhhhhhhhhhhhhhhhh');});</script>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputText('car_vin',$carVin,'class="car_vin"')
			."<script>\$(function(){\$.mask.definitions['h']='[0-9A-HJ-NPR-Z]';"
			."\$('.car_vin').mask('hhhhhhhhhhhhhhhhh');});</script>"
			."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_num_and_lat')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Гос. номер автомобиля
	 *
	 * @api
	 *
	 * @param string $carNumber Значени по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarNumberField ($carNumber='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_number');
		}
		//CoreLib\Plugins::includeMaskedInput();
		$echo = "<div class=\"form-group\"><label for=\"car_number\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputText('car_number',$carNumber,'class="car_number form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_num_and_big_lat')."</span>"
			//."<script>\$(function(){\$.mask.definitions['h']='[0-9A-Z]';"
			//."\$('.car_number').mask('h?');});</script>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputText('car_number',$carNumber,'class="car_number"')."</td><td>"
			.CoreLib\Loc::getMessage('ms_icar_mycars_num_and_lat')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Объем двигателя
	 *
	 * @api
	 *
	 * @param string $carEngine Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarEngineField ($carEngine='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_engine_capacity');
		}

		$echo = "<div class=\"form-group\"><label for=\"car_engine\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_engine',$carEngine,0.1,false,0.1,'class="car_engine form-control"')."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_engine',$carEngine,0.1,false,0.1,'class="car_engine"')."</td><td>"
			.CoreLib\Loc::getMessage('ms_icar_mycars_litra')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Тип коробки передач
	 *
	 * @api
	 *
	 * @param int    $carGearbox    Значение по-умолчанию
	 * @param string $fieldName     Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarGearboxField ($carGearbox=0, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_gearbox');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_gearbox\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.CarGearbox::getHtmlSelect($carGearbox)."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.CarGearbox::getHtmlSelect($carGearbox)."</td><td>&nbsp;</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Тип кузова
	 *
	 * @api
	 *
	 * @param int    $carBody   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarBodyField ($carBody=0, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_body_type');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_body\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.CarBody::getHtmlSelect($carBody)."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.CarBody::getHtmlSelect($carBody)."</td><td>&nbsp;</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Дата первого ТО
	 *
	 * @api
	 *
	 * @param string $carTs     Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarTsField ($carTs='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_interval_ts');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_ts\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_ts',$carTs,false,false,1,'class="car_ts form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_km')."</span>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_ts',$carTs,false,false,1,'class="car_ts"')."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_km')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает название поля Стоимость автомобиля
	 *
	 * @api
	 *
	 * @param string $carCost   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarCostField ($carCost='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_auto_cost_buy');
		}
		if (strval($carCost) != '')
		{
			$carCost = Main::formatMoney ($carCost, TRUE);
		}
		$echo = "<div class=\"form-group\"><label for=\"car_cost\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_cost',$carCost,0,false,0.01,'class="car_cost form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_rub')."</span>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_cost',$carCost,0,false,0.01,'class="car_cost"')."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_rub')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Пробег при покупке
	 *
	 * @api
	 *
	 * @param string $carMileage    Значение по-умолчанию
	 * @param string $fieldName     Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarMileageBuyField ($carMileage='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_mileage_buy');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_mileage\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_mileage',$carMileage,0,false,1,'class="car_mileage form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_km')."</span>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_mileage',$carMileage,0,false,1,'class="car_mileage"')."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_km')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Автомобиль в кредит
	 *
	 * @api
	 *
	 * @param bool   $carCredit Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarCreditField ($carCredit=false, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_credit');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_credit\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\SelectBoxBool('car_credit',(($carCredit) ? 1 : 0),'','','class="form-control"')."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\SelectBoxBool('car_credit',(($carCredit) ? 1 : 0))."</td><td>&nbsp;</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Сумма кредита
	 *
	 * @api
	 *
	 * @param string $carCreditCost Значение по-умолчанию
	 * @param string $fieldName     Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarCreditCostField ($carCreditCost='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_car_credit_cost');
		}
		if (strval($carCreditCost) != '')
		{
			$carCreditCost = Main::formatMoney ($carCreditCost, TRUE);
		}
		$echo = "<div class=\"form-group\"><label for=\"car_credit_cost\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('car_credit_cost',$carCreditCost,0,false,0.01,'class="car_credit_cost form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_rub')."</span>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputNumber('car_credit_cost',$carCreditCost,0,false,0.01,'class="car_credit_cost"')."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_rub')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращаент поле формы Дата ОСАГО
	 *
	 * @api
	 *
	 * @param string $carOsago  Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarOsagoField ($carOsago='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_date_end_osago');
		}

		$dateHelper = new CoreLib\DateHelper();
		if ($carOsago!='')
		{
			$carOsago = $dateHelper->convertDateToDB($carOsago);
		}
		$echo = "<div class=\"form-group\"><label for=\"car_osago\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputDate('car_osago',$carOsago,date("Y-m-d"),false,'class="car_osago form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_set_notice')."</span>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputDate('car_osago',$carOsago,date("Y-m-d"),false,'class="car_osago"')."</td><td>"
			//.\InputCalendar('car_osago',$carOsago,'class="car_osago"')."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_set_notice')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Дата ГТО
	 *
	 * @api
	 *
	 * @param string $carGto    Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarGtoField ($carGto='', $fieldName='')
	{
		if ($fieldName == '')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_date_end_gto');
		}

		$dateHelper = new CoreLib\DateHelper();
		if ($carGto!='')
		{
			$carGto = $dateHelper->convertDateToDB($carGto);
		}
		$echo = "<div class=\"form-group\"><label for=\"car_gto\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputDate('car_gto',$carGto,date('Y-m-d'),false,'class="car_gto form-control"')
			."<span class=\"help-block small\">".CoreLib\Loc::getPackMessage('icar','mycars_set_notice')."</span>"
			."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\InputDate('car_gto',$carGto,date('Y-m-d'),false,'class="car_gto"')."</td><td>"
			//.\InputCalendar('car_gto',$carGto,'class="car_gto"')."</td><td>"
			.CoreLib\Loc::getPackMessage('icar','mycars_set_notice')."</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Автомобиль
	 *
	 * @api
	 *
	 * @param bool   $carDefault    Значение по-умолчанию
	 * @param string $fieldName     Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCarDefaultField ($carDefault=false, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','mycars_default_car');
		}
		$echo = "<div class=\"form-group\"><label for=\"car_default\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\SelectBoxBool('car_default',(($carDefault) ? 1 : 0),'','','class="form-control"')."</div></div>";

/*		$echo = "<tr><td class=\"name\">".$fieldName.":</td><td class=\"value\">"
			.\SelectBoxBool('car_default',(($carDefault) ? 1 : 0))."</td><td>&nbsp;</td></tr>";*/

		return $echo;
	}

	/**
	 * Возвращает поле формы Дата
	 *
	 * @api
	 *
	 * @param string $date      Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 * @param bool   $mobile    Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showDateField ($date='', $fieldName='', $mobile=false)
	{
		if (strval($date)=='')
		{
			//$date = date('d.m.Y');
			$date = date('Y-m-d');
		}

		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_date');
		}

		$dateHelper = new CoreLib\DateHelper();
		$date = $dateHelper->convertDateToDB($date);

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputDate ('date', $date, false, false, 'class="calendarDate form-control"')
			."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Пробег
	 *
	 * @api
	 *
	 * @param string $odo       Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showOdoField ($odo='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_odo');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('odo',$odo,0,false,1,'class="odo form-control" placeholder="'.round(Odo::getCurrentOdo()).'"')
			."</div></div>";

		return $echo;
	}

	public static function showMobileGpsField ($pointType='null', $point='start')
	{
		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">Координаты Tasker</label><div class=\"col-sm-10\">"
			.\InputText('tasker_gps','','class="tasker_gps form-control"')
			."<br><button type=\"button\" class=\"btn btn-default tasker_gps_button\">GET</button></div>"
			."</div>";
		if ($point=='start')
		{
			$echo .= self::showStartPointField('null',true,false,'Путевая точка',$pointType,true);
		}
		else
		{
			$echo .= self::showEndPointField('null',true,'',$pointType,true);
		}
		$echo .= "<script>
			\$(document).on('ready',function(){
				\$('.odo').focus();
				\$('.tasker_gps_button').on('click',function(){
					var gps = $('.tasker_gps').val();
					\$.post(
						'".CoreLib\Config::getConfig("ICAR_TOOLS_ROOT")."get_point_by_gps.php',
						{
							gps: gps,
							pointType: '".$pointType."'
						},
						function(data) {
							console.log(data);
							if (data.status == 'point')
							{
								\$('.".$point."_point [value=\"' + data.point_id + '\"]').attr(\"selected\", \"selected\");
								\$('.".$point."_point_field').hide();
								\$('.comment').focus();
							}
							else if (data.status == 'new')
							{
								$('#".$point."_lat').val(data.lat);
								$('#".$point."_lon').val(data.lon);
								$('#".$point."_name').focus();
							}
							else
							{

							}
						},
						\"json\"
					);

				});
			});
			</script>
		";


		return $echo;
	}

	/**
	 * Возвращает поле формы выбора Стартовой путевой точки или просто Путевой точки, для остальных форм
	 *
	 * @api
	 *
	 * @param string $startPoint    Значение по-умолчанию
	 * @param bool   $showOr        Флаг. Выводить ли поля для добавления новой путевой точки
	 * @param bool   $route         Флаг. Выводить ли поле "По городу"
	 * @param string $fieldName     Альтернативное название поля
	 * @param string $type          Тип путевой точки
	 *
	 * @return string
	 */
	public static function showStartPointField ($startPoint='null', $showOr=true, $route=false, $fieldName='', $type='null')
	{
		if ($fieldName=='')
		{
			if ($route)
			{
				$fieldName = CoreLib\Loc::getPackMessage('icar','odo_start_point_route');
			}
			else
			{
				$fieldName = CoreLib\Loc::getPackMessage('icar','odo_start_point');
			}
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.Points::showSelectPoints("start_point",$startPoint,'class="start_point form-control"',$type)
			."</div></div>";

		if ($showOr)
		{
			$echo .= self::showPointFormFields($route,'start',$type);
		}

		return $echo;
	}

	/**
	 * Возвращает поле формы выбора Конечной путевой точки
	 *
	 * @api
	 *
	 * @param string        $endPoint   Значение по-умолчанию
	 * @param bool          $showOr     Флаг. Показывать ли поля для добавления новой путевой точки
	 * @param string        $fieldName  Альтернативное название поля
	 * @param string|array  $type       Тип путевой точки
	 * @param bool          $mobile     Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showEndPointField ($endPoint='null', $showOr=true, $fieldName='', $type='null', $mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','odo_end_point');
		}

		$echo = "<div class=\"form-group end_point_select\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.Points::showSelectPoints("end_point",$endPoint,'class="end_point form-control"',$type)
			."</div></div>";

		if ($showOr)
		{
			$echo .= self::showPointFormFields(true,'end',$type,$mobile);
		}

		return $echo;
	}

	/**
	 * Возвращает поля формы для создания новой путевой точки
	 *
	 * @api
	 *
	 * @param bool   $route     Флаг, обозначающий, что необходимо вывести поле "По городу"
	 * @param string $point     Какая путевая точка ('start' - стартовая, 'end' - конечная)
	 * @param string $type      Тип путевой точки
	 *
	 * @return string
	 */
	public static function showPointFormFields ($route=false, $point='start', $type='null')
	{
		//или
		$echo = "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			."&nbsp;</label><div class=\"col-sm-10\">"
			.CoreLib\Loc::getPackMessage('icar','all_or')
			."</div></div>";
		//тип путевой точки
		if (is_array($type))
		{
			$selected = $type[0];
		}
		else
		{
			$selected = $type;
		}
		$echo .= "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_type_point')."</label><div class=\"col-sm-10\">"
			.Points::showSelectPointTypes($point.'_type',$selected,'class="point_type form-control"')
			."</div></div>";
		//имя
		$echo .= "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_new_name')."</label><div class=\"col-sm-10\">"
			.\InputText($point.'_name','','class="point_name form-control"')
			."</div></div>";
		//адрес
		$echo .= "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_new_address')."</label><div class=\"col-sm-10\">"
			.\InputText($point.'_address','','class="point_address form-control"')
			."</div></div>";
		//широта
		$echo .= "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_new_lat')."</label><div class=\"col-sm-10\">"
			.\InputNumber($point.'_lat','',0,false,0.000001,'class="point_lat form-control" placeholder="55.765542"')
			."</div></div>";
		//долгота
		$echo .= "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_new_lon')."</label><div class=\"col-sm-10\">"
			.\InputNumber($point.'_lon','',0,false,0.000001,'class="point_lon form-control" placeholder="39.356721"')
			."</div></div>";

		if (CoreLib\Loader::issetPackage('yandexmap') && CoreLib\Loader::IncludePackage('yandexmap'))
		{
			$echo .= "<div class=\"form-group ".$point."_point_field\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
				.CoreLib\Loc::getPackMessage('icar','all_point_on_map')."</label><div class=\"col-sm-10\">";
			$echo .= '<button type="button" class="btn btn-default '.$point.'_map_button">'
				.CoreLib\Loc::getPackMessage('icar','all_open_map').'</button>';
			$echo .= YandexMap::showMapForClick($point.'_map',$point.'_lat',$point.'_lon');
			$echo .= '<script>'
				.'$(document).on("ready",function(){'
					.'$("#'.$point.'_map").hide();'
					.'$(".'.$point.'_map_button").on("click",function(){'
						.'$(this).hide();'
						.'$("#'.$point.'_map").show();'
						.'ymaps.ready(init_'.$point.'_map);'
					.'});'
				.'});'
				.'</script>';
			$echo .= '</div></div>';
		}


		if ($route && $point=='start')
		{
			$echo .= "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
				.CoreLib\Loc::getPackMessage('icar','all_local_route')."</label><div class=\"col-sm-10\">"
				.\InputType('checkbox','end_start',1,'',false,'','class="end_start" style="width: 20px; height: 20px;"')
				."</div></div>";
			$echo .= "
				<script>
				$(document).on(\"ready\",function(){
					$('.end_start').on(\"change\",function(){
						if ($(this).prop(\"checked\"))
						{
							$('.end_point_field').hide();
							$('.end_point_select').hide();
						}
						else
						{
							$('.end_point_field').show();
							$('.end_point_select').show();
						}
					});
				});
				</script>
			";
		}
		$echo .= "<script>
			$(document).on('ready',function(){
				if ($('.".$point."_point').val()!='NULL')
				{
					$('.".$point."_point_field').hide();
				}
				$('.".$point."_point').on('change',function(){
					if ($(this).val()!='NULL')
					{
						$('.".$point."_point_field').hide();
					}
					else
					{
						$('.".$point."_point_field').show();
					}
				});
			});
		</script>";

		return $echo;
	}

	/**
	 * Возвращает поле формы ТО
	 *
	 * @api
	 *
	 * @param string $ts        Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showTsField ($ts="null", $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','ts_num');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.Ts::showSelectTsNum("ts_num",$ts,'class="ts_num form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Исполнитель
	 *
	 * @api
	 *
	 * @param string $executor  Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showExecutorsField ($executor='null', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_executor');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.Ts::showSelectExecutor("executor",$executor,'class="executor form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Цена
	 *
	 * @api
	 *
	 * @param string $cost      Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCostField ($cost='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','ts_cost');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('cost',$cost,0,false,0.01,'class="cost form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Комментарий
	 *
	 * @api
	 *
	 * @param string $comment   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 * @param bool   $mobile    Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showCommentField ($comment='', $fieldName='',$mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_comment');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputText('comment',$comment,'class="comment form-control"')
			."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Марки топлива
	 *
	 * @api
	 *
	 * @param string $fuelMark  Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 * @param bool   $mobile    Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showFuelMarksField ($fuelMark='null', $fieldName='', $mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','fuel_mark');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.Fuel::showSelectFuelMarks("fuel_mark",$fuelMark,'class="fuel_mark form-control"')
			."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Литры
	 *
	 * @api
	 *
	 * @param string $liters    Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 * @param bool   $mobile    Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showLitersField ($liters='', $fieldName='', $mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','fuel_liters');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('liters',$liters,0,false,0.01,'class="liters form-control" placeholder="1.00"')
			."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Стоимость литра
	 *
	 * @api
	 *
	 * @param string $literCost Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 * @param bool   $mobile    Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showLiterCostField ($literCost='', $fieldName='', $mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','fuel_liter_cost');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('cost_liter',$literCost,0,false,0.01,'class="cost_liter form-control" placeholder="1.00"')
			."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Полный бак
	 *
	 * @api
	 *
	 * @param bool   $fullTank  Значение по-умолчанию
	 * @param string $fieldName Альтарнативное название поля
	 * @param bool   $mobile    Флаг использования в мобильной версии
	 *
	 * @return string
	 */
	public static function showFullTankField ($fullTank=false, $fieldName='', $mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','fuel_full');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputType('checkbox','full_tank',1,(($fullTank)?1:0),false,'','class="full_tank" style="width: 20px; height: 20px;"')
			."</div></div>";

		return $echo;
	}

	public static function showMissingPrevFuel ($missed=false, $fieldName='', $mobile=false)
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','fuel_missing');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputType('checkbox','missing_fuel',1,(($missed)?1:0),false,'','class="missing_fuel" style="width: 20px; height: 20px;"')
			."</div></div>";

		return $echo;
	}

	public static function showCheckField ($fileID=null, $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','fuel_check');
		}

		if (is_null($fileID) || intval($fileID) <= 0)
		{
			$echo = "<div class=\"form-group\"><label for=\"check\" class=\"col-sm-2 control-label\">"
				.$fieldName."</label><div class=\"col-sm-10\">"
				.\InputFile('check','','class="check form-control"')
				."</div></div>";
		}
		else
		{
			$echo = "<div class=\"form-group\"><label for=\"check\" class=\"col-sm-2 control-label\">".$fieldName
				."</label><div class=\"col-sm-10\"><input type=\"hidden\" name=\"check_id\" value=\"".intval($fileID)."\">"
				.CoreLib\File::showImage(intval($fileID),100,100,'class="check_img"','',true)
				."&nbsp;".CoreLib\Loc::getPackMessage('icar','all_delete_image')."&nbsp;"
				.\InputType('checkbox','check_delete',1,'',false,'','class="check_delete" style="width: 20px; height: 20px;"')
				."<br><br>".CoreLib\Loc::getPackMessage('icar','all_or_change_image').":"
				.\InputFile('new_check','','class="new_check form-control"')
				."</div>";
			$echo .= "</div>";
			$echo .= "
				<script>
					$(document).on('ready',function(){
						$('.check_delete').on('change',function(){
							if ($(this).prop('checked'))
							{
								$('.check_img').css('opacity', '.25');
							}
							else
							{
								$('.check_img').css('opacity', '1');
							}
						});
						$('.new_check').on('change', function () {
    						if ($(this).val())
    						{
								$('.check_img').css('opacity', '.25');
    						}
    						else
    						{
    							$('.check_img').css('opacity', '1');
    						}
						});
					});
				</script>
			";
		}

		return $echo;
	}

	/**
	 * Возвращает поле формы Название
	 *
	 * @api
	 *
	 * @param string $name      Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showNameField ($name='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_name');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputText('name',$name,'class="name_field form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Место хранения
	 *
	 * @api
	 *
	 * @param int    $storage   Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showStorageField ($storage=0, $fieldName='')
	{
		if ($fieldName == '')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_storage');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.Storage::showSelectStorageList("storage",'',$storage, 'class="storageselect form-control"')."</div></div>";

		//$echo = "<tr><td class=\"name\">".$fieldName."</td><td class=\"value\">"
		//	.Storage::showSelectStorageList("storage",'',$storage)."</td></tr>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Каталожный номер
	 *
	 * @api
	 *
	 * @param string $catalogNumber Значение по-умолчанию
	 * @param string $fieldName     Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showCatalogNumberField ($catalogNumber='', $fieldName='')
	{
		if ($fieldName == '')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_catalog_number');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputText('catalog_number',$catalogNumber,'class="catalog_number form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поле формы Количество
	 *
	 * @api
	 *
	 * @param string $number    Значение по-умолчанию
	 * @param string $fieldName Альтернативное название поля
	 *
	 * @return string
	 */
	public static function showNumberField ($number='', $fieldName='')
	{
		if ($fieldName=='')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_number');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.\InputNumber('number',$number,0,false,0.01,'class="number form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Возвращает поля формы (Причина замены и Доп. причины: ТО, Поломка, ДТП, Тюнинг, Upgrade)
	 *
	 * @api
	 *
	 * @param int    $carID     ID автомобиля
	 * @param int    $reason    Причина замены по-умолчанию
	 * @param string $ts        Доп. причина ТО, по-умолчанию
	 * @param string $breakdown Доп. причина Поломка, по умолчанию
	 * @param string $dtp       Доп. причина ДТП, по-умолчанию
	 * @param string $tuning    Доп. причина Тюнинг, по-умолчанию
	 * @param string $upgrade   Доп. причина Upgrade, по-умолчанию
	 * @param string $show      Поле, которое должно показываться (остальные скрыты)
	 *
	 * @return string
	 */
	public static function showReasonReplacementField ($carID=0, $reason=1, $ts='null', $breakdown='null', $dtp='null', $tuning='null', $upgrade='null', $show='')
	{
		if ($carID == 0)
		{
			$carID = MyCar::getDefaultCarID();
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_reason')."</label><div class=\"col-sm-10\">"
			.ReasonReplacement::showSelectReasonReplacementList("reason",'',$reason, 'class="reasonreplacementselect form-control"')."</div></div>";
		$echo .= "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.CoreLib\Loc::getPackMessage('icar','all_reason_add')."</label><div class=\"col-sm-10\">"
			.Ts::showSelectTsList(
				$carID,
				"reason_ts",
				CoreLib\Loc::getPackMessage('icar','all_not_select'),
				$ts,
				'id="reason_ts" class="tslistselect form-control"'.(($show=='ts'||$show=='')?'':' style="display: none;"')
			)
			.Repair::showSelectRepairList(
				$carID,
				"reason_breakdown",
				CoreLib\Loc::getPackMessage('icar','all_not_select'),
				$breakdown,
				'id="reason_breakdown" class="repairlistselect form-control"'.(($show=='breakdown')?'':' style="display: none;"')
			)
			.Accident::showSelectAccidentList(
				$carID,
				"reason_dtp",
				CoreLib\Loc::getPackMessage('icar','all_not_select'),
				$dtp,
				'id="reason_dtp" class="accidentlistselect form-control"'.(($show=='accident')?'':' style="display: none;"')
			)
			.Repair::showSelectRepairList(
				$carID,
				"reason_tuning",
				CoreLib\Loc::getPackMessage('icar','all_not_select'),
				$tuning,
				'id="reason_tuning" class="repairlistselect form-control"'.(($show=='tuning')?'':' style="display: none;"')
			)
			.Repair::showSelectRepairList(
				$carID,
				"reason_upgrade",
				CoreLib\Loc::getPackMessage('icar','all_not_select'),
				$upgrade,
				'id="reason_upgrade" class="repairlistselect form-control"'.(($show=='upgrade')?'':' style="display: none;"')
			)
			."<span class=\"value reason_tire\"".(($show=='tire')?'':' style="display: none;"').">-</span>"
			."</div></div>";

		/*
		$echo = "
			<tr>
				<td class=\"name\">".CoreLib\Loc::getPackMessage('icar','all_reason')."</td>
				<td class=\"value\">".ReasonReplacement::showSelectReasonReplacementList("reason",'',$reason)."</td>
			</tr>
			<tr>
				<td class=\"name\">".CoreLib\Loc::getPackMessage('icar','all_reason_add')."</td>
				<td class=\"value reason_add\">"
					.Ts::showSelectTsList(
						$carID,
						"reason_ts",
						CoreLib\Loc::getPackMessage('icar','all_not_select'),
						$ts,
						'id="reason_ts" class="tslistselect"'.(($show=='ts'||$show=='')?'':' style="display: none;"')
					)
					.Repair::showSelectRepairList(
						$carID,
						"reason_breakdown",
						CoreLib\Loc::getPackMessage('icar','all_not_select'),
						$breakdown,
						'id="reason_breakdown" class="repairlistselect"'.(($show=='breakdown')?'':' style="display: none;"')
					)
					.Accident::showSelectAccidentList(
						$carID,
						"reason_dtp",
						CoreLib\Loc::getPackMessage('icar','all_not_select'),
						$dtp,
						'id="reason_dtp" class="accidentlistselect"'.(($show=='accident')?'':' style="display: none;"')
					)
					.Repair::showSelectRepairList(
						$carID,
						"reason_tuning",
						CoreLib\Loc::getPackMessage('icar','all_not_select'),
						$tuning,
						'id="reason_tuning" class="repairlistselect"'.(($show=='tuning')?'':' style="display: none;"')
					)
					.Repair::showSelectRepairList(
						$carID,
						"reason_upgrade",
						CoreLib\Loc::getPackMessage('icar','all_not_select'),
						$upgrade,
						'id="reason_upgrade" class="repairlistselect"'.(($show=='upgrade')?'':' style="display: none;"')
					)
					."<span class=\"value reason_tire\"".(($show=='tire')?'':' style="display: none;"').">-</span>
				</td>
			</tr>";
		*/
		$echo .= "
			<script type=\"text/javascript\">
				function hideAllReason()
				{
					$('#reason_ts').hide();
					$('#reason_breakdown').hide();
					$('#reason_dtp').hide();
					$('#reason_tuning').hide();
					$('#reason_upgrade').hide();
					$('.reason_tire').hide();
				}
				$(document).on('ready',function(){
					$('.reasonreplacementselect').on('change',function(){
						var sel = $(this).val();
						if (sel==1)
						{
							hideAllReason();
							$('#reason_ts').show();
						}
						else if (sel==2)
						{
							hideAllReason();
							$('#reason_breakdown').show();
						}
						else if (sel==3)
						{
							hideAllReason();
							$('#reason_tuning').show();
						}
						else if (sel==4)
						{
							hideAllReason();
							$('#reason_upgrade').show();
						}
						else if (sel==5)
						{
							hideAllReason();
							$('.reason_tire').show();
						}
						else if (sel==6)
						{
							hideAllReason();
							$('#reason_dtp').show();
						}
					});
				});
			</script>";

		return $echo;
	}

	/**
	 * Возвращает поле формы "Кто оплачивал"
	 *
	 * @api
	 *
	 * @param   int     $whoPaid    Выбор по-умолчанию
	 * @param   string  $fieldName  Имя поля, не по-умолчанию
	 *
	 * @return string
	 */
	public static function showWhoPaidField ($whoPaid=1, $fieldName='')
	{
		if ($fieldName == '')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_who_paid');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.WhoPaid::showSelectWhoPaidList("who_paid",$whoPaid, 'class="whopaidselect form-control"')."</div></div>";

		//$echo = "<tr><td class=\"name\">".$fieldName."</td><td class=\"value\">"
		//	.WhoPaid::showSelectWhoPaidList("who_paid",$whoPaid)."</td></tr>";

		return $echo;
	}

	public static function showFlowTypeField ($defaultVal=1, $fieldName='')
	{
		if ($fieldName == '')
		{
			$fieldName = CoreLib\Loc::getPackMessage('icar','all_flow_type');
		}

		$echo = "<div class=\"form-group\"><label for=\"my_car\" class=\"col-sm-2 control-label\">"
			.$fieldName."</label><div class=\"col-sm-10\">"
			.FlowType::showSelectFlowTypeList("flow_type",$defaultVal,'--- Выбрать ---', 'class="flowtypeselect form-control"')."</div></div>";

		return $echo;
	}

	/**
	 * Преобразует выхоящие параметры к правильному виду и формирует массив исходящих параметров
	 *
	 * @api
	 *
	 * @param array         $arPost     Массив входных данных, переданный по ссылке
	 * @param null|array    $arData     Массив выходных данных, переданный по ссылке
	 */
	public static function validateFields (&$arPost,&$arData=null)
	{
		$dateHelper = new CoreLib\DateHelper();

		if (is_null($arData))
		{
			$arData = array();
		}

		if (isset($arPost['car_name']))
		{
			self::validateString($arPost['car_name']);
			if (strlen($arPost['car_name'])>2)
			{
				$arData['NAME'] = $arPost['car_name'];
			}
		}

		if (isset($arPost['car_active']))
		{
			self::validateBool($arPost['car_active']);
			$arData['ACTIVE'] = $arPost['car_active'];
		}

		if (isset($arPost['car_sort']))
		{
			self::validateInt($arPost['car_sort']);
			if ($arPost['car_sort']>=0)
			{
				$arData['SORT'] = $arPost['car_sort'];
			}
			else
			{
				$arData['SORT'] = 500;
			}
		}

		if(isset($arPost['car_brand']))
		{
			self::validateInt($arPost['car_brand']);
			if ($arPost['car_brand']>0)
			{
				$arData['CAR_BRANDS_ID'] = $arPost['car_brand'];
			}
		}

		if (isset($arPost['car_model']))
		{
			self::validateInt($arPost['car_model']);
			if ($arPost['car_model'])
			{
				$arData['CAR_MODEL_ID'] = $arPost['car_model'];
			}
		}

		if (isset($arPost['car_model_text']))
		{
			self::validateString($arPost['car_model_text']);
			if (strlen($arPost['car_model_text'])>0)
			{
				$arData['CAR_MODEL_TEXT'] = $arPost['car_model_text'];
			}
		}

		if (isset($arPost['car_year']))
		{
			self::validateInt($arPost['car_year']);
			if ($arPost['car_year']>1500)
			{
				$arData['YEAR'] = $arPost['car_year'];
			}
		}

		if (isset($arPost['car_vin']))
		{
			self::validateString($arPost['car_vin']);
			if (strlen($arPost['car_vin'])==17)
			{
				$arData['VIN'] = $arPost['car_vin'];
			}
		}

		if (isset($arPost['car_number']))
		{
			self::validateString($arPost['car_number']);
			//TODO: Посмотреть минимальный размер Автомобильного номера
			if (strlen($arPost['car_number'])>3)
			{
				$arData['CAR_NUMBER'] = $arPost['car_number'];
			}
		}

		if (isset($arPost['car_engine']))
		{
			self::validateFloat($arPost['car_engine']);
			$arData['ENGINE_CAPACITY'] = $arPost['car_engine'];
		}

		if (isset($arPost['car_gearbox']))
		{
			self::validateInt($arPost['car_gearbox']);
			if ($arPost['car_gearbox']>0)
			{
				$arData['CAR_GEARBOX_ID'] = $arPost['car_gearbox'];
			}
		}

		if (isset($arPost['car_body']))
		{
			self::validateInt($arPost['car_body']);
			if ($arPost['car_body']>0)
			{
				$arData['CAR_BODY_ID'] = $arPost['car_body'];
			}
		}

		if (isset($arPost['car_ts']))
		{
			self::validateFloat($arPost['car_ts']);
			$arData['INTERVAL_TS'] = $arPost['car_ts'];
		}

		if (isset($arPost['car_cost']))
		{
			self::validateFloat($arPost['car_cost']);
			$arData['COST'] = $arPost['car_cost'];
		}

		if (isset($arPost['car_mileage']))
		{
			self::validateFloat($arPost['car_mileage']);
			$arData['MILEAGE'] = $arPost['car_mileage'];
		}

		if (isset($arPost['car_credit']))
		{
			self::validateBool($arPost['car_credit']);
			$arData['CREDIT'] = $arPost['car_credit'];
		}

		if (isset($arPost['car_credit_cost']))
		{
			self::validateFloat($arPost['car_credit_cost']);
			$arData['CREDIT_COST'] = $arPost['car_credit_cost'];
		}

		if (isset($arPost['car_osago']) && CoreLib\DateHelper::checkDate($arPost['car_osago']))
		{
			self::validateDate($arPost['car_osago']);
			if ($arPost['car_osago']!==false)
			{
				$arData['DATE_OSAGO_END'] = $dateHelper->convertDateFromDB($arPost['car_osago']);
			}
		}

		if (isset($arPost['car_gto']) && CoreLib\DateHelper::checkDate($arPost['car_gto']))
		{
			self::validateDate($arPost['car_gto']);
			if ($arPost['car_gto']!==false)
			{
				$arData['DATE_GTO_END'] = $dateHelper->convertDateFromDB($arPost['car_gto']);
			}
		}

		if (isset($arPost['car_default']))
		{
			self::validateBool($arPost['car_default']);
			$arData['DEFAULT'] = $arPost['car_default'];
		}


		if (isset($arPost['my_car']))
		{
			self::validateInt($arPost['my_car']);
			if ($arPost['my_car']>0)
			{
				$arData['MY_CAR_ID'] = $arPost['my_car'];
			}
		}

		if (isset($arPost['date']) && CoreLib\DateHelper::checkDate($arPost['date']))
		{
			self::validateDate($arPost['date']);
			if ($arPost['date']!==false)
			{
				$arData['DATE'] = $dateHelper->convertDateFromDB($arPost['date']);
			}
		}

		if (isset($arPost['odo']))
		{
			self::validateFloat($arPost['odo']);
			if ($arPost['odo']>0)
			{
				$arData['ODO'] = $arPost['odo'];
			}
		}

		if (isset($arPost['start_point']))
		{
			self::validateInt($arPost['start_point']);
			if ($arPost['start_point']>0)
			{
				$arData['POINTS_ID'] = $arPost['start_point'];
			}
			else
			{
				$pointType = 'waypoint';
				if (isset($arPost['start_type']))
				{
					self::validateString($arPost['start_type']);
					if ($arPost['start_type']!='null')
					{
						$pointType = $arPost['start_type'];
					}
				}

				if (isset($arPost['start_name']))
				{
					self::validateString($arPost['start_name']);
				}

				if (isset($arPost['start_address']))
				{
					self::validateString($arPost['start_address']);
				}

				if (isset($arPost['start_lat']))
				{
					self::validateFloat($arPost['start_lat']);
				}

				if (isset($arPost['start_lon']))
				{
					self::validateFloat($arPost['start_lon']);
				}

				if (isset($arPost['start_address']) || (isset($arPost['start_lat']) && isset($post['start_lon'])))
				{
					if (!$arData['POINTS_ID'] = Points::createPointFromForm($arPost,'start',$pointType))
					{
						unset($arData['POINTS_ID']);
					}
				}
			}
		}

		if (isset($arPost['end_start']))
		{
			self::validateBool($arPost['end_start']);
			$arData['END_START'] = $arPost['end_start'];
		}

		if (isset($arPost['end_point']))
		{
			self::validateInt($arPost['end_point']);
			if ($arPost['end_point']>0)
			{
				$arData['END_POINTS_ID'] = $arPost['end_point'];
				if (!isset($arData['END_START']) || !$arData['END_START'])
				{
					$arData['end_point_num'] = true;
				}
			}
			else
			{
				$pointType = 'waypoint';
				if (isset($arPost['end_type']))
				{
					self::validateString($arPost['end_type']);
					if ($arPost['end_type']!='null')
					{
						$pointType = $arPost['end_type'];
					}
				}

				if (isset($arPost['end_name']))
				{
					self::validateString($arPost['end_name']);
				}

				if (isset($arPost['end_address']))
				{
					self::validateString($arPost['end_address']);
				}

				if (isset($arPost['end_lat']))
				{
					self::validateFloat($arPost['end_lat']);
				}

				if (isset($arPost['end_lon']))
				{
					self::validateFloat($arPost['end_lon']);
				}

				if (isset($arPost['end_address']) || (isset($arPost['end_lat']) && isset($post['end_lon'])))
				{
					if (!$arData['END_POINTS_ID'] = Points::createPointFromForm($arPost,'end',$pointType))
					{
						unset($arData['END_POINTS_ID']);
					}
				}
			}
		}

		if (isset($arPost['car_id']))
		{
			self::validateInt($arPost['car_id']);
		}

		if (isset($arPost['ts_num']))
		{
			self::validateInt($arPost['ts_num']);
			$arData['TS_NUM'] = $arPost['ts_num'];
		}

		if (isset($arPost['executor']))
		{
			self::validateInt($arPost['executor']);
			if ($arPost['executor']>0)
			{
				$arData['EXECUTOR_ID'] = $arPost['executor'];
			}
		}

		if (isset($arPost['cost']))
		{
			self::validateFloat($arPost['cost']);
			if ($arPost['cost']>0)
			{
				$arData['COST'] = $arPost['cost'];
			}
		}

		if (isset($arPost['comment']))
		{
			self::validateString($arPost['comment']);
			if (strlen($arPost['comment'])>0)
			{
				$arData['DESCRIPTION'] = $arPost['comment'];
			}
		}

		if (isset($arPost['fuel_mark']))
		{
			self::validateInt($arPost['fuel_mark']);
			if ($arPost['fuel_mark']>0)
			{
				$arData['FUELMARK_ID'] = $arPost['fuel_mark'];
			}
		}

		if (isset($arPost['liters']))
		{
			self::validateFloat($arPost['liters']);
			$arData['LITER'] = $arPost['liters'];
		}

		if (isset($arPost['cost_liter']))
		{
			self::validateFloat($arPost['cost_liter']);
			$arData['LITER_COST'] = $arPost['cost_liter'];
		}

		if (isset($arPost['full_tank']))
		{
			self::validateBool($arPost['full_tank']);
			$arData['FULL'] = $arPost['full_tank'];
		}

		if (isset($arPost['missing_fuel']))
		{
			self::validateBool($arPost['missing_fuel']);
			$arData['MISSING'] = $arPost['missing_fuel'];
		}

		if (isset($_FILES['check']))
		{
			self::validateFile($_FILES['check']);
			$arData['CHECK'] = $_FILES['check'];
		}

		if (isset($arPost['check_id']))
		{
			self::validateInt($arPost['check_id']);
			$arData['CHECK'] = $arPost['check_id'];
		}

		if (isset($arPost['check_delete']))
		{
			self::validateBool($arPost['check_delete']);
			$arData['~CHECK_DELETE'] = $arPost['check_delete'];
		}

		if (isset($_FILES['new_check']))
		{
			self::validateFile($_FILES['new_check']);
			$arData['~NEW_CHECK'] = $_FILES['new_check'];
		}

		if (isset($arPost['name']))
		{
			self::validateString($arPost['name']);
			if (strlen($arPost['name'])>0)
			{
				$arData['NAME'] = $arPost['name'];
			}
		}

		if (isset($arPost['storage']))
		{
			self::validateInt($arPost['storage']);
			if ($arPost['storage']>0)
			{
				$arData['STORAGE_ID'] = $arPost['storage'];
			}
		}

		if (isset($arPost['catalog_number']))
		{
			self::validateString($arPost['catalog_number']);
			if (strlen($arPost['catalog_number'])>0)
			{
				$arData['CATALOG_NUMBER'] = $arPost['catalog_number'];
			}
		}

		if (isset($arPost['number']))
		{
			self::validateFloat($arPost['number']);
			if ($arPost['number']>0)
			{
				$arData['NUMBER'] = $arPost['number'];
			}
			else
			{
				$arData['NUMBER'] = 1;
			}
		}

		if (isset($arPost['reason']))
		{
			self::validateInt($arPost['reason']);
			if ($arPost['reason']>0)
			{
				$arData['REASON_REPLACEMENT_ID'] = $arPost['reason'];
				$reasonCode = ReasonReplacement::getCodeById($arData['REASON_REPLACEMENT_ID']);
				switch ($reasonCode)
				{
					case 'ts':
						if (isset($arPost['reason_ts']))
						{
							self::validateInt($arPost['reason_ts']);
							if ($arPost['reason_ts']>0)
							{
								$arData['TS_ID'] = $arPost['reason_ts'];
							}
						}
						break;
					case 'breakdown':
						if (isset($arPost['reason_breakdown']))
						{
							self::validateInt($arPost['reason_breakdown']);
							if ($arPost['reason_breakdown']>0)
							{
								$arData['REPAIR_ID'] = $arPost['reason_breakdown'];
							}
						}
						break;
					case 'tuning':
						if (isset($arPost['reason_tuning']))
						{
							self::validateInt($arPost['reason_tuning']);
							if ($arPost['reason_tuning']>0)
							{
								$arData['REPAIR_ID'] = $arPost['reason_tuning'];
							}
						}
						break;
					case 'upgrade':
						if (isset($arPost['reason_upgrade']))
						{
							self::validateInt($arPost['reason_upgrade']);
							if ($arPost['reason_upgrade']>0)
							{
								$arData['REPAIR_ID'] = $arPost['reason_upgrade'];
							}
						}
						break;
					case 'tire':
						break;
					case 'accident':
						if (isset($arPost['reason_dtp']))
						{
							self::validateInt($arPost['reason_dtp']);
							if ($arPost['reason_dtp']>0)
							{
								$arData['ACCIDENT_ID'] = $arPost['reason_dtp'];
							}
						}
						break;
					default:
						return false;
				}
			}
		}

		if (isset($arPost['who_paid']))
		{
			self::validateInt($arPost['who_paid']);
			if ($arPost['who_paid']>0)
			{
				$arData['WHO_PAID_ID'] = $arPost['who_paid'];
			}
		}

		if (isset($arPost['flow_type']))
		{
			self::validateInt($arPost['flow_type']);
			if ($arPost['flow_type'] > 0)
			{
				$arData['FLOW_TYPE_ID'] = $arPost['flow_type'];
			}
		}

		if (isset($arPost['id']))
		{
			self::validateInt($arPost['id']);
		}
	}

	/**
	 * Преобразует переменную к типу float
	 *
	 * @api
	 *
	 * @param mixed $float_val Переменная, переданная по ссылке
	 */
	public static function validateFloat (&$float_val)
	{
		$float_val = str_replace(' ','',$float_val);
		$float_val = str_replace(',','.',$float_val);
		$float_val = floatval($float_val);
	}

	/**
	 * Преобразует переменную к типу bool
	 *
	 * @api
	 *
	 * @param mixed $bool_val Переменная, переданная по ссылке
	 */
	public static function validateBool (&$bool_val)
	{
		if (
			(is_string($bool_val) && ($bool_val == '1' || $bool_val == '0'))
			||
			(is_bool($bool_val))
		)
		{
			$bool_val = (int) $bool_val;
		}
		elseif (is_string($bool_val) && ($bool_val == 'true' || $bool_val == 'Y'))
		{
			$bool_val = 1;
		}
		elseif (is_string($bool_val) && ($bool_val == 'false' || $bool_val== 'N'))
		{
			$bool_val = 0;
		}

		if (is_integer($bool_val) && $bool_val == 1)
		{
			$bool_val = true;
		}
		else
		{
			$bool_val = false;
		}
	}

	/**
	 * Обрабатывает строку, заменяя опасные символы
	 *
	 * @api
	 *
	 * @param mixed $string_val
	 */
	public static function validateString(&$string_val)
	{
		$string_val = htmlspecialchars(trim($string_val));
	}

	/**
	 * Преобразует переменную к типу integer
	 *
	 * @api
	 *
	 * @param mixed $int_val
	 */
	public static function validateInt (&$int_val)
	{
		$int_val = intval($int_val);
	}

	/**
	 * Преобразует переменную к правильному формату даты
	 *
	 * @api
	 *
	 * @param mixed $date_val
	 */
	public static function validateDate (&$date_val)
	{
		$date_val = CoreLib\DateHelper::validateDate($date_val);
	}

	/**
	 * Обрабатывает данные файла и возвращает ID записи файла из таблицы файлов
	 *
	 * @api
	 *
	 * @param array $file - массив файла со структурой $_FILES
	 */
	public static function validateFile (&$file)
	{
		if ($fileID = CoreLib\File::addNewImg('icar',$file))
		{
			$file = $fileID;
		}
		else
		{
			$file = 0;
		}
	}
}