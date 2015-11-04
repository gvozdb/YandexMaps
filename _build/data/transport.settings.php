<?php

$settings = array();

$tmp = array(
	'field_full_address' => array(
		'xtype' => 'textfield',
		'value' => '',
		'area' => 'yandexmaps_main',
	),
	'field_full_address_mask' => array(
		'xtype' => 'textfield',
		'value' => "++Страна: --country--, ++++--region--, ++++--subregion--, ++++Город: --city--, ++++Район: --district--, ++++--district2--, ++++--street--, ++++д. --house--, ++++--premise--++",
		'area' => 'yandexmaps_main',
	),
	'admin_tv_coords' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_main',
	),
	'admin_tv_zoom' => array(
		'xtype' => 'numberfield',
		'value' => '',
		'area' => 'yandexmaps_main',
	),
	
	'field_country' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_countryCode' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_region' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_subregion' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_city' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_district' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_district2' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_street' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_premise' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_house' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_text' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	'field_kind' => array(
		'xtype' => 'textfield',
		'value' => "",
		'area' => 'yandexmaps_fields',
	),
	
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'yandexmaps_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
