<?php
/*
 * Properties Russian Lexicon Entries for YandexMaps
 *
 * */

$_lang['yandexmaps_prop_tpl'] = 'Основной шаблон';
$_lang['yandexmaps_prop_tplFiltersItemsWrapper'] = 'Шаблон обёртка вывода ссылок фильтров для отображения/скрытия объектов на карте';
$_lang['yandexmaps_prop_tplFiltersItems'] = 'Шаблон вывода ссылок фильтров для отображения/скрытия объектов на карте';
$_lang['yandexmaps_prop_idMap'] = 'Id карты для html разметки и JS инициализации';
$_lang['yandexmaps_prop_centerCoords'] = 'Координаты для центра карты';
$_lang['yandexmaps_prop_zoom'] = 'Zoom карты (приближение)';
$_lang['yandexmaps_prop_checkZoomRange'] = 'Ставить zoom карты (приближение) так, чтобы были видны все имеющиеся на карте маркеры';
$_lang['yandexmaps_prop_tvCoords'] = 'Имя TV поля с типом yandexMaps (в которое будем записывать координаты объекта)';
$_lang['yandexmaps_prop_tvAddress'] = 'Имя TV поля с адресом объекта (если хотим, чтобы компонент сам определил координаты для каждого объекта по его адресу в ТВшке)';
$_lang['yandexmaps_prop_addressPrefix'] = 'Префикс в начало адреса. Если в TV поле "tvAddress" все адреса указаны без обозначения страны и города. Например: "Россия, Москва, "';
$_lang['yandexmaps_prop_objectsTypesJSON'] = 'JSON строка с типами объектов, родительскими id, тайтлами, иконками или пресетами, а также субфильтрами по TV (и назначение каждому варианту ответа иконки или пресета)';
$_lang['yandexmaps_prop_id'] = 'Id ресурса для вызова одиночного объекта на карте. Например: [[*id]]';
$_lang['yandexmaps_prop_markerIcon'] = 'Иконка для одиночного объекта. Например: "/images/map_marker.png"';
$_lang['yandexmaps_prop_markerPreset'] = 'Ключ стиля для одиночного объекта. Например: "islands#yellowStretchyIcon" или "islands#violetDotIcon"';
$_lang['yandexmaps_prop_markerPresetText'] = 'Текст для одиночного объекта на preset. Например: "Плавательный бассейн"';
$_lang['yandexmaps_prop_markerPresetFieldText'] = 'Поле, откуда извлекать текст для одиночного объекта на preset. Например: "menutitle"';
$_lang['yandexmaps_prop_fieldForBalloonContent'] = 'Какое поле ресурса использовать для описания в balloon';
$_lang['yandexmaps_prop_fieldForHint'] = 'Какое поле ресурса использовать для подсказки при наведении на объект';
$_lang['yandexmaps_prop_showMoreLink'] = 'Ставить ли ссылку "подробнее" на ресурс в конец текста в balloonContent';
$_lang['yandexmaps_prop_showMoreLinkTpl'] = 'Шаблон ссылки "подробнее" на ресурс в конце текста в balloonContent';

$_lang['yandexmaps_prop_showFilter'] = 'Показывать ли блок фильтрации объектов карты';
$_lang['yandexmaps_prop_goToRes'] = 'Переходить на страницу ресурса при клике на маркере';
$_lang['yandexmaps_prop_goToResBlank'] = 'Открывать страницу в новой вкладке, если параметр &goToRes включён';
$_lang['yandexmaps_prop_goToJS'] = 'Простенький JS код с использованием переменной modx_id в качестве id ресурса, пример: "$.fancybox({ type: \'ajax\', href: \'[[++site_url]]index.php?id=\' + modx_id });". Внимание! Если используете этот пример, то FancyBox должен вызываться раньше сниппета YandexMaps!';
$_lang['yandexmaps_prop_showUnpublished'] = 'Показывать неопубликованные ресурсы';
$_lang['yandexmaps_prop_showDeleted'] = 'Показывать удалённые ресурсы';
$_lang['yandexmaps_prop_showHidden'] = 'Показывать ресурсы, скрытые в меню';
$_lang['yandexmaps_prop_depth'] = 'Уровень вложенности ресурсов';

$_lang['yandexmaps_prop_classMapBlock'] = 'Класс для блока карты';
$_lang['yandexmaps_prop_styleMapBlock'] = 'Стиль для блока карты';
$_lang['yandexmaps_prop_idFiltersForm'] = 'Id для формы фильтров (вкл/выкл) гео-объектов';
$_lang['yandexmaps_prop_classFiltersBlock'] = 'Класс для блока ссылок фильтров (вкл/выкл) гео-объектов';
$_lang['yandexmaps_prop_styleFiltersBlock'] = 'Стиль для блока ссылок фильтров (вкл/выкл) гео-объектов';
$_lang['yandexmaps_prop_classFiltersItem'] = 'Класс для ссылки фильтра (вкл/выкл) гео-объектов (нужен для отслеживания клика JSом)';