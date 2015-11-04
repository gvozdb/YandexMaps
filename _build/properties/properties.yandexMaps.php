<?php

$properties = array();

$tmp = array(
	'tpl' => array(
		'type' => 'textfield',
		'value' => 'tpl.yandexMaps',
	),
	'tplFiltersItemsWrapper' => array(
		'type' => 'textfield',
		'value' => 'tpl.yandexMaps.filtersItemsWrapper',
	),
	'tplFiltersItems' => array(
		'type' => 'textfield',
		'value' => 'tpl.yandexMaps.filtersItems',
	),
	'idMap' => array(
		'type' => 'textfield',
		'value' => 'yandexMap',
	),
	'centerCoords' => array(
		'type' => 'textfield',
		'value' => '55.753565715196416,37.62001016381833',
	),
	'zoom' => array(
		'type' => 'numberfield',
		'value' => 14,
	),
	'checkZoomRange' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	'tvCoords' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'tvAddress' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'addressPrefix' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'objectsTypesJSON' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'id' => array(
		'type' => 'numberfield',
		'value' => '',
	),
	'markerIcon' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'markerPreset' => array(
		'type' => 'textfield',
		'value' => 'islands#redDotIcon',
	),
	'markerPresetText' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'markerPresetFieldText' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'fieldForBalloonContent' => array(
		'type' => 'list',
		'options' => array(
			array('text' => '', 'value' => ''),
			array('text' => 'content', 'value' => 'content'),
			array('text' => 'introtext', 'value' => 'introtext'),
			array('text' => 'description', 'value' => 'description'),
			array('text' => 'longtitle', 'value' => 'longtitle'),
			array('text' => 'pagetitle', 'value' => 'pagetitle'),
		),
		'value' => '',
	),
	'fieldForHint' => array(
		'type' => 'list',
		'options' => array(
			array('text' => '', 'value' => ''),
			array('text' => 'introtext', 'value' => 'introtext'),
			array('text' => 'description', 'value' => 'description'),
			array('text' => 'menutitle', 'value' => 'menutitle'),
			array('text' => 'longtitle', 'value' => 'longtitle'),
			array('text' => 'pagetitle', 'value' => 'pagetitle'),
		),
		'value' => '',
	),
	'showMoreLink' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'showMoreLinkTpl' => array(
		'type' => 'textfield',
		'value' => '@INLINE <p><a href="[[~[[+id]]]]" target="_blank">Подробнее</a></p>',
	),
	
	'showFilter' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	
	'goToRes' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'goToResBlank' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	'goToJS' => array(
		'type' => 'textfield',
		'value' => '',
	),
	
	'showUnpublished' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'showDeleted' => array(
		'type' => 'combo-boolean',
		'value' => false,
	),
	'showHidden' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	'depth' => array(
		'type' => 'numberfield',
		'value' => '4',
	),
	
	'classMapBlock' => array(
		'type' => 'textfield',
		'value' => 'ymBlock',
	),
	'styleMapBlock' => array(
		'type' => 'textfield',
		'value' => 'float:left; width:90%; height:100%;',
	),
	'idFiltersForm' => array(
		'type' => 'textfield',
		'value' => 'ymFiltersForm',
	),
	'classFiltersBlock' => array(
		'type' => 'textfield',
		'value' => 'ymFiltersBlock',
	),
	'styleFiltersBlock' => array(
		'type' => 'textfield',
		'value' => 'float:left; width:10%; height:100%;',
	),
	'classFiltersItem' => array(
		'type' => 'textfield',
		'value' => 'ymFiltersItem',
	),
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;