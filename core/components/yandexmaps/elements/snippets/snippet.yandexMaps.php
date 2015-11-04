<?php
// Сниппет Yandex-Карта со своими иконками-маркерами + возможность по клику отключать определённые типы гео-объектов

$YandexMaps = $modx->getService('yandexmaps','YandexMaps',$modx->getOption('yandexmaps_core_path',null,$modx->getOption('core_path').'components/yandexmaps/').'model/yandexmaps/',$scriptProperties);
$YandexMaps->initialize($modx->context->key, $scriptProperties);

// Подключаем pdoTools и pdoFetch
$pdo = $modx->getService('pdoTools');
$pdoFetch = $modx->getService('pdoFetch');
if(!is_object($pdo)) { return '[[%yandexmaps_pdotools_install]]'; }

/* >> Параметры */
$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.yandexMaps'); // основной шаблон
$tplFiltersItemsWrapper = $modx->getOption('tplFiltersItemsWrapper', $scriptProperties, 'tpl.yandexMaps.filtersItemsWrapper'); // шаблон обёртка вывода ссылок фильтров для отображения/скрытия объектов на карте
$tplFiltersItems = $modx->getOption('tplFiltersItems', $scriptProperties, 'tpl.yandexMaps.filtersItems'); // шаблон вывода ссылок фильтров для отображения/скрытия объектов на карте

$idMap = $modx->getOption('idMap', $scriptProperties, 'yandexMap'); // id карты для html разметки и JS инициализации
$centerCoords = $modx->getOption('centerCoords', $scriptProperties, '51.1305,71.4120'); // координаты для центра карты '55.753565715196416,37.62001016381833'
$zoom = $modx->getOption('zoom', $scriptProperties, '14'); // зум (приближение)
$checkZoomRange = $modx->getOption('checkZoomRange', $scriptProperties, true); // ставить зум (приближение) так, чтобы были видны все имеющиеся на карте объекты
$addressPrefix = $modx->getOption('addressPrefix', $scriptProperties, ''); // префикс в начало адреса.. в случае если в TV параметре адреса указаны без указания страны и города
$tvAddress = $modx->getOption('tvAddress', $scriptProperties, ''); // имя TV поля, в котором записан адрес
$tvCoords = $modx->getOption('tvCoords', $scriptProperties, ''); // имя TV поля с типом yandexMaps (в которое будем записывать координаты объекта)

$objectsTypesJSON = $modx->getOption('objectsTypesJSON', $scriptProperties, ''); // JSON строка с типами объектов, родительскими id, тайтлами, иконками или пресетами, а также субфильтрами по TV (и назначение каждому варианту ответа иконки или пресета)

$id = $modx->getOption('id', $scriptProperties, $modx->resource->get('id') ); // id ресурса для вызова одиночного объекта на карте
$markerIcon = $modx->getOption('markerIcon', $scriptProperties, ''); // иконка для одиночного объекта (например: "/images/map_marker.png")
$markerPreset = $modx->getOption('markerPreset', $scriptProperties, 'islands#redDotIcon'); // ключ стиля для одиночного объекта (например: "islands#redClusterIcons" или "islands#violetDotIcon")
$markerPresetText = $modx->getOption('markerPresetText', $scriptProperties, ''); // текст для preset (для одиночного объекта)
$markerPresetFieldText = $modx->getOption('markerPresetFieldText', $scriptProperties, ''); // поле, откуда извлекать текст для preset (для одиночного объекта)

$fieldForBalloonContent = $modx->getOption('fieldForBalloonContent', $scriptProperties, ''); // какое поле ресурса использовать для описания в balloon (content, introtext, description, longtitle, pagetitle)
$fieldForHint = $modx->getOption('fieldForHint', $scriptProperties, ''); // какое поле ресурса использовать для подсказки при наведении на объект (introtext, description, menutitle, longtitle, pagetitle)
$showMoreLink = $modx->getOption('showMoreLink', $scriptProperties, false); // ставить ли ссылку "подробнее" на ресурс в конец текста в balloonContent
$showMoreLinkTpl = $modx->getOption('showMoreLinkTpl', $scriptProperties, '@INLINE <p><a href="[[~[[+id]]]]" target="_blank">Подробнее</a></p>'); // шаблон ссылки (подробнее) на ресурс для описания в balloon

