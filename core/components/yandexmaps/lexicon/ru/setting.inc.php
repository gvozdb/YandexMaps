<?php

$_lang['area_yandexmaps_main'] = 'Основные';
$_lang['area_yandexmaps_fields'] = 'Поля для адреса';


// >> Основные
$_lang['setting_yandexmaps_field_full_address'] = 'Поле для записи полного адреса по маске';
$_lang['setting_yandexmaps_field_full_address_desc'] = 'Поле для записи полного полученного адреса собранного по частям, которые будут расположены в том порядке, в котором они указаны в настройке "full_address_mask".<br /><br />Можно указывать, как TV: "tv.address", так и обычное поле modx: "introtext"';

$_lang['setting_yandexmaps_field_full_address_mask'] = 'Маска для поля полного адреса';
$_lang['setting_yandexmaps_field_full_address_mask_desc'] = 'Можно указать вывод каждой части адреса так, как Вам угодно.<br /><br />2 знака "++" с обеих сторон - это обёртка для любой из возможных частей адреса. Она нужна, если одно из полей не указано в адресе, чтобы можно было удалить лишние символы, используемые для этого поля. Например: "<b>++Страна: --country--, ++</b>".<br /><br />Сами названия любой из поддерживаемых частей адреса указываются с двумя знаками "--" с обеих сторон названия. Без пробелов и других посторонних символов. Например: "<b>--house--</b>".<br /><br /><b>Поддерживаемые названия</b>: countryCode, country, region, subregion, city, district, district2, street, house, premise';

$_lang['setting_yandexmaps_admin_tv_coords'] = 'Координаты центра карты в админке по-умолчанию';
$_lang['setting_yandexmaps_admin_tv_coords_desc'] = 'Укажите координаты центра карты в TV поле админки по-умолчанию.<br />Данная настройка второстепенна к аналогичному параметру в TV поле.<br /><br />Указывается в формате: "широта,долгота". Например: "59.938418139505394,30.310238871065962"';

$_lang['setting_yandexmaps_admin_tv_zoom'] = 'Zoom для карты в админке';
$_lang['setting_yandexmaps_admin_tv_zoom_desc'] = 'Укажите приближение для карты в TV поле админки.<br />Данная настройка второстепенна к аналогичному параметру в TV поле.<br /><br />Указывается в цифрах. Возможное значение от 0 до 23. Например: "14"';
// << Основные


// >> Поля для адреса
$_lang['setting_yandexmaps_field_city'] = 'Город, деревня, село';
$_lang['setting_yandexmaps_field_city_desc'] = 'Поле для записи города, населённого пункта, деревни, села и т.п.<br /><br />Можно указывать, как TV (пример: "tv.city"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_country'] = 'Страна';
$_lang['setting_yandexmaps_field_country_desc'] = 'Поле для записи страны.<br /><br />Можно указывать, как TV (пример: "tv.country"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_countryCode'] = 'Код страны';
$_lang['setting_yandexmaps_field_countryCode_desc'] = 'Поле для записи кода страны.<br /><br />Можно указывать, как TV (пример: "tv.countryCode"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_district'] = 'Район, микрорайон';
$_lang['setting_yandexmaps_field_district_desc'] = 'Поле для записи района/микрорайона города.<br /><br />Можно указывать, как TV (пример: "tv.district"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_district2'] = 'Доп. район, микрорайон';
$_lang['setting_yandexmaps_field_district2_desc'] = 'Поле для записи дополнительного района/микрорайона города.<br />У Яндекс.Карт есть такой параметр, решил, что он кому-то может пригодиться. Бывает так, что и первое, и второе поле заполнены. А бывает, что только первое.<br /><br />Можно указывать, как TV (пример: "tv.district2"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_house'] = 'Номер дома + литера';
$_lang['setting_yandexmaps_field_house_desc'] = 'Поле для записи номера дома + литера/буквы (если есть). Примерно так: "19Б".<br /><br />Можно указывать, как TV (пример: "tv.house"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_kind'] = 'Тип адреса';
$_lang['setting_yandexmaps_field_kind_desc'] = 'Поле для записи типа адреса. В зависимости от этого значения меняется заполненность полей частей адреса. Например у kind - "hydro" не будет полей "house", "street" и т.п. За то будет заполнено поле "premise".<br /><br /><b>Возможные возвращаемые значения</b>: house - дом; street - улица; metro - метро; district - район; locality - населённый пункт; province - область; country - страна; hydro - река, озеро, ручей, водохранилище; railway - ж.д. станция; route - линия метро / шоссе / ж.д. линия; vegetation - лес, парк; cemetery - кладбище; bridge - мост; km - километр шоссе; other - разное.<br /><br />Можно указывать, как TV (пример: "tv.kind"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_premise'] = 'Помещение, название залива, реки, моря, леса, парка, острова';
$_lang['setting_yandexmaps_field_premise_desc'] = 'Поле для записи служебного помещения, названия залива, реки, моря, леса, парка, острова.<br /><br />Можно указывать, как TV (пример: "tv.premise"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_region'] = 'Регион';
$_lang['setting_yandexmaps_field_region_desc'] = 'Поле для записи региона страны.<br /><br />Можно указывать, как TV (пример: "tv.region"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_street'] = 'Улица, переулок, проспект';
$_lang['setting_yandexmaps_field_street_desc'] = 'Поле для записи улицы, переулока, проспекта.<br /><br />Можно указывать, как TV (пример: "tv.street"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_subregion'] = 'Округ';
$_lang['setting_yandexmaps_field_subregion_desc'] = 'Поле для записи округа или субрегиона, как угодно. :)<br /><br />Можно указывать, как TV (пример: "tv.subregion"), так и обычное поле modx (пример: "introtext")';

$_lang['setting_yandexmaps_field_text'] = 'Полный адрес от Яндекс.Maps';
$_lang['setting_yandexmaps_field_text_desc'] = 'Поле для записи полного адреса возвращаемого Yandex.Картами. Бывает так, что не все значения попадают в него.<br />Для более полной и корректируемой записи адреса есть настройки "full_address" и "full_address_mask".<br /><br />Можно указывать, как TV (пример: "tv.text"), так и обычное поле modx (пример: "introtext")';
// << Поля для адреса