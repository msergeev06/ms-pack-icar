<?php

namespace MSergeev\Packages\Icar\Tables;

use MSergeev\Core\Entity;
use MSergeev\Core\Lib\DataManager;
use MSergeev\Core\Lib\TableHelper;

class CarModelTable extends DataManager {
	public static function getTableName() {
		return 'ms_icar_car_model';
	}
	public static function getTableTitle() {
		return 'Модели автомобилей';
	}
	public static function getTableLinks() {
		return array(
			'ID' => array(
				'ms_icar_my_car' => 'CAR_MODEL_ID'
			)
		);
	}
	public static function getMap(){
		return array(
			new Entity\IntegerField('ID',array(
				'primary' => true,
				'autocomplete' => true,
				'title' => 'ID модели автомобиля'
			)),
			TableHelper::activeField(),
			TableHelper::sortField(),
			new Entity\IntegerField('BRANDS_ID',array(
				'link' => 'ms_icar_car_brands.ID',
				'required' => true,
				'default_value' => 0,
				'title' => 'ID бренда автомобиля'
			)),
			new Entity\StringField('NAME',array(
				'required' => true,
				'title' => 'Название модели автомобиля'
			)),
			new Entity\StringField('CODE',array(
				'required' => true,
				'run' => array(
					'function' => "\\MSergeev\\Core\\Lib\\Tools::generateCode",
					'column' => 'NAME'
				),
				'title' => 'Код модели автомобиля'
			))
		);
	}
	public static function getArrayDefaultValues ()
	{
		return array(
			array('BRANDS_ID' => 1,'NAME' => 'Ace','CODE' => 'ac_cars_ace'),
			array('BRANDS_ID' => 1,'NAME' => 'Aceca','CODE' => 'ac_cars_aceca'),
			array('BRANDS_ID' => 1,'NAME' => 'Cobra','CODE' => 'ac_cars_cobra'),

			array('BRANDS_ID' => 2,'NAME' => 'Integra','CODE' => 'acura_integra'),
			array('BRANDS_ID' => 2,'NAME' => 'Integra Type R','CODE' => 'acura_integra_type_r'),
			array('BRANDS_ID' => 2,'NAME' => 'Legend','CODE' => 'acura_legend'),
			array('BRANDS_ID' => 2,'NAME' => 'MDX','CODE' => 'acura_mdx'),
			array('BRANDS_ID' => 2,'NAME' => 'RDX','CODE' => 'acura_rdx'),
			array('BRANDS_ID' => 2,'NAME' => 'RL','CODE' => 'acura_rl'),
			array('BRANDS_ID' => 2,'NAME' => 'RSX','CODE' => 'acura_rsx'),
			array('BRANDS_ID' => 2,'NAME' => 'TL','CODE' => 'acura_tl'),
			array('BRANDS_ID' => 2,'NAME' => 'TSX','CODE' => 'acura_tsx'),
			array('BRANDS_ID' => 2,'NAME' => 'ZDX','CODE' => 'acura_zdx'),

			array('BRANDS_ID' => 3,'NAME' => '145','CODE' => 'alfa_romeo_145'),
			array('BRANDS_ID' => 3,'NAME' => '147','CODE' => 'alfa_romeo_147'),
			array('BRANDS_ID' => 3,'NAME' => '155','CODE' => 'alfa_romeo_155'),
			array('BRANDS_ID' => 3,'NAME' => '156','CODE' => 'alfa_romeo_156'),
			array('BRANDS_ID' => 3,'NAME' => '159','CODE' => 'alfa_romeo_159'),
			array('BRANDS_ID' => 3,'NAME' => '164','CODE' => 'alfa_romeo_164'),
			array('BRANDS_ID' => 3,'NAME' => '166','CODE' => 'alfa_romeo_166'),
			array('BRANDS_ID' => 3,'NAME' => '33','CODE' => 'alfa_romeo_33'),
			array('BRANDS_ID' => 3,'NAME' => 'Brera','CODE' => 'alfa_romeo_brera'),
			array('BRANDS_ID' => 3,'NAME' => 'Giulietta','CODE' => 'alfa_romeo_giulietta'),
			array('BRANDS_ID' => 3,'NAME' => 'GT','CODE' => 'alfa_romeo_gt'),
			array('BRANDS_ID' => 3,'NAME' => 'GTV','CODE' => 'alfa_romeo_gtv'),
			array('BRANDS_ID' => 3,'NAME' => 'MiTo','CODE' => 'alfa_romeo_mito'),
			array('BRANDS_ID' => 3,'NAME' => 'Spider','CODE' => 'alfa_romeo_spider'),

			array('BRANDS_ID' => 4,'NAME' => 'B3','CODE' => 'alpina_b3'),
			array('BRANDS_ID' => 4,'NAME' => 'B6','CODE' => 'alpina_b6'),

			array('BRANDS_ID' => 5,'NAME' => 'DB9','CODE' => 'aston_martin_db9'),
			array('BRANDS_ID' => 5,'NAME' => 'V8 Vantage','CODE' => 'aston_martin_v8_vantage'),

			array('BRANDS_ID' => 6,'NAME' => '100','CODE' => 'audi_100'),
			array('BRANDS_ID' => 6,'NAME' => '200','CODE' => 'audi_200'),
			array('BRANDS_ID' => 6,'NAME' => '80','CODE' => 'audi_80'),
			array('BRANDS_ID' => 6,'NAME' => '90','CODE' => 'audi_90'),
			array('BRANDS_ID' => 6,'NAME' => 'A1','CODE' => 'audi_a1'),
			array('BRANDS_ID' => 6,'NAME' => 'A2','CODE' => 'audi_a2'),
			array('BRANDS_ID' => 6,'NAME' => 'A3','CODE' => 'audi_a3'),
			array('BRANDS_ID' => 6,'NAME' => 'A4','CODE' => 'audi_a4'),
			array('BRANDS_ID' => 6,'NAME' => 'A4 allroad quattro','CODE' => 'audi_a4_allroad_quattro'),
			array('BRANDS_ID' => 6,'NAME' => 'A5','CODE' => 'audi_a5'),
			array('BRANDS_ID' => 6,'NAME' => 'A6','CODE' => 'audi_a6'),
			array('BRANDS_ID' => 6,'NAME' => 'A6 allroad quattro','CODE' => 'audi_a6_allroad_quattro'),
			array('BRANDS_ID' => 6,'NAME' => 'A7','CODE' => 'audi_a7'),
			array('BRANDS_ID' => 6,'NAME' => 'A8','CODE' => 'audi_a8'),
			array('BRANDS_ID' => 6,'NAME' => 'Cabriolet','CODE' => 'audi_cabriolet'),
			array('BRANDS_ID' => 6,'NAME' => 'Q5','CODE' => 'audi_q5'),
			array('BRANDS_ID' => 6,'NAME' => 'Q7','CODE' => 'audi_q7'),
			array('BRANDS_ID' => 6,'NAME' => 'R8','CODE' => 'audi_r8'),
			array('BRANDS_ID' => 6,'NAME' => 'RS4','CODE' => 'audi_rs4'),
			array('BRANDS_ID' => 6,'NAME' => 'RS6','CODE' => 'audi_rs6'),
			array('BRANDS_ID' => 6,'NAME' => 'S2','CODE' => 'audi_s2'),
			array('BRANDS_ID' => 6,'NAME' => 'S3','CODE' => 'audi_s3'),
			array('BRANDS_ID' => 6,'NAME' => 'S4','CODE' => 'audi_s4'),
			array('BRANDS_ID' => 6,'NAME' => 'S5','CODE' => 'audi_s5'),
			array('BRANDS_ID' => 6,'NAME' => 'S6','CODE' => 'audi_s6'),
			array('BRANDS_ID' => 6,'NAME' => 'S8','CODE' => 'audi_s8'),

			array('BRANDS_ID' => 7,'NAME' => 'Arnage','CODE' => 'bentley_arnage'),
			array('BRANDS_ID' => 7,'NAME' => 'Azure','CODE' => 'bentley_azure'),
			array('BRANDS_ID' => 7,'NAME' => 'Continental Flying Spur','CODE' => 'bentley_continental_flying_spur'),
			array('BRANDS_ID' => 7,'NAME' => 'Continental GT','CODE' => 'bentley_continental_gt'),

			//array('BRANDS_ID' => 9,'NAME' => '','CODE' => ''),

			array('BRANDS_ID' => 8,'NAME' => '','CODE' => ''),
			array('BRANDS_ID' => 8,'NAME' => '1 series','CODE' => 'bmw_1_series'),
			array('BRANDS_ID' => 8,'NAME' => '3 series','CODE' => 'bmw_3_series'),
			array('BRANDS_ID' => 8,'NAME' => '5 series','CODE' => 'bmw_5_series'),
			array('BRANDS_ID' => 8,'NAME' => '5 Гран Туризмо','CODE' => 'bmw_5_gran_turizmo'),
			array('BRANDS_ID' => 8,'NAME' => '6 series','CODE' => 'bmw_6_series'),
			array('BRANDS_ID' => 8,'NAME' => '7 series','CODE' => 'bmw_7_series'),
			array('BRANDS_ID' => 8,'NAME' => '8','CODE' => 'bmw_8'),
			array('BRANDS_ID' => 8,'NAME' => 'M3','CODE' => 'bmw_m3'),
			array('BRANDS_ID' => 8,'NAME' => 'M5','CODE' => 'bmw_m5'),
			array('BRANDS_ID' => 8,'NAME' => 'M6','CODE' => 'bmw_m6'),
			array('BRANDS_ID' => 8,'NAME' => 'X1','CODE' => 'bmw_x1'),
			array('BRANDS_ID' => 8,'NAME' => 'X3','CODE' => 'bmw_x3'),
			array('BRANDS_ID' => 8,'NAME' => 'X5','CODE' => 'bmw_x5'),
			array('BRANDS_ID' => 8,'NAME' => 'X5 M','CODE' => 'bmw_x5_m'),
			array('BRANDS_ID' => 8,'NAME' => 'X6','CODE' => 'bmw_x6'),
			array('BRANDS_ID' => 8,'NAME' => 'X6 M','CODE' => 'bmw_x6_m'),
			array('BRANDS_ID' => 8,'NAME' => 'Z3','CODE' => 'bmw_z3'),
			array('BRANDS_ID' => 8,'NAME' => 'Z4','CODE' => 'bmw_z4'),
			array('BRANDS_ID' => 8,'NAME' => 'Z4 M','CODE' => 'bmw_z4_m'),

			array('BRANDS_ID' => 9,'NAME' => '2110','CODE' => 'bogdan_2110'),
			array('BRANDS_ID' => 9,'NAME' => '2111','CODE' => 'bogdan_2111'),
			array('BRANDS_ID' => 9,'NAME' => '2112','CODE' => 'bogdan_2112'),
			array('BRANDS_ID' => 9,'NAME' => '2310','CODE' => 'bogdan_2310'),
			array('BRANDS_ID' => 9,'NAME' => 'A092 02','CODE' => 'bogdan_a092_02'),
			array('BRANDS_ID' => 9,'NAME' => 'A092 04','CODE' => 'bogdan_a092_04'),
			array('BRANDS_ID' => 9,'NAME' => 'A092 12','CODE' => 'bogdan_a092_12'),
			array('BRANDS_ID' => 9,'NAME' => 'A092 14','CODE' => 'bogdan_a092_14'),
			array('BRANDS_ID' => 9,'NAME' => 'A092 S2','CODE' => 'bogdan_a092_s2'),

			array('BRANDS_ID' => 10,'NAME' => 'M2 (JunJie)','CODE' => 'brilliance_m2_junjie'),

			array('BRANDS_ID' => 11,'NAME' => 'Enclave','CODE' => 'bugatti_enclave'),
			array('BRANDS_ID' => 11,'NAME' => 'RendezVous','CODE' => 'bugatti_rendezvous'),
			array('BRANDS_ID' => 11,'NAME' => 'Roadmaster','CODE' => 'bugatti_roadmaster'),

			array('BRANDS_ID' => 12,'NAME' => 'BLS','CODE' => 'buick_bls'),
			array('BRANDS_ID' => 12,'NAME' => 'CTS','CODE' => 'buick_cts'),
			array('BRANDS_ID' => 12,'NAME' => 'De Ville','CODE' => 'buick_de_ville'),
			array('BRANDS_ID' => 12,'NAME' => 'Eldorado','CODE' => 'buick_eldorado'),
			array('BRANDS_ID' => 12,'NAME' => 'Escalade','CODE' => 'buick_escalade'),
			array('BRANDS_ID' => 12,'NAME' => 'Fleetwood','CODE' => 'buick_fleetwood'),
			array('BRANDS_ID' => 12,'NAME' => 'Seville','CODE' => 'buick_seville'),
			array('BRANDS_ID' => 12,'NAME' => 'SRX','CODE' => 'buick_srx'),
			array('BRANDS_ID' => 12,'NAME' => 'STS','CODE' => 'buick_sts'),
			array('BRANDS_ID' => 12,'NAME' => 'XLR','CODE' => 'buick_xlr'),

			array('BRANDS_ID' => 13,'NAME' => 'F3','CODE' => 'byd_f3'),
			array('BRANDS_ID' => 13,'NAME' => 'Flyer','CODE' => 'byd_flyer'),

			array('BRANDS_ID' => 15,'NAME' => 'CS35','CODE' => 'changan_cs35'),
			array('BRANDS_ID' => 15,'NAME' => 'Eado','CODE' => 'changan_eado'),
			array('BRANDS_ID' => 15,'NAME' => 'Raeton','CODE' => 'changan_raeton'),

			array('BRANDS_ID' => 16,'NAME' => 'A21','CODE' => 'chery_a21'),
			array('BRANDS_ID' => 16,'NAME' => 'Amulet','CODE' => 'chery_amulet'),
			array('BRANDS_ID' => 16,'NAME' => 'Bonus','CODE' => 'chery_bonus'),
			array('BRANDS_ID' => 16,'NAME' => 'Cross Eastar','CODE' => 'chery_cross_eastar'),
			array('BRANDS_ID' => 16,'NAME' => 'Fora','CODE' => 'chery_fora'),
			array('BRANDS_ID' => 16,'NAME' => 'Indis','CODE' => 'chery_indis'),
			array('BRANDS_ID' => 16,'NAME' => 'Kimo','CODE' => 'chery_kimo'),
			array('BRANDS_ID' => 16,'NAME' => 'M11','CODE' => 'chery_m11'),
			array('BRANDS_ID' => 16,'NAME' => 'Mikado Eastar','CODE' => 'chery_mikado_eastar'),
			array('BRANDS_ID' => 16,'NAME' => 'QQ','CODE' => 'chery_qq'),
			array('BRANDS_ID' => 16,'NAME' => 'QQ 6','CODE' => 'chery_qq_6'),
			array('BRANDS_ID' => 16,'NAME' => 'Tiggo','CODE' => 'chery_tiggo'),
			array('BRANDS_ID' => 16,'NAME' => 'Very','CODE' => 'chery_very'),

			array('BRANDS_ID' => 17,'NAME' => 'Alero','CODE' => 'chevrolet_alero'),
			array('BRANDS_ID' => 17,'NAME' => 'Astro','CODE' => 'chevrolet_astro'),
			array('BRANDS_ID' => 17,'NAME' => 'Avalanche','CODE' => 'chevrolet_avalanche'),
			array('BRANDS_ID' => 17,'NAME' => 'Aveo','CODE' => 'chevrolet_aveo'),
			array('BRANDS_ID' => 17,'NAME' => 'Blazer','CODE' => 'chevrolet_blazer'),
			array('BRANDS_ID' => 17,'NAME' => 'Camaro','CODE' => 'chevrolet_camaro'),
			array('BRANDS_ID' => 17,'NAME' => 'Caprice','CODE' => 'chevrolet_caprice'),
			array('BRANDS_ID' => 17,'NAME' => 'Captiva','CODE' => 'chevrolet_captiva'),
			array('BRANDS_ID' => 17,'NAME' => 'Cavalier','CODE' => 'chevrolet_cavalier'),
			array('BRANDS_ID' => 17,'NAME' => 'Cobalt','CODE' => 'chevrolet_cobalt'),
			array('BRANDS_ID' => 17,'NAME' => 'Corsica','CODE' => 'chevrolet_corsica'),
			array('BRANDS_ID' => 17,'NAME' => 'Corvette','CODE' => 'chevrolet_corvette'),
			array('BRANDS_ID' => 17,'NAME' => 'Cruze','CODE' => 'chevrolet_cruze'),
			array('BRANDS_ID' => 17,'NAME' => 'Epica','CODE' => 'chevrolet_epica'),
			array('BRANDS_ID' => 17,'NAME' => 'Equinox','CODE' => 'chevrolet_equinox'),
			array('BRANDS_ID' => 17,'NAME' => 'Evanda','CODE' => 'chevrolet_evanda'),
			array('BRANDS_ID' => 17,'NAME' => 'Express','CODE' => 'chevrolet_express'),
			array('BRANDS_ID' => 17,'NAME' => 'HHR','CODE' => 'chevrolet_hhr'),
			array('BRANDS_ID' => 17,'NAME' => 'Impala','CODE' => 'chevrolet_impala'),
			array('BRANDS_ID' => 17,'NAME' => 'Lacetti','CODE' => 'chevrolet_lacetti'),
			array('BRANDS_ID' => 17,'NAME' => 'Lanos','CODE' => 'chevrolet_lanos'),
			array('BRANDS_ID' => 17,'NAME' => 'Lumina','CODE' => 'chevrolet_lumina'),
			array('BRANDS_ID' => 17,'NAME' => 'Malibu','CODE' => 'chevrolet_malibu'),
			array('BRANDS_ID' => 17,'NAME' => 'Metro','CODE' => 'chevrolet_metro'),
			array('BRANDS_ID' => 17,'NAME' => 'Monte Carlo','CODE' => 'chevrolet_monte_carlo'),
			array('BRANDS_ID' => 17,'NAME' => 'Niva','CODE' => 'chevrolet_niva'),
			array('BRANDS_ID' => 17,'NAME' => 'Orlando','CODE' => 'chevrolet_orlando'),
			array('BRANDS_ID' => 17,'NAME' => 'Rezzo','CODE' => 'chevrolet_rezzo'),
			array('BRANDS_ID' => 17,'NAME' => 'Silverado','CODE' => 'chevrolet_silverado'),
			array('BRANDS_ID' => 17,'NAME' => 'Spark','CODE' => 'chevrolet_spark'),
			array('BRANDS_ID' => 17,'NAME' => 'Suburban','CODE' => 'chevrolet_suburban'),
			array('BRANDS_ID' => 17,'NAME' => 'Tahoe','CODE' => 'chevrolet_tahoe'),
			array('BRANDS_ID' => 17,'NAME' => 'Tracker','CODE' => 'chevrolet_tracker'),
			array('BRANDS_ID' => 17,'NAME' => 'TrailBlazer','CODE' => 'chevrolet_trailblazer'),
			array('BRANDS_ID' => 17,'NAME' => 'Uplander','CODE' => 'chevrolet_uplander'),
			array('BRANDS_ID' => 17,'NAME' => 'Viva','CODE' => 'chevrolet_viva'),

			array('BRANDS_ID' => 18,'NAME' => '300C','CODE' => 'chrysler_300c'),
			array('BRANDS_ID' => 18,'NAME' => '300M','CODE' => 'chrysler_300m'),
			array('BRANDS_ID' => 18,'NAME' => 'Cirrus','CODE' => 'chrysler_cirrus'),
			array('BRANDS_ID' => 18,'NAME' => 'Concorde','CODE' => 'chrysler_concorde'),
			array('BRANDS_ID' => 18,'NAME' => 'Crossfire','CODE' => 'chrysler_crossfire'),
			array('BRANDS_ID' => 18,'NAME' => 'Grand Voyager','CODE' => 'chrysler_grand_voyager'),
			array('BRANDS_ID' => 18,'NAME' => 'Intrepid','CODE' => 'chrysler_intrepid'),
			array('BRANDS_ID' => 18,'NAME' => 'Le Baron','CODE' => 'chrysler_le_baron'),
			array('BRANDS_ID' => 18,'NAME' => 'LHS','CODE' => 'chrysler_lhs'),
			array('BRANDS_ID' => 18,'NAME' => 'Neon','CODE' => 'chrysler_neon'),
			array('BRANDS_ID' => 18,'NAME' => 'New Yorker','CODE' => 'chrysler_new_yorker'),
			array('BRANDS_ID' => 18,'NAME' => 'Pacifica','CODE' => 'chrysler_pacifica'),
			array('BRANDS_ID' => 18,'NAME' => 'PT Cruiser','CODE' => 'chrysler_pt_cruiser'),
			array('BRANDS_ID' => 18,'NAME' => 'Sebring','CODE' => 'chrysler_sebring'),
			array('BRANDS_ID' => 18,'NAME' => 'Stratus','CODE' => 'chrysler_stratus'),
			array('BRANDS_ID' => 18,'NAME' => "Town&amp;Country",'CODE' => 'chrysler_town_amp_country'),
			array('BRANDS_ID' => 18,'NAME' => 'Vision','CODE' => 'chrysler_vision'),
			array('BRANDS_ID' => 18,'NAME' => 'Voyager','CODE' => 'chrysler_voyager'),

			array('BRANDS_ID' => 19,'NAME' => 'Berlingo','CODE' => 'citroen_berlingo'),
			array('BRANDS_ID' => 19,'NAME' => 'C-Crosser','CODE' => 'citroen_c_crosser'),
			array('BRANDS_ID' => 19,'NAME' => 'C1','CODE' => 'citroen_c1'),
			array('BRANDS_ID' => 19,'NAME' => 'C2','CODE' => 'citroen_c2'),
			array('BRANDS_ID' => 19,'NAME' => 'C3','CODE' => 'citroen_c3'),
			array('BRANDS_ID' => 19,'NAME' => 'C3 Picasso','CODE' => 'citroen_c3_picasso'),
			array('BRANDS_ID' => 19,'NAME' => 'C4','CODE' => 'citroen_c4'),
			array('BRANDS_ID' => 19,'NAME' => 'C4 Picasso','CODE' => 'citroen_c4_picasso'),
			array('BRANDS_ID' => 19,'NAME' => 'C5','CODE' => 'citroen_c5'),
			array('BRANDS_ID' => 19,'NAME' => 'C6','CODE' => 'citroen_c6'),
			array('BRANDS_ID' => 19,'NAME' => 'C8','CODE' => 'citroen_c8'),
			array('BRANDS_ID' => 19,'NAME' => 'DS3','CODE' => 'citroen_ds3'),
			array('BRANDS_ID' => 19,'NAME' => 'DS4','CODE' => 'citroen_ds4'),
			array('BRANDS_ID' => 19,'NAME' => 'DS5','CODE' => 'citroen_ds5'),
			array('BRANDS_ID' => 19,'NAME' => 'DS6','CODE' => 'citroen_ds6'),
			array('BRANDS_ID' => 19,'NAME' => 'Evasion','CODE' => 'citroen_evasion'),
			array('BRANDS_ID' => 19,'NAME' => 'Grand C4 Picasso','CODE' => 'citroen_grand_c4_picasso'),
			array('BRANDS_ID' => 19,'NAME' => 'Jumper','CODE' => 'citroen_jumper'),
			array('BRANDS_ID' => 19,'NAME' => 'Jumpy','CODE' => 'citroen_jumpy'),
			array('BRANDS_ID' => 19,'NAME' => 'Saxo','CODE' => 'citroen_saxo'),
			array('BRANDS_ID' => 19,'NAME' => 'Xantia','CODE' => 'citroen_xantia'),
			array('BRANDS_ID' => 19,'NAME' => 'XM','CODE' => 'citroen_xm'),
			array('BRANDS_ID' => 19,'NAME' => 'Xsara','CODE' => 'citroen_xsara'),
			array('BRANDS_ID' => 19,'NAME' => 'Xsara Picasso','CODE' => 'citroen_xsara_picasso'),
			array('BRANDS_ID' => 19,'NAME' => 'ZX','CODE' => 'citroen_zx'),

			array('BRANDS_ID' => 20,'NAME' => '1300','CODE' => 'dacia_1300'),
			array('BRANDS_ID' => 20,'NAME' => 'Duster','CODE' => 'dacia_duster'),
			array('BRANDS_ID' => 20,'NAME' => 'Logan','CODE' => 'dacia_logan'),
			array('BRANDS_ID' => 20,'NAME' => 'Logan MCV','CODE' => 'dacia_logan_mcv'),
			array('BRANDS_ID' => 20,'NAME' => 'Solenza','CODE' => 'dacia_solenza'),
			array('BRANDS_ID' => 20,'NAME' => 'SupeRNova','CODE' => 'dacia_supernova'),

			array('BRANDS_ID' => 21,'NAME' => 'Damas','CODE' => 'daewoo_damas'),
			array('BRANDS_ID' => 21,'NAME' => 'Espero','CODE' => 'daewoo_espero'),
			array('BRANDS_ID' => 21,'NAME' => 'Gentra','CODE' => 'daewoo_gentra'),
			array('BRANDS_ID' => 21,'NAME' => 'Kalos','CODE' => 'daewoo_kalos'),
			array('BRANDS_ID' => 21,'NAME' => 'Lacetti','CODE' => 'daewoo_lacetti'),
			array('BRANDS_ID' => 21,'NAME' => 'Lanos','CODE' => 'daewoo_lanos'),
			array('BRANDS_ID' => 21,'NAME' => 'Leganza','CODE' => 'daewoo_leganza'),
			array('BRANDS_ID' => 21,'NAME' => 'Magnus','CODE' => 'daewoo_magnus'),
			array('BRANDS_ID' => 21,'NAME' => 'Matiz','CODE' => 'daewoo_matiz'),
			array('BRANDS_ID' => 21,'NAME' => 'Musso','CODE' => 'daewoo_musso'),
			array('BRANDS_ID' => 21,'NAME' => 'Nexia','CODE' => 'daewoo_nexia'),
			array('BRANDS_ID' => 21,'NAME' => 'Nubira','CODE' => 'daewoo_nubira'),
			array('BRANDS_ID' => 21,'NAME' => 'Rezzo','CODE' => 'daewoo_rezzo'),
			array('BRANDS_ID' => 21,'NAME' => 'Sens','CODE' => 'daewoo_sens'),
			array('BRANDS_ID' => 21,'NAME' => 'Tacuma','CODE' => 'daewoo_tacuma'),
			array('BRANDS_ID' => 21,'NAME' => 'Tico','CODE' => 'daewoo_tico'),

			array('BRANDS_ID' => 22,'NAME' => 'CF','CODE' => 'daf_cf'),
			array('BRANDS_ID' => 22,'NAME' => 'FX 105','CODE' => 'daf_fx_105'),
			array('BRANDS_ID' => 22,'NAME' => 'FX 95','CODE' => 'daf_fx_95'),
			array('BRANDS_ID' => 22,'NAME' => 'LF','CODE' => 'daf_lf'),
			array('BRANDS_ID' => 22,'NAME' => 'XF','CODE' => 'daf_xf'),

			array('BRANDS_ID' => 23,'NAME' => 'Applause','CODE' => 'daihatsu_applause'),
			array('BRANDS_ID' => 23,'NAME' => 'Charade','CODE' => 'daihatsu_charade'),
			array('BRANDS_ID' => 23,'NAME' => 'Copen','CODE' => 'daihatsu_copen'),
			array('BRANDS_ID' => 23,'NAME' => 'Cuore','CODE' => 'daihatsu_cuore'),
			array('BRANDS_ID' => 23,'NAME' => 'Materia','CODE' => 'daihatsu_materia'),
			array('BRANDS_ID' => 23,'NAME' => 'Move','CODE' => 'daihatsu_move'),
			array('BRANDS_ID' => 23,'NAME' => 'Sirion','CODE' => 'daihatsu_sirion'),
			array('BRANDS_ID' => 23,'NAME' => 'Storia','CODE' => 'daihatsu_storia'),
			array('BRANDS_ID' => 23,'NAME' => 'Terios','CODE' => 'daihatsu_terios'),
			array('BRANDS_ID' => 23,'NAME' => 'Trevis','CODE' => 'daihatsu_trevis'),
			array('BRANDS_ID' => 23,'NAME' => 'YRV','CODE' => 'daihatsu_yrv'),

			array(
				'BRANDS_ID' => 24,
				'NAME' => 'On Do',
				'CODE' => 'datsun_ondo'
			),
			array('BRANDS_ID' => 24,'NAME' => 'Mi Do','CODE' => 'datsun_mido'),

			array('BRANDS_ID' => 25,'NAME' => 'Aurora','CODE' => 'derways_aurora'),
			array('BRANDS_ID' => 25,'NAME' => 'Shuttle','CODE' => 'derways_shuttle'),

			array('BRANDS_ID' => 26,'NAME' => 'Avenger','CODE' => 'dodge_avenger'),
			array('BRANDS_ID' => 26,'NAME' => 'Caliber','CODE' => 'dodge_caliber'),
			array('BRANDS_ID' => 26,'NAME' => 'Caravan','CODE' => 'dodge_caravan'),
			array('BRANDS_ID' => 26,'NAME' => 'Challenger','CODE' => 'dodge_challenger'),
			array('BRANDS_ID' => 26,'NAME' => 'Charger','CODE' => 'dodge_charger'),
			array('BRANDS_ID' => 26,'NAME' => 'Durango','CODE' => 'dodge_durango'),
			array('BRANDS_ID' => 26,'NAME' => 'Grand Caravan','CODE' => 'dodge_grand_caravan'),
			array('BRANDS_ID' => 26,'NAME' => 'Intrepid','CODE' => 'dodge_intrepid'),
			array('BRANDS_ID' => 26,'NAME' => 'Journey','CODE' => 'dodge_journey'),
			array('BRANDS_ID' => 26,'NAME' => 'Magnum','CODE' => 'dodge_magnum'),
			array('BRANDS_ID' => 26,'NAME' => 'Neon','CODE' => 'dodge_neon'),
			array('BRANDS_ID' => 26,'NAME' => 'Nitro','CODE' => 'dodge_nitro'),
			array('BRANDS_ID' => 26,'NAME' => 'Ram','CODE' => 'dodge_ram'),
			array('BRANDS_ID' => 26,'NAME' => 'Stealth','CODE' => 'dodge_stealth'),
			array('BRANDS_ID' => 26,'NAME' => 'Stratus','CODE' => 'dodge_stratus'),
			array('BRANDS_ID' => 26,'NAME' => 'Viper','CODE' => 'dodge_viper'),

			array('BRANDS_ID' => 27,'NAME' => '1041','CODE' => 'faw_1041'),
			array('BRANDS_ID' => 27,'NAME' => 'Jinn','CODE' => 'faw_jinn'),
			array('BRANDS_ID' => 27,'NAME' => 'Vita','CODE' => 'faw_vita'),

			array('BRANDS_ID' => 28,'NAME' => '365','CODE' => 'ferrari_365'),
			array('BRANDS_ID' => 28,'NAME' => 'F430','CODE' => 'ferrari_f430'),

			array('BRANDS_ID' => 29,'NAME' => '500','CODE' => 'fiat_500'),
			array('BRANDS_ID' => 29,'NAME' => 'Albea','CODE' => 'fiat_albea'),
			array('BRANDS_ID' => 29,'NAME' => 'Brava','CODE' => 'fiat_brava'),
			array('BRANDS_ID' => 29,'NAME' => 'Bravo','CODE' => 'fiat_bravo'),
			array('BRANDS_ID' => 29,'NAME' => 'Coupe','CODE' => 'fiat_coupe'),
			array('BRANDS_ID' => 29,'NAME' => 'Croma','CODE' => 'fiat_croma'),
			array('BRANDS_ID' => 29,'NAME' => 'Doblo','CODE' => 'fiat_doblo'),
			array('BRANDS_ID' => 29,'NAME' => 'Ducato','CODE' => 'fiat_ducato'),
			array('BRANDS_ID' => 29,'NAME' => 'Grande Punto','CODE' => 'fiat_grande_punto'),
			array('BRANDS_ID' => 29,'NAME' => 'Linea','CODE' => 'fiat_linea'),
			array('BRANDS_ID' => 29,'NAME' => 'Marea','CODE' => 'fiat_marea'),
			array('BRANDS_ID' => 29,'NAME' => 'Palio','CODE' => 'fiat_palio'),
			array('BRANDS_ID' => 29,'NAME' => 'Panda','CODE' => 'fiat_panda'),
			array('BRANDS_ID' => 29,'NAME' => 'Punto','CODE' => 'fiat_punto'),
			array('BRANDS_ID' => 29,'NAME' => 'Scudo','CODE' => 'fiat_scudo'),
			array('BRANDS_ID' => 29,'NAME' => 'Sedici','CODE' => 'fiat_sedici'),
			array('BRANDS_ID' => 29,'NAME' => 'Stilo','CODE' => 'fiat_stilo'),
			array('BRANDS_ID' => 29,'NAME' => 'Tempra','CODE' => 'fiat_tempra'),
			array('BRANDS_ID' => 29,'NAME' => 'Tipo','CODE' => 'fiat_tipo'),
			array('BRANDS_ID' => 29,'NAME' => 'Ulysse','CODE' => 'fiat_ulysse'),
			array('BRANDS_ID' => 29,'NAME' => 'Uno','CODE' => 'fiat_uno'),

			array('BRANDS_ID' => 30,'NAME' => 'Aerostar','CODE' => 'ford_aerostar'),
			array('BRANDS_ID' => 30,'NAME' => 'C-Max','CODE' => 'ford_c_max'),
			array('BRANDS_ID' => 30,'NAME' => 'Contour','CODE' => 'ford_contour'),
			array('BRANDS_ID' => 30,'NAME' => 'Cougar','CODE' => 'ford_cougar'),
			array('BRANDS_ID' => 30,'NAME' => 'Econoline','CODE' => 'ford_econoline'),
			array('BRANDS_ID' => 30,'NAME' => 'Econovan','CODE' => 'ford_econovan'),
			array('BRANDS_ID' => 30,'NAME' => 'Edge','CODE' => 'ford_edge'),
			array('BRANDS_ID' => 30,'NAME' => 'Escape','CODE' => 'ford_escape'),
			array('BRANDS_ID' => 30,'NAME' => 'Escort','CODE' => 'ford_escort'),
			array('BRANDS_ID' => 30,'NAME' => 'Excursion','CODE' => 'ford_excursion'),
			array('BRANDS_ID' => 30,'NAME' => 'Expedition','CODE' => 'ford_expedition'),
			array('BRANDS_ID' => 30,'NAME' => 'Explorer','CODE' => 'ford_explorer'),
			array('BRANDS_ID' => 30,'NAME' => 'F150','CODE' => 'ford_f150'),
			array('BRANDS_ID' => 30,'NAME' => 'F250','CODE' => 'ford_f250'),
			array('BRANDS_ID' => 30,'NAME' => 'F350','CODE' => 'ford_f350'),
			array('BRANDS_ID' => 30,'NAME' => 'Festiva','CODE' => 'ford_festiva'),
			array('BRANDS_ID' => 30,'NAME' => 'Fiesta','CODE' => 'ford_fiesta'),
			array('BRANDS_ID' => 30,'NAME' => 'Focus','CODE' => 'ford_focus'),
			array('BRANDS_ID' => 30,'NAME' => 'Freestar','CODE' => 'ford_freestar'),
			array('BRANDS_ID' => 30,'NAME' => 'Freestyle','CODE' => 'ford_freestyle'),
			array('BRANDS_ID' => 30,'NAME' => 'Fusion','CODE' => 'ford_fusion'),
			array('BRANDS_ID' => 30,'NAME' => 'Galaxy','CODE' => 'ford_galaxy'),
			array('BRANDS_ID' => 30,'NAME' => 'Granada','CODE' => 'ford_granada'),
			array('BRANDS_ID' => 30,'NAME' => 'Ka','CODE' => 'ford_ka'),
			array('BRANDS_ID' => 30,'NAME' => 'Kuga','CODE' => 'ford_kuga'),
			array('BRANDS_ID' => 30,'NAME' => 'Maverick','CODE' => 'ford_maverick'),
			array('BRANDS_ID' => 30,'NAME' => 'Mondeo','CODE' => 'ford_mondeo'),
			array('BRANDS_ID' => 30,'NAME' => 'Mustang','CODE' => 'ford_mustang'),
			array('BRANDS_ID' => 30,'NAME' => 'Orion','CODE' => 'ford_orion'),
			array('BRANDS_ID' => 30,'NAME' => 'Probe','CODE' => 'ford_probe'),
			array('BRANDS_ID' => 30,'NAME' => 'Puma','CODE' => 'ford_puma'),
			array('BRANDS_ID' => 30,'NAME' => 'Ranger','CODE' => 'ford_ranger'),
			array('BRANDS_ID' => 30,'NAME' => 'S-MAX','CODE' => 'ford_s_max'),
			array('BRANDS_ID' => 30,'NAME' => 'Scorpio','CODE' => 'ford_scorpio'),
			array('BRANDS_ID' => 30,'NAME' => 'Sierra','CODE' => 'ford_sierra'),
			array('BRANDS_ID' => 30,'NAME' => 'Taunus','CODE' => 'ford_taunus'),
			array('BRANDS_ID' => 30,'NAME' => 'Taurus','CODE' => 'ford_taurus'),
			array('BRANDS_ID' => 30,'NAME' => 'Thunderbird','CODE' => 'ford_thunderbird'),
			array('BRANDS_ID' => 30,'NAME' => 'Tourneo Connect','CODE' => 'ford_tourneo_connect'),
			array('BRANDS_ID' => 30,'NAME' => 'Tourneo II','CODE' => 'ford_tourneo_ii'),
			array('BRANDS_ID' => 30,'NAME' => 'Transit','CODE' => 'ford_transit'),
			array('BRANDS_ID' => 30,'NAME' => 'Windstar','CODE' => 'ford_windstar'),

			array('BRANDS_ID' => 31,'NAME' => 'Emgrand','CODE' => 'geely_emgrand'),
			array('BRANDS_ID' => 31,'NAME' => 'MK','CODE' => 'geely_mk'),
			array('BRANDS_ID' => 31,'NAME' => 'MK Cross','CODE' => 'geely_mk_cross'),
			array('BRANDS_ID' => 31,'NAME' => 'Otaka','CODE' => 'geely_otaka'),
			array('BRANDS_ID' => 31,'NAME' => 'Vision','CODE' => 'geely_vision'),

			array('BRANDS_ID' => 32,'NAME' => 'Acadia','CODE' => 'gmc_acadia'),
			array('BRANDS_ID' => 32,'NAME' => 'Envoy','CODE' => 'gmc_envoy'),
			array('BRANDS_ID' => 32,'NAME' => 'Jimmy','CODE' => 'gmc_jimmy'),
			array('BRANDS_ID' => 32,'NAME' => 'Savana','CODE' => 'gmc_savana'),
			array('BRANDS_ID' => 32,'NAME' => 'Sierra','CODE' => 'gmc_sierra'),
			array('BRANDS_ID' => 32,'NAME' => 'Suburban','CODE' => 'gmc_suburban'),
			array('BRANDS_ID' => 32,'NAME' => 'Yukon','CODE' => 'gmc_yukon'),

			array('BRANDS_ID' => 33,'NAME' => 'CoolBear','CODE' => 'great_wall_coolbear'),
			array('BRANDS_ID' => 33,'NAME' => 'Deer','CODE' => 'great_wall_deer'),
			array('BRANDS_ID' => 33,'NAME' => 'H3','CODE' => 'great_wall_h3'),
			array('BRANDS_ID' => 33,'NAME' => 'Hover','CODE' => 'great_wall_hover'),
			array('BRANDS_ID' => 33,'NAME' => 'Hover_M2','CODE' => 'great_wall_hover_m2'),
			array('BRANDS_ID' => 33,'NAME' => 'Peri','CODE' => 'great_wall_peri'),
			array('BRANDS_ID' => 33,'NAME' => 'Safe','CODE' => 'great_wall_safe'),
			array('BRANDS_ID' => 33,'NAME' => 'Sailor','CODE' => 'great_wall_sailor'),
			array('BRANDS_ID' => 33,'NAME' => 'Voleex','CODE' => 'great_wall_voleex'),

			array('BRANDS_ID' => 34,'NAME' => '3','CODE' => 'haima_3'),
			array('BRANDS_ID' => 34,'NAME' => '7','CODE' => 'haima_7'),

			array('BRANDS_ID' => 35,'NAME' => 'Accord','CODE' => 'honda_accord'),
			array('BRANDS_ID' => 35,'NAME' => 'Airwave','CODE' => 'honda_airwave'),
			array('BRANDS_ID' => 35,'NAME' => 'Capa','CODE' => 'honda_capa'),
			array('BRANDS_ID' => 35,'NAME' => 'City','CODE' => 'honda_city'),
			array('BRANDS_ID' => 35,'NAME' => 'Civic','CODE' => 'honda_civic'),
			array('BRANDS_ID' => 35,'NAME' => 'Civic Ferio','CODE' => 'honda_civic_ferio'),
			array('BRANDS_ID' => 35,'NAME' => 'Civic Hybrid','CODE' => 'honda_civic_hybrid'),
			array('BRANDS_ID' => 35,'NAME' => 'Concerto','CODE' => 'honda_concerto'),
			array('BRANDS_ID' => 35,'NAME' => 'CR-V','CODE' => 'honda_cr_v'),
			array('BRANDS_ID' => 35,'NAME' => 'Crosstour','CODE' => 'honda_crosstour'),
			array('BRANDS_ID' => 35,'NAME' => 'Domani','CODE' => 'honda_domani'),
			array('BRANDS_ID' => 35,'NAME' => 'Element','CODE' => 'honda_element'),
			array('BRANDS_ID' => 35,'NAME' => 'Fit','CODE' => 'honda_fit'),
			array('BRANDS_ID' => 35,'NAME' => 'FR-V','CODE' => 'honda_fr_v'),
			array('BRANDS_ID' => 35,'NAME' => 'HR-V','CODE' => 'honda_hr_v'),
			array('BRANDS_ID' => 35,'NAME' => 'Insight','CODE' => 'honda_insight'),
			array('BRANDS_ID' => 35,'NAME' => 'Inspire','CODE' => 'honda_inspire'),
			array('BRANDS_ID' => 35,'NAME' => 'Integra','CODE' => 'honda_integra'),
			array('BRANDS_ID' => 35,'NAME' => 'Jazz','CODE' => 'honda_jazz'),
			array('BRANDS_ID' => 35,'NAME' => 'Legend','CODE' => 'honda_legend'),
			array('BRANDS_ID' => 35,'NAME' => 'Logo','CODE' => 'honda_logo'),
			array('BRANDS_ID' => 35,'NAME' => 'Mobilio','CODE' => 'honda_mobilio'),
			array('BRANDS_ID' => 35,'NAME' => 'Mobilio Spike','CODE' => 'honda_mobilio_spike'),
			array('BRANDS_ID' => 35,'NAME' => 'Odyssey','CODE' => 'honda_odyssey'),
			array('BRANDS_ID' => 35,'NAME' => 'Orthia','CODE' => 'honda_orthia'),
			array('BRANDS_ID' => 35,'NAME' => 'Pilot','CODE' => 'honda_pilot'),
			array('BRANDS_ID' => 35,'NAME' => 'Prelude','CODE' => 'honda_prelude'),
			array('BRANDS_ID' => 35,'NAME' => 'Rafaga','CODE' => 'honda_rafaga'),
			array('BRANDS_ID' => 35,'NAME' => 'Ridgelin','CODE' => 'honda_ridgeline'),
			array('BRANDS_ID' => 35,'NAME' => 'S-MX','CODE' => 'honda_s_mx'),
			array('BRANDS_ID' => 35,'NAME' => 'Shuttle','CODE' => 'honda_shuttle'),
			array('BRANDS_ID' => 35,'NAME' => 'Stepwgn','CODE' => 'honda_stepwgn'),
			array('BRANDS_ID' => 35,'NAME' => 'Strea M','CODE' => 'honda_strea_m'),
			array('BRANDS_ID' => 35,'NAME' => 'Z','CODE' => 'honda_z'),

			array('BRANDS_ID' => 36,'NAME' => 'H1','CODE' => 'hummer_h1'),
			array('BRANDS_ID' => 36,'NAME' => 'H2','CODE' => 'hummer_h2'),
			array('BRANDS_ID' => 36,'NAME' => 'H3','CODE' => 'hummer_h3'),

			array('BRANDS_ID' => 37,'NAME' => 'Accent','CODE' => 'hyundai_accent'),
			array('BRANDS_ID' => 37,'NAME' => 'Atos','CODE' => 'hyundai_atos'),
			array('BRANDS_ID' => 37,'NAME' => 'Avante','CODE' => 'hyundai_avante'),
			array('BRANDS_ID' => 37,'NAME' => 'Azera','CODE' => 'hyundai_azera'),
			array('BRANDS_ID' => 37,'NAME' => 'Coupe','CODE' => 'hyundai_coupe'),
			array('BRANDS_ID' => 37,'NAME' => 'Elantra','CODE' => 'hyundai_elantra'),
			array('BRANDS_ID' => 37,'NAME' => 'Elantra XD','CODE' => 'hyundai_elantra_xd'),
			array('BRANDS_ID' => 37,'NAME' => 'Equus','CODE' => 'hyundai_equus'),
			array('BRANDS_ID' => 37,'NAME' => 'Galloper','CODE' => 'hyundai_galloper'),
			array('BRANDS_ID' => 37,'NAME' => 'Genesis','CODE' => 'hyundai_genesis'),
			array('BRANDS_ID' => 37,'NAME' => 'Getz','CODE' => 'hyundai_getz'),
			array('BRANDS_ID' => 37,'NAME' => 'Grandeur','CODE' => 'hyundai_grandeur'),
			array('BRANDS_ID' => 37,'NAME' => 'H1 (Starex)','CODE' => 'hyundai_h1_starex'),
			array('BRANDS_ID' => 37,'NAME' => 'H100','CODE' => 'hyundai_h100'),
			array('BRANDS_ID' => 37,'NAME' => 'H200','CODE' => 'hyundai_h200'),
			array('BRANDS_ID' => 37,'NAME' => 'i10','CODE' => 'hyundai_i10'),
			array('BRANDS_ID' => 37,'NAME' => 'i20','CODE' => 'hyundai_i20'),
			array('BRANDS_ID' => 37,'NAME' => 'i30','CODE' => 'hyundai_i30'),
			array('BRANDS_ID' => 37,'NAME' => 'i40','CODE' => 'hyundai_i40'),
			array('BRANDS_ID' => 37,'NAME' => 'ix35','CODE' => 'hyundai_ix35'),
			array('BRANDS_ID' => 37,'NAME' => 'ix55','CODE' => 'hyundai_ix55'),
			array('BRANDS_ID' => 37,'NAME' => 'Lantra','CODE' => 'hyundai_lantra'),
			array('BRANDS_ID' => 37,'NAME' => 'Lavita','CODE' => 'hyundai_lavita'),
			array('BRANDS_ID' => 37,'NAME' => 'Matrix','CODE' => 'hyundai_matrix'),
			array('BRANDS_ID' => 37,'NAME' => 'NF Sonata','CODE' => 'hyundai_nf_sonata'),
			array('BRANDS_ID' => 37,'NAME' => 'Pony','CODE' => 'hyundai_pony'),
			array('BRANDS_ID' => 37,'NAME' => 'Porter','CODE' => 'hyundai_porter'),
			array('BRANDS_ID' => 37,'NAME' => 'S Coupe','CODE' => 'hyundai_s_coupe'),
			array('BRANDS_ID' => 37,'NAME' => 'Santa Fe','CODE' => 'hyundai_santa_fe'),
			array('BRANDS_ID' => 37,'NAME' => 'Santa Fe Classic','CODE' => 'hyundai_santa_fe_classic'),
			array('BRANDS_ID' => 37,'NAME' => 'Santamo','CODE' => 'hyundai_santamo'),
			array('BRANDS_ID' => 37,'NAME' => 'Solaris','CODE' => 'hyundai_solaris'),
			array('BRANDS_ID' => 37,'NAME' => 'Sonata','CODE' => 'hyundai_sonata'),
			array('BRANDS_ID' => 37,'NAME' => 'Terracan','CODE' => 'hyundai_terracan'),
			array('BRANDS_ID' => 37,'NAME' => 'Tiburon','CODE' => 'hyundai_tiburon'),
			array('BRANDS_ID' => 37,'NAME' => 'Trajet','CODE' => 'hyundai_trajet'),
			array('BRANDS_ID' => 37,'NAME' => 'Tucson','CODE' => 'hyundai_tucson'),
			array('BRANDS_ID' => 37,'NAME' => 'Tuscani','CODE' => 'hyundai_tuscani'),
			array('BRANDS_ID' => 37,'NAME' => 'Veracruz','CODE' => 'hyundai_veracruz'),
			array('BRANDS_ID' => 37,'NAME' => 'Verna','CODE' => 'hyundai_verna'),
			array('BRANDS_ID' => 37,'NAME' => 'XG','CODE' => 'hyundai_xg'),

			array('BRANDS_ID' => 38,'NAME' => 'EX','CODE' => 'infiniti_ex'),
			array('BRANDS_ID' => 38,'NAME' => 'FX','CODE' => 'infiniti_fx'),
	 		array('BRANDS_ID' => 38,'NAME' => 'G','CODE' => 'infiniti_g'),
	 		array('BRANDS_ID' => 38,'NAME' => 'M','CODE' => 'infiniti_m'),
			array('BRANDS_ID' => 38,'NAME' => 'Q','CODE' => 'infiniti_q'),
			array('BRANDS_ID' => 38,'NAME' => 'QX','CODE' => 'infiniti_qx'),
			array('BRANDS_ID' => 38,'NAME' => 'QX4','CODE' => 'infiniti_qx4'),

			array('BRANDS_ID' => 39,'NAME' => 'Samand','CODE' => 'iran_khodro_samand'),

			array('BRANDS_ID' => 40,'NAME' => 'Axiom','CODE' => 'isuzu_axiom'),
			array('BRANDS_ID' => 40,'NAME' => 'D-Max','CODE' => 'isuzu_d_max'),
			array('BRANDS_ID' => 40,'NAME' => 'Elf','CODE' => 'isuzu_elf'),
			array('BRANDS_ID' => 40,'NAME' => 'FRR90SL','CODE' => 'isuzu_frr90sl'),
			array('BRANDS_ID' => 40,'NAME' => 'NLR85A','CODE' => 'isuzu_nlr85a'),
			array('BRANDS_ID' => 40,'NAME' => 'NMR85L','CODE' => 'isuzu_nmr85l'),
			array('BRANDS_ID' => 40,'NAME' => 'NPR75','CODE' => 'isuzu_npr75'),
			array('BRANDS_ID' => 40,'NAME' => 'Rodeo','CODE' => 'isuzu_rodeo'),
			array('BRANDS_ID' => 40,'NAME' => 'Trooper','CODE' => 'isuzu_trooper'),
			array('BRANDS_ID' => 40,'NAME' => 'Vehi Cross','CODE' => 'isuzu_vehi_cross'),

			array('BRANDS_ID' => 41,'NAME' => 'Cursor','CODE' => 'iveco_cursor'),
			array('BRANDS_ID' => 41,'NAME' => 'Daily','CODE' => 'iveco_daily'),
			array('BRANDS_ID' => 41,'NAME' => 'EuroCargo','CODE' => 'iveco_eurocargo'),
			array('BRANDS_ID' => 41,'NAME' => 'EuroStar','CODE' => 'iveco_eurostar'),
			array('BRANDS_ID' => 41,'NAME' => 'EuroTech','CODE' => 'iveco_eurotech'),
			array('BRANDS_ID' => 41,'NAME' => 'Stralis','CODE' => 'iveco_stralis'),
			array('BRANDS_ID' => 41,'NAME' => 'Trakker','CODE' => 'iveco_trakker'),
			array('BRANDS_ID' => 41,'NAME' => 'TurboStar','CODE' => 'iveco_turbostar'),
			array('BRANDS_ID' => 41,'NAME' => 'TurboTech','CODE' => 'iveco_turbotech'),

			array('BRANDS_ID' => 42,'NAME' => 'S-Typ','CODE' => 'jaguar_s_type'),
			array('BRANDS_ID' => 42,'NAME' => 'X-Type','CODE' => 'jaguar_x_type'),
			array('BRANDS_ID' => 42,'NAME' => 'XF','CODE' => 'jaguar_xf'),
			array('BRANDS_ID' => 42,'NAME' => 'XJ','CODE' => 'jaguar_xj'),
			array('BRANDS_ID' => 42,'NAME' => 'XJR','CODE' => 'jaguar_xjr'),
			array('BRANDS_ID' => 42,'NAME' => 'XJS','CODE' => 'jaguar_xjs'),
			array('BRANDS_ID' => 42,'NAME' => 'XK','CODE' => 'jaguar_xk'),

			array('BRANDS_ID' => 43,'NAME' => 'Cherokee','CODE' => 'jeep_cherokee'),
			array('BRANDS_ID' => 43,'NAME' => 'Commander','CODE' => 'jeep_commander'),
			array('BRANDS_ID' => 43,'NAME' => 'Compass','CODE' => 'jeep_compass'),
			array('BRANDS_ID' => 43,'NAME' => 'Grand Cherokee','CODE' => 'jeep_grand_cherokee'),
			array('BRANDS_ID' => 43,'NAME' => 'Liberty','CODE' => 'jeep_liberty'),
			array('BRANDS_ID' => 43,'NAME' => 'Patriot','CODE' => 'jeep_patriot'),
			array('BRANDS_ID' => 43,'NAME' => 'Wrangler','CODE' => 'jeep_wrangler'),

			array('BRANDS_ID' => 106,'NAME' => 'Baodian','CODE' => 'jmc_baodian'),

			array('BRANDS_ID' => 44,'NAME' => 'Avella','CODE' => 'kia_avella'),
			array('BRANDS_ID' => 44,'NAME' => 'Bongo 3','CODE' => 'kia_bongo_3'),
			array('BRANDS_ID' => 44,'NAME' => 'Carens','CODE' => 'kia_carens'),
			array('BRANDS_ID' => 44,'NAME' => 'Carnival','CODE' => 'kia_carnival'),
			array('BRANDS_ID' => 44,'NAME' => 'Ceed','CODE' => 'kia_ceed'),
			array('BRANDS_ID' => 44,'NAME' => 'Cerato','CODE' => 'kia_cerato'),
			array('BRANDS_ID' => 44,'NAME' => 'Clarus','CODE' => 'kia_clarus'),
			array('BRANDS_ID' => 44,'NAME' => 'GrandBird','CODE' => 'kia_grandbird'),
			array('BRANDS_ID' => 44,'NAME' => 'Joice','CODE' => 'kia_joice'),
			array('BRANDS_ID' => 44,'NAME' => 'K','CODE' => 'kia_k'),
			array('BRANDS_ID' => 44,'NAME' => 'Magentis','CODE' => 'kia_magentis'),
			array('BRANDS_ID' => 44,'NAME' => 'Mohave','CODE' => 'kia_mohave'),
			array('BRANDS_ID' => 44,'NAME' => 'Opirus','CODE' => 'kia_opirus'),
			array('BRANDS_ID' => 44,'NAME' => 'Optima','CODE' => 'kia_optima'),
			array('BRANDS_ID' => 44,'NAME' => 'Picanto','CODE' => 'kia_picanto'),
			array('BRANDS_ID' => 44,'NAME' => 'Pregio','CODE' => 'kia_pregio'),
			array('BRANDS_ID' => 44,'NAME' => 'Pride','CODE' => 'kia_pride'),
			array('BRANDS_ID' => 44,'NAME' => 'Rio','CODE' => 'kia_rio'),
			array('BRANDS_ID' => 44,'NAME' => 'Rio K2','CODE' => 'kia_rio_k2'),
			array('BRANDS_ID' => 44,'NAME' => 'Sephia','CODE' => 'kia_sephia'),
			array('BRANDS_ID' => 44,'NAME' => 'Shuma','CODE' => 'kia_shuma'),
			array('BRANDS_ID' => 44,'NAME' => 'Sorento','CODE' => 'kia_sorento'),
			array('BRANDS_ID' => 44,'NAME' => 'Soul','CODE' => 'kia_soul'),
			array('BRANDS_ID' => 44,'NAME' => 'Spectra','CODE' => 'kia_spectra'),
			array('BRANDS_ID' => 44,'NAME' => 'Sportage','CODE' => 'kia_sportage'),
			array('BRANDS_ID' => 44,'NAME' => 'Venga','CODE' => 'kia_venga'),

			array('BRANDS_ID' => 45,'NAME' => '','CODE' => ''),




		);
	}
}