$showFilter = $modx->getOption('showFilter', $scriptProperties, true); // показывать ли блок фильтрации объектов карты

$goToRes = $modx->getOption('goToRes', $scriptProperties, false); // переходить на страницу ресурса при клике на маркере
$goToResBlank = $modx->getOption('goToResBlank', $scriptProperties, true); // открывать страницу в новой вкладке, если параметр &goToRes включён
$goToJS = $modx->getOption('goToJS', $scriptProperties, ''); // простенький JS код с использованием переменной modx_id в качестве id ресурса, пример: $.fancybox({ type: 'ajax', href: '[[++site_url]]index.php?id=' + modx_id });

$showUnpublished = $modx->getOption('showUnpublished', $scriptProperties, false); // показывать неопубликованные ресурсы
$showDeleted = $modx->getOption('showDeleted', $scriptProperties, false); // показывать удалённые ресурсы
$showHidden = $modx->getOption('showHidden', $scriptProperties, true); // показывать ресурсы, скрытые в меню
$depth = $modx->getOption('depth', $scriptProperties, '4'); // уровень вложенности ресурсов

$classMapBlock = $modx->getOption('classMapBlock', $scriptProperties, 'ymBlock'); // класс для блока карты
$styleMapBlock = $modx->getOption('styleMapBlock', $scriptProperties, 'float:left; width:90%; height:100%;'); // стиль для блока карты
$idFiltersForm = $modx->getOption('idFiltersForm', $scriptProperties, 'ymFiltersForm'); // id для формы фильтров (вкл/выкл) гео-объектов
$classFiltersBlock = $modx->getOption('classFiltersBlock', $scriptProperties, 'ymFiltersBlock'); // класс для блока ссылок фильтров (вкл/выкл) гео-объектов
$styleFiltersBlock = $modx->getOption('styleFiltersBlock', $scriptProperties, 'float:left; width:10%; height:100%;'); // стиль для блока ссылок фильтров (вкл/выкл) гео-объектов
$classFiltersItem = $modx->getOption('classFiltersItem', $scriptProperties, 'ymFiltersItem'); // класс для ссылки фильтра (вкл/выкл) гео-объектов (нужен для отслеживания клика JSом)
/* << Параметры */

// >> Поле, которое нужно использовать для описания.. проверяем, является ли оно подходящим..
if($fieldForBalloonContent!='') {
	if(	$fieldForBalloonContent=='content' OR
		$fieldForBalloonContent=='introtext' OR
		$fieldForBalloonContent=='description' OR
		$fieldForBalloonContent=='longtitle' OR
		$fieldForBalloonContent=='pagetitle'
	) { } else {
		$fieldForBalloonContent = '';
	}
}
// >> Поле, которое нужно использовать для описания.. проверяем, является ли оно подходящим..

// >> Поле, которое нужно использовать для подсказки.. проверяем, является ли оно подходящим..
if($fieldForHint!='') {
	if(	$fieldForHint=='introtext' OR
		$fieldForHint=='description' OR
		$fieldForHint=='menutitle' OR
		$fieldForHint=='longtitle' OR
		$fieldForHint=='pagetitle'
	) { } else {
		$fieldForHint = '';
	}
}
// >> Поле, которое нужно использовать для подсказки.. проверяем, является ли оно подходящим..


if($tvCoords == '') { // если не указаны TVшки адреса и координат - дальше не идём..
	return;
}


// >> Если есть данные переданные с формы, то записываем их для дальнейшей работы
$ymFormData=array();
if(isset($_REQUEST['ym'])) {
	$ymFormData = $_REQUEST['ym'];
	//print_r( $ymFormData ); die;
}
// << Если есть данные переданные с формы, то записываем их для дальнейшей работы


$oneObject=false;

$geoObjectsArray=array();
$geoObjectsForYandexMapArray=array();


if($objectsTypesJSON != '') {
	$objectsTypes = $modx->fromJSON($objectsTypesJSON); // из json в массив
	//print_r( $objectsTypes ); die;

	// >> Собираем массив с данными ресурсов для отображения на карте
	for($i=0; $i<count($objectsTypes); $i++)
	{
		foreach($objectsTypes[$i] as $objectsTypesKeyId => $objectsTypesDataArray)
		{
			$includeTVs = '';
			$includeTVs .= ( $tvAddress=='' ? '' : ($includeTVs=='' ? '' : ',') . $tvAddress );
			$includeTVs .= ( $tvCoords=='' ? '' : ($includeTVs=='' ? '' : ',') . $tvCoords );

			// >> Работаем с субфильтрами (пока только tv поля)
			if( !empty($objectsTypesDataArray['subFilters']) && is_array($objectsTypesDataArray['subFilters']) )
			{
				foreach($objectsTypesDataArray['subFilters'] as $subFiltersKeyId => $subFiltersDataArray)
				{
					if($subFiltersDataArray['type'] == 'tv' OR !isset($subFiltersDataArray['type'])) {
						$includeTVs .= ($includeTVs=='' ? '' : ',') . $subFiltersKeyId; // записываем TVшки в переменную includeTVs, чтобы передать список TV в парамеры pdoFetch

						for($v=0; $v<count( $subFiltersDataArray['options'] ); $v++)
						{
							$tvFilterData = explode('==', $subFiltersDataArray['options'][$v]['value'] ); // получаем значение пункта TV, не смотря на то, записано оно с разделителем "==" или без него
							$tvFilterVal = ( isset($tvFilterData[1]) ? $tvFilterData[1] : $tvFilterData[0] );
							$tvFilterTitle = $tvFilterData[0];

							// формируем массив с возможными TV полями для фильтрации
							$subFiltersArray[ $objectsTypesKeyId ][ $subFiltersKeyId ][] = array(
									'val' => $tvFilterVal,
									'icon' => $subFiltersDataArray['options'][$v]['icon'],
									'preset' => $subFiltersDataArray['options'][$v]['preset'],
									'presetText' => $subFiltersDataArray['options'][$v]['presetText'],
									'presetFieldText' => $subFiltersDataArray['options'][$v]['presetFieldText'],
								);
						}
					}
				}
				//print_r( $subFiltersArray ); die;
				//print_r( $includeTVs ); die;
			}
			// << Работаем с субфильтрами (пока только tv поля)

			// >> Собираем массив по TV полям для параметра where, для корректировки выборки pdoFetch
			$setConfigWhere=array();
			foreach($ymFormData as $ymFormDataType => $ymFormDataArray)
			{
				foreach($ymFormDataArray as $ymFormDataKeyId => $ymFormDataSubArray)
				{
					if($ymFormDataType == 'checkboxes') {
						if($ymFormDataKeyId == $objectsTypesKeyId) {
							if($ymFormDataSubArray['val']) {
								foreach($ymFormDataSubArray as $ymFormDataSubFieldKeyId => $ymFormDataSubFieldArray)
								{
									if(is_array($ymFormDataSubFieldArray) AND count($ymFormDataSubFieldArray)) {
										$dataWhereArray=array();
										$dataArrayIN=array();
										$dataArrayNotIN=array();
										foreach($ymFormDataSubFieldArray as $key => $value)
										{
											if($value) {
												$dataArrayIN[] = $key;
											}
											else {
												$dataArrayNotIN[] = $key;
											}
										}

										if(count($dataArrayIN)) {
											$dataWhereArray[ 'TV' . $ymFormDataSubFieldKeyId . '.value:IN' ] = $dataArrayIN;
										}
										if(count($dataArrayNotIN)) {
											$dataWhereArray[ 'TV' . $ymFormDataSubFieldKeyId . '.value:NOT IN' ] = $dataArrayNotIN;
										}
										$setConfigWhere[] = $dataWhereArray;
									}
								}
							}
							else {
								$setConfigWhere = array( 'id' => '0' ); // ставим это, чтобы не отображать этот тип объектов, в случае, когда у нас в фильтре галочка отменена
							}
						}
					}
				}
			}
			//if($_POST){print_r( $setConfigWhere ); die;}
			// << Собираем массив по TV полям для параметра where, для корректировки выборки pdoFetch


			$setConfigArray['limit'] = '999999999'; // заведомо большой limit, дабы отобразить все объекты на карте
			$setConfigArray['showUnpublished'] = $showUnpublished;
			$setConfigArray['showDeleted'] = $showDeleted;
			$setConfigArray['showHidden'] = $showHidden;
			$setConfigArray['depth'] = $depth;
			$setConfigArray['parents'] = $objectsTypesDataArray['parent']; // id родителя
			$setConfigArray['includeTVs'] = $includeTVs; // tv поля
			$setConfigArray['where'] = $setConfigWhere; // условия выборки
			$setConfigArray['return'] = 'data';

			$pdoFetch->setConfig($setConfigArray); // передаём параметры в pdoFetch

			$geoObjectsArray[$i][ $objectsTypesKeyId ] = $pdoFetch->run(); // получаем массив с ресурсами для отображения на карте

			if(is_array($geoObjectsArray[$i][ $objectsTypesKeyId ]) AND count($geoObjectsArray[$i][ $objectsTypesKeyId ])) {
				for($a=0; $a<count( $geoObjectsArray[$i][ $objectsTypesKeyId ] ); $a++)
				{
					// >> Если tv поле "координаты" пусто у ресурса, то получаем координаты на Яндекс.Картах по tv полю "адрес" и сохраняем в ресурс
					if($geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] == '') {
						if($tvAddress != '') {
							if($geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvAddress ] != '') {
								$xmlContentCoordsFromYndexMaps = file_get_contents("http://geocode-maps.yandex.ru/1.x/?geocode=".urlencode($addressPrefix.$resTvAddress)."&results=200"); // получаем xml страницу с координатами на Яндекс.Картах
								preg_match('/<pos>(.*?)<\/pos>/', $xmlContentCoordsFromYndexMaps, $posCoords); // вырезаем нужные нам координаты
								$resTvCoordsArray = explode(' ', trim(strip_tags($posCoords[1])));
								$geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] = $resTvCoords = $resTvCoordsArray[1].','.$resTvCoordsArray[0]; // записываем координаты в массив с данными ресурса и в переменную
								$resObj = $modx->getObject('modResource', array( 'id' => $geoObjectsArray[$i][ $objectsTypesKeyId ][$a]['id'] ) ); // получаем объект ресурса, чтобы сохранить координаты в его TV
								$resObj->setTVValue($tvCoords, $resTvCoords); // записываем координаты в tv поле координат
								$resObj->save();
							}
						}
					}
					// << Если tv поле "координаты" пусто у ресурса, то получаем координаты на Яндекс.Картах по tv полю "адрес" и сохраняем в ресурс

					// >> Получаем описание.. добавляем ссылку "подробнее"
					$balloonContent='';
					if($fieldForBalloonContent != '') {
						$balloonContent = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $fieldForBalloonContent ];
						if($showMoreLink) {
							$balloonContent .= $pdo->getChunk($showMoreLinkTpl, array(
									'id' => $geoObjectsArray[$i][ $objectsTypesKeyId ][$a]['id']
								));
						}
					}
					// << Получаем описание.. добавляем ссылку "подробнее"

					// >> Получаем подсказку
					$hintContent='';
					if($fieldForHint != '') {
						$hintContent = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $fieldForHint ];
					}
					// << Получаем подсказку

					// >> Проверяем, указан ли адрес и координаты, и только в этом случае передаём объект для отображения на карте
					if($geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] != '') {
						// разбиваем координаты на 2 переменные, чтобы корректно передать в массив ниже
						list($coord_1, $coord_2) = explode(',', $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] );

						// >> Формируем массив с обозначением объекта на карте - точка (парам. в JSON строке: "preset") ИЛИ иконка (парам. в JSON строке: "icon")
						$objectPropertiesArray = array();
						$objectOptionsArray=array();
						$objectMarkerArray=array();

						// массив с опциями
						//$objectOptionsArray['pointOverlay'] = 'ymaps.overlay.html.Placemark';
						//$objectOptionsArray['iconLayout'] = 'ymaps.templateLayoutFactory.createClass(\'<div class="{{properties.imageClass}} _active"><div class="{{properties.imageClass}}__icon"></div><div class="{{properties.imageClass}}__title">[if properties.iconContent]<i>$[properties.iconContent]</i>[else]$[properties.hintContent][endif]</div></div>\')';

						// массив с параметрами
						$objectPropertiesArray['imageClass'] = 'search-map-result-view';

						// записываем в properties id ресурса в MODx, чтобы потом его получить от Яндекса
						$objectPropertiesArray['modx_id'] = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a]['id'];

						// собираем массив с текстом для объекта
						$objectPropertiesArray['balloonContent'] = $balloonContent;
						$objectPropertiesArray['hintContent'] = $hintContent;
						$objectPropertiesArray['clusterCaption'] = $hintContent;

						if( !empty($subFiltersArray[ $objectsTypesKeyId ]) && is_array($subFiltersArray[ $objectsTypesKeyId ]) )
						{
							foreach($subFiltersArray[ $objectsTypesKeyId ] as $iconOrPresetKey => $iconOrPresetArray)
							{
								for($o=0; $o<count($iconOrPresetArray); $o++)
								{
									if( $iconOrPresetArray[$o]['val'] == $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $iconOrPresetKey ] ) {
										if( $iconOrPresetArray[$o]['icon'] != '') {
											$objectMarkerArray = array(
													'iconLayout' => 'default#image',
													'iconImageHref' => $iconOrPresetArray[$o]['icon'],
												);
										}
										else if( $iconOrPresetArray[$o]['preset'] != '') {
											$objectMarkerArray = array(
													'preset' => $iconOrPresetArray[$o]['preset'],
												);
											// ставим текст на объекте
											if( $iconOrPresetArray[$o]['presetText'] != '') {
												$objectPropertiesArray['iconContent'] = $iconOrPresetArray[$o]['presetText'];
											}
											else if( $iconOrPresetArray[$o]['presetFieldText'] != '') {
												// проверяем, существует ли поле, из которого мы выдернем текст для preset
												if( isset( $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $iconOrPresetArray[$o]['presetFieldText'] ] ) ) {
													$objectPropertiesArray['iconContent'] = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $iconOrPresetArray[$o]['presetFieldText'] ];
												}
											}
										}
									}
								}
							}
						}

						// проверяем, не пуст ли массив с иконками объектов.. если пуст, то ставим стандартную для родителя этих объектов
						if(!count($objectMarkerArray)) {
							if( $objectsTypesDataArray['icon'] != '') {
								$objectMarkerArray = array(
										'iconLayout' => 'default#image',
										'iconImageHref' => $objectsTypesDataArray['icon'],
									);
							}
							else if( $objectsTypesDataArray['preset'] != '') {
								$objectMarkerArray = array(
										'preset' => $objectsTypesDataArray['preset'],
									);
								// ставим текст на объекте
								if( $objectsTypesDataArray['presetText'] != '') {
									$objectPropertiesArray['iconContent'] = $objectsTypesDataArray['presetText'];
								}
								else if( $objectsTypesDataArray['presetFieldText'] != '') {
									// проверяем, существует ли поле, из которого мы выдернем текст для preset
									if( isset( $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $objectsTypesDataArray['presetFieldText'] ] ) ) {
										$objectPropertiesArray['iconContent'] = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $objectsTypesDataArray['presetFieldText'] ];
									}
								}
							}
						}
						// << Формируем массив с обозначением объекта на карте - точка (парам. в JSON строке: "preset") ИЛИ иконка (парам. в JSON строке: "icon")

						// >> Записываем данные в массив, который будет передан в JSON формате в карту
						if(count($objectMarkerArray)) {
							$geoObjectsForYandexMapArray[] = array(
									'type' => 'Feature',
									'geometry' => array(
											'type' => 'Point',
											'coordinates' => array(
													(double) $coord_1,
													(double) $coord_2
												),
										),
									'properties' => $objectPropertiesArray,
									'options' => array_merge( $objectOptionsArray , $objectMarkerArray ),
								);
						}
						// << Записываем данные в массив, который будет передан в JSON формате в карту
					}
					// << Проверяем, указан ли адрес и координаты, и только в этом случае передаём объект для отображения на карте
				}
			}
		}
	}
	// << Собираем массив с данными ресурсов для отображения на карте
}
// >> Одиночный объект на карте
if(!isset($id) OR $id=='') {
	$id = $modx->resource->get('id');
}

if( isset($id) AND !count($geoObjectsForYandexMapArray) ) {
	$oneObject = true;
	$i=0;
	$objectsTypesKeyId = 'OneObject';

	$includeTVs = '';
	$includeTVs .= ( $tvAddress=='' ? '' : ($includeTVs=='' ? '' : ',') . $tvAddress );
	$includeTVs .= ( $tvCoords=='' ? '' : ($includeTVs=='' ? '' : ',') . $tvCoords );

	$setConfigArray['includeTVs'] = $includeTVs; // tv поля
	$setConfigArray['where'] = array( 'id' => $id ); // условия выборки
	$setConfigArray['return'] = 'data';

	$pdoFetch->setConfig($setConfigArray); // передаём параметры в pdoFetch

	$geoObjectsArray[$i][ $objectsTypesKeyId ] = $pdoFetch->run(); // получаем массив с ресурсом для отображения на карте

	if(is_array($geoObjectsArray[$i][ $objectsTypesKeyId ]) AND count($geoObjectsArray[$i][ $objectsTypesKeyId ])) {
		for($a=0; $a<count( $geoObjectsArray[$i][ $objectsTypesKeyId ] ); $a++)
		{
			// >> Если tv поле "координаты" пусто у ресурса, то получаем координаты на Яндекс.Картах по tv полю "адрес" и сохраняем в ресурс
			if($geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] == '') {
				if($tvAddress != '') {
					if($geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvAddress ] != '') {
						$xmlContentCoordsFromYndexMaps = file_get_contents("http://geocode-maps.yandex.ru/1.x/?geocode=".urlencode($addressPrefix.$resTvAddress)."&results=200"); // получаем xml страницу с координатами на Яндекс.Картах
						preg_match('/<pos>(.*?)<\/pos>/', $xmlContentCoordsFromYndexMaps, $posCoords); // вырезаем нужные нам координаты
						$resTvCoordsArray = explode(' ', trim(strip_tags($posCoords[1])));
						$geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] = $resTvCoords = $resTvCoordsArray[1].','.$resTvCoordsArray[0]; // записываем координаты в массив с данными ресурса и в переменную
						$resObj = $modx->getObject('modResource', array( 'id' => $geoObjectsArray[$i][ $objectsTypesKeyId ][$a]['id'] ) ); // получаем объект ресурса, чтобы сохранить координаты в его TV
						$resObj->setTVValue($tvCoords, $resTvCoords); // записываем координаты в tv поле координат
						$resObj->save();
					}
				}
			}
			// << Если tv поле "координаты" пусто у ресурса, то получаем координаты на Яндекс.Картах по tv полю "адрес" и сохраняем в ресурс

			// >> Получаем описание.. добавляем ссылку "подробнее"
			$balloonContent='';
			if($fieldForBalloonContent != '') {
				$balloonContent = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $fieldForBalloonContent ];
				if($showMoreLink) {
					$balloonContent .= $pdo->getChunk($showMoreLinkTpl, array(
							'id' => $geoObjectsArray[$i][ $objectsTypesKeyId ][$a]['id']
						));
				}
			}
			// << Получаем описание.. добавляем ссылку "подробнее"

			// >> Получаем подсказку
			$hintContent='';
			if($fieldForHint != '') {
				$hintContent = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $fieldForHint ];
			}
			// << Получаем подсказку

			// >> Проверяем, указаны ли координаты, и только в этом случае передаём объект для отображения на карте
			if($geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] != '') {
				// разбиваем координаты на 2 переменные, чтобы корректно передать в массив ниже
				list($coord_1, $coord_2) = explode(',', $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $tvCoords ] );

				// >> Формируем массив с обозначением объекта на карте - точка (парам.: "markerPreset") ИЛИ иконка (парам.: "markerIcon")
				$objectPropertiesArray = array();
				$objectOptionsArray=array();
				$objectMarkerArray=array();

				// собираем массив с текстом для объекта
				$objectPropertiesArray['balloonContent'] = $balloonContent;
				$objectPropertiesArray['hintContent'] = $hintContent;
				$objectPropertiesArray['clusterCaption'] = $hintContent;

				// проверяем, не пуст ли массив с иконками объектов.. если пуст, то ставим стандартную для родителя этих объектов
				if(!count($objectMarkerArray)) {
					if( $markerIcon != '') {
						$objectMarkerArray = array(
								'iconLayout' => 'default#image',
								'iconImageHref' => $markerIcon,
							);
					}
					else if( $markerPreset != '') {
						$objectMarkerArray = array(
								'preset' => $markerPreset,
							);
						// ставим текст на объекте
						if( $markerPresetText != '') {
							$objectPropertiesArray['iconContent'] = $markerPresetText;
						}
						else if( $markerPresetFieldText != '') {
							// проверяем, существует ли поле, из которого мы выдернем текст для preset
							if( isset( $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $markerPresetFieldText ] ) ) {
								$objectPropertiesArray['iconContent'] = $geoObjectsArray[$i][ $objectsTypesKeyId ][$a][ $markerPresetFieldText ];
							}
						}
					}
				}
				// << Формируем массив с обозначением объекта на карте - точка (парам.: "markerPreset") ИЛИ иконка (парам.: "markerIcon")

				// >> Записываем данные в массив, который будет передан в JSON формате в карту
				if(count($objectMarkerArray)) {
					$geoObjectsForYandexMapArray[] = array(
							'type' => 'Feature',
							'geometry' => array(
									'type' => 'Point',
									'coordinates' => array(
											(double) $coord_1,
											(double) $coord_2
										),
								),
							'properties' => $objectPropertiesArray,
							'options' => array_merge( $objectOptionsArray , $objectMarkerArray ),
						);
				}
				// << Записываем данные в массив, который будет передан в JSON формате в карту
			}
			// << Проверяем, указаны ли координаты, и только в этом случае передаём объект для отображения на карте
		}
	}
}
// << Одиночный объект на карте

//print_r( $geoObjectsArray ); die;
//print_r( $geoObjectsForYandexMapArray ); die;
//print_r( $objectMarkerArray ); die;
//print_r( $modx->toJSON( array('type' => 'FeatureCollection', 'features' => $geoObjectsForYandexMapArray ) ) ); die;
//print_r( array('type' => 'FeatureCollection', 'features' => $geoObjectsForYandexMapArray ) ); die;


// >> Если запрос к сниппету был передан из формы, то принтим JSON строку для Яндекс Карты и убиваем рендер всего остального
if(isset($_REQUEST['ym']) OR $_REQUEST['ymJSON']) {
	//print_r( json_encode( array('type' => 'FeatureCollection', 'features' => str_replace('"',"'",$geoObjectsForYandexMapArray) ) , JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
	print_r( $modx->toJSON( array('type' => 'FeatureCollection', 'features' => $geoObjectsForYandexMapArray ) ) );
	die;
}
// << Если запрос к сниппету был передан из формы, то принтим JSON строку для Яндекс Карты и убиваем рендер всего остального


// >> Формируем и рендерим список фильтрации объектов на карте
$filterItemsWrapper='';
if(count($objectsTypes) && $showFilter) {
	//print_r( $objectsTypes ); die;

	for($i=0; $i<count($objectsTypes); $i++)
	{
		$filterItems='';
		foreach($objectsTypes[$i] as $objectsTypesKeyId => $objectsTypesDataArray)
		{
			$filterChildItemsWrapper='';
			if( !empty($objectsTypesDataArray['subFilters']) )
			{
				foreach($objectsTypesDataArray['subFilters'] as $subFiltersKeyId => $subFiltersDataArray)
				{
					$filterChildItems='';
					if($subFiltersDataArray['type'] == 'tv' OR !isset($subFiltersDataArray['type'])) {
						for($v=0; $v<count( $subFiltersDataArray['options'] ); $v++)
						{
							$tvFilterData = explode('==', $subFiltersDataArray['options'][$v]['value'] ); // получаем значение пункта TV, не смотря на то, записано оно с разделителем "==" или без него
							$tvFilterVal = ( isset($tvFilterData[1]) ? $tvFilterData[1] : $tvFilterData[0] );
							$tvFilterTitle = $tvFilterData[0];

							$itemInputValue = 1;

							// рендерим чанк для фильтров-потомков
							$tplFiltersItemsParams = array(
									'classFiltersItem' => $classFiltersItem,
									'wrapper' => '',
									'idFilterItem' => $objectsTypesKeyId,
									'idSubFilterItem' => ucfirst($subFiltersKeyId) . $tvFilterVal ,
									'inputSufixName' => '[' . $objectsTypesKeyId . '][' . $subFiltersKeyId . '][' . $tvFilterVal . ']',
									'itemTitle' => $tvFilterTitle,
									'itemValue' => $tvFilterVal,
									'itemInputValue' => $itemInputValue,
									'classHideOrShow' => ( $itemInputValue ? 'ymFiltersItemHide' : 'ymFiltersItemShow' ),
								);

							$filterChildItems .= $pdo->getChunk($tplFiltersItems, $tplFiltersItemsParams);
						}

						if($filterChildItems != '') {
							// рендерим чанк для обёртки фильтров-потомков
							$tplFiltersItemsWrapperParams = array(
									'classFiltersItem' => $classFiltersItem,
									'wrapper' => $filterChildItems,
									'filterId' => $objectsTypesKeyId,
									'subFilterId' => ucfirst($subFiltersKeyId),
									'title' => $subFiltersDataArray['title'],
								);

							$filterChildItemsWrapper .= $pdo->getChunk($tplFiltersItemsWrapper, $tplFiltersItemsWrapperParams);
						}
					}
				}
				//print_r($filterChildItemsWrapper); die;
			}

			$itemInputValue = 1;

			// рендерим чанк для фильтров-родителей
			$tplFiltersItemsParams = array(
					'classFiltersItem' => $classFiltersItem,
					'wrapper' => $filterChildItemsWrapper,
					'idFilterItem' => $objectsTypesKeyId,
					'idSubFilterItem' => '',
					'inputSufixName' => '[' . $objectsTypesKeyId . '][val]',
					'itemTitle' => $objectsTypesDataArray['title'],
					'itemValue' => $objectsTypesDataArray['parent'],
					'itemInputValue' => $itemInputValue,
					'classHideOrShow' => ( $itemInputValue ? 'ymFiltersItemHide' : 'ymFiltersItemShow' ),
				);

			$filterItems .= $pdo->getChunk($tplFiltersItems, $tplFiltersItemsParams);
		}
		if($filterItems != '') {
			// рендерим чанк для обёртки фильтров-родителей
			$tplFiltersItemsWrapperParams = array(
					'classFiltersItem' => $classFiltersItem,
					'wrapper' => $filterItems,
					'filterId' => $i,
					'subFilterId' => '',
					'title' => '',
				);

			$filterItemsWrapper .= $pdo->getChunk($tplFiltersItemsWrapper, $tplFiltersItemsWrapperParams);
		}
	}
	//print_r( $filterItemsWrapper ); die;
}
// << Формируем и рендерим список фильтрации объектов на карте


// >> Рендерим чанк "tpl"
$tplParams = array(
		'idMap' => $idMap,
		'centerCoords' => '['.$centerCoords.']',
		'zoom' => $zoom,
		'checkZoomRange' => $checkZoomRange,
		'styleMapBlock' => $styleMapBlock,
		'styleFiltersBlock' => $styleFiltersBlock,
		'classMapBlock' => $classMapBlock,
		'classFiltersBlock' => $classFiltersBlock,
		'classFiltersItem' => $classFiltersItem,
		'idFiltersForm' => $idFiltersForm,
		'filtersFormItems' => $filterItemsWrapper,
		'goToRes' => $goToRes ? $goToRes : '0',
		'goToResBlank' => $goToResBlank ? $goToResBlank : '0',
		'goToJS' => $goToJS!='' ? $goToJS : '0',
	);

return $pdo->getChunk($tpl, $tplParams);
// << Рендерим чанк "tpl"