<div class="yandexmaps-map-blocks-wrapper">
	<form id="yandexmaps-search-form">
		<input type="text" id="suggest" class="x-form-text x-form-field yandexmaps-form-text" placeholder="{$modx->lexicon('yandexmaps_search')}" value="" />
		<input type="submit" class="x-btn primary-button yandexmaps-form-submit" value="Найти" />
	</form>
	<div class="yandexmaps-map-wrapper">
		<div id="tv{$tv->id}YaMap" class="yandexmaps-map"></div>
	</div>
	<div class="x-form-element">
		<input type="text" id="tv{$tv->id}" name="tv{$tv->id}" value="{$tv->value}" class="textfield" placeholder="{$modx->lexicon('yandexmaps_coords')}" />
	</div>
</div>


<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function(){
	var fld = MODx.load({
		{/literal}
		xtype: 'textfield'
		,applyTo: 'tv{$tv->id}'
		,width: '99%'
		,id: 'tv{$tv->id}'
		,enableKeyEvents: true
		,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
		,value: '{$tv->value}'
		{literal}
		,listeners: { 'change': { fn:MODx.fireResourceFormChange, scope:this}}
	});
	Ext.getCmp('modx-panel-resource').getForm().add(fld);
	MODx.makeDroppable(fld);
});
{/literal}
// ]]>
</script>


{$field_full_address_mask = $_config.yandexmaps_field_full_address_mask}

{$fields =
	[
		'field_full_address'	=> $_config.yandexmaps_field_full_address,
		'country'				=> $_config.yandexmaps_field_country,
		'countryCode'			=> $_config.yandexmaps_field_countryCode,
		'region'				=> $_config.yandexmaps_field_region,
		'subregion'				=> $_config.yandexmaps_field_subregion,
		'city'					=> $_config.yandexmaps_field_city,
		'district'				=> $_config.yandexmaps_field_district,
		'district2'				=> $_config.yandexmaps_field_district2,
		'street'				=> $_config.yandexmaps_field_street,
		'premise'				=> $_config.yandexmaps_field_premise,
		'house'					=> $_config.yandexmaps_field_house,
		'text'					=> $_config.yandexmaps_field_text,
		'kind'					=> $_config.yandexmaps_field_kind
	]}

{$fields_from_mask =
	[
		'country'		=> 'country',
		'countryCode'	=> 'countryCode',
		'region'		=> 'administrativeArea',
		'subregion'		=> 'subAdministrativeArea',
		'city'			=> 'locality',
		'district'		=> 'dependentLocality',
		'district2'		=> 'dependentLocality2',
		'street'		=> 'thoroughfare',
		'premise'		=> 'premise',
		'house'			=> 'premiseNumber',
		'text'			=> 'text'
	]}


{foreach from=$fields key=name item=item}
	
	{if $item != ''}
		
		{if substr($item, 0,3) == 'tv.'}
			
			{$ym_fields[$name] = str_replace('tv.','', $item)}
			{$input_ids_data = "tv`$modx->getObject('modTemplateVar', ['name'=>$ym_fields[$name]] )->id`"}
			
			{if $input_ids_data != 'tv' AND $input_ids_data != ''}
				
				{$input_ids[$name] = $input_ids_data}
				
			{/if}
			
		{else}
			
			{$ym_fields[$name] = $item}
			{$input_ids[$name] = "modx-resource-`$ym_fields[$name]`"}
			
		{/if}
		
	{/if}
	
{/foreach}


<script type="text/javascript">
	var field_full_address_mask = "{str_replace( array('"', '\'') , array('\\"', '\\\''), $field_full_address_mask )}";
	var input_ids = {literal}{{/literal}{foreach from=$input_ids key=name item=item name=ids}'{$name}':'{$item}'{if $smarty.foreach.ids.last} {else},{/if}{/foreach}{literal}}{/literal};
</script>


<script type="text/javascript">
// Функция подгрузки подсказок
function onLoad (ymaps)
{
	window.suggestView = new ymaps.SuggestView('suggest', {literal}{'results':'9'}{/literal});
}

// Функция замены строки
function str_replace (needle, replacement, haystack)
{
	var temp = haystack.split(needle);
	return temp.join(replacement);
}
</script>


<script type="text/javascript">
// <![CDATA[
{literal}
Ext.onReady(function(){
	{/literal}
	var myPlacemark,
		coords{$tv->id}YaMap = [ {$tv->value} ],
		coordsCenter{$tv->id}YaMap = [ {$tv->value} ];
	
	if(coordsCenter{$tv->id}YaMap.length == 0) {
		var coordsCenter{$tv->id}YaMap = [ {if $params['adminCoords']!=''}{$params['adminCoords']}{else}{if $_config.yandexmaps_admin_tv_coords!=''}{$_config.yandexmaps_admin_tv_coords}{else}59.93730360567001,30.312010200408867{/if}{/if} ];
	}
	
	{literal}
	ymaps.ready()
	.done(function (ymaps) {
		
		var {/literal}myYandexMap{$tv->id}{literal} = new ymaps.Map('{/literal}tv{$tv->id}YaMap{literal}', {
			center: {/literal}coordsCenter{$tv->id}YaMap{literal}
			, zoom: {/literal}{if $params['adminZoom']!=''}{$params['adminZoom']}{else}{if $_config.yandexmaps_admin_tv_zoom!=''}{$_config.yandexmaps_admin_tv_zoom}{else}14{/if}{/if}{literal}
			, controls: [ 'zoomControl', 'fullscreenControl' ] /* 'searchControl' */
		});
		
		
		// >> В случае установленных координат в TV ставим метку при загрузке страницы
		if({/literal}coords{$tv->id}YaMap.length{literal} != 0) {
			setPlacemark({/literal}coords{$tv->id}YaMap{literal});
		}
		// << В случае установленных координат в TV ставим метку при загрузке страницы
		
		var searchControl = new ymaps.control.SearchControl({
				'noPlacemark': true,
				'noPopup': true
			});
		
		//var searchControl = {/literal}myYandexMap{$tv->id}{literal}.controls.get('searchControl');
        /*searchControl.options.set('size', 'medium');
		
		// Получаем лейаут контрола
        searchControl.getLayout().then(function (layout) {
			layout.openPanel(); 
        });*/
		
		/* >> Обрабатываем поиск по карте из нашей формы */
		var searchForm = $("#yandexmaps-search-form");
		
		searchForm.bind('submit', function (e) {
			var address = $('.yandexmaps-form-text').val();
			
			getCoords(address, true);
			
			e.preventDefault();
		});
		/* << Обрабатываем поиск по карте из нашей формы */
		
		
		// >> Слушаем клик на подсказках в поиске
		suggestView.events.add('select', function (e) {
			var address = e.get('item').value;
			//console.log( address );
			
			getCoords(address, true);
			
			//console.log( address );
		});
		// << Слушаем клик на подсказках в поиске
		
		
		// >> Слушаем клик на карте
		{/literal}myYandexMap{$tv->id}{literal}.events.add('click', function (e) {
			var coords = e.get('coords');
			
			setPlacemark(coords, true);
		});
		// << Слушаем клик на карте
		
		
		// >> Слушаем изменения нашего TV
		$("#{/literal}tv{$tv->id}{literal}").change(function() {
			var coords = $(this).val().split(',');
			
			setPlacemark(coords, true);
			
			{/literal}myYandexMap{$tv->id}{literal}.setCenter(coords); // ставим центр карты на маркер
		});
		// >> Слушаем изменения нашего TV
		
		
		// >> Ставим метку
		function setPlacemark(coords, pasteToFields, resObject)
		{
			if ( typeof resObject === "undefined" && resObject === null )
			{
				if ( typeof resObject.geoObjects === "undefined" && resObject.geoObjects === null )
				{
					resObject = null;
				}
			}
			
			if ( typeof pasteToFields === "undefined" || pasteToFields === null )
			{
				var pasteToFields = null;
			}
			
			// Если метка уже создана – просто передвигаем ее
			if ( typeof myPlacemark !== "undefined" && myPlacemark !== null )
			{
				//alert( 'Если метка уже создана' );
				myPlacemark.geometry.setCoordinates(coords);
				
				//console.log( coords );
			}
			// Если нет – создаем
			else {
				//alert( 'Если нет – создаем' );
				myPlacemark = createPlacemark(coords);
				{/literal}myYandexMap{$tv->id}{literal}.geoObjects.add(myPlacemark);
			}
			
			//alert( '0' );
			
			getAddress(coords, pasteToFields, resObject);
			
			//alert( '1' );
		}
		// << Ставим метку
		
		
		// Слушаем событие окончания перетаскивания на метке
		{/literal}myYandexMap{$tv->id}{literal}.geoObjects.events.add('dragend', function () {
			//console.log( myPlacemark.geometry.getCoordinates() );
			setPlacemark(myPlacemark.geometry.getCoordinates(), true);
		});
		
		
		// >> Создание метки
		function createPlacemark(coords)
		{
			return new ymaps.Placemark(coords, {
				iconContent: 'Ищем адрес....',
				imageClass: 'search-map-result-view'
			}, {
				preset: 'islands#redStretchyIcon',
				pointOverlay: ymaps.overlay.html.Placemark,
				pane: 'overlaps',
				iconLayout: ymaps.templateLayoutFactory.createClass('<div id="marker_{{properties.modx_id}}" class="{{properties.imageClass}} _active"><div class="{{properties.imageClass}}__overlay"></div><div class="{{properties.imageClass}}__icon"></div><div class="{{properties.imageClass}}__title">[if properties.iconContent]<i>$[properties.iconContent]</i>[else]$[properties.hintContent][endif]</div></div>'),
				draggable: true
			});
		}
		// << Создание метки
		
		
		// >> Определяем координаты по адресу (геокодирование)
		function getCoords(address, pasteToFields)
		{
			searchControl.search( address ).then(function (res) {
				//console.log( res );
				if ( typeof res !== "undefined" && res !== null )
				{
					if ( typeof res.geoObjects !== "undefined" && res.geoObjects !== null )
					{
						var getGeoObject = res.geoObjects;
						//console.log( res.geoObjects.properties.get('metaDataProperty') );
						
						if ( typeof getGeoObject.properties !== "undefined" && getGeoObject.properties !== null )
						{
								if ( typeof getGeoObject.properties.get('metaDataProperty') !== "undefined"
									&& getGeoObject.properties.get('metaDataProperty') !== null )
								{
									if ( typeof getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData !== "undefined"
										&& getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData !== null )
									{
										//console.log( getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData );
										
										if ( typeof getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point !== "undefined"
											&& getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point !== null )
										{
											//console.log( getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point );
											
											if ( typeof getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point.coordinates !== "undefined"
												&& getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point.coordinates !== null )
											{
												//console.log( getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point.coordinates );
												
												var coords_arr = getGeoObject.properties.get('metaDataProperty').GeocoderResponseMetaData.Point.coordinates; // .join(',') .split(',')
												var coords = [ coords_arr[1] , coords_arr[0] ];
												//console.log( coords );
												
												setPlacemark(coords, pasteToFields, res);
												
												//alert( '2' );
												
												{/literal}myYandexMap{$tv->id}{literal}.setCenter(coords); // ставим центр карты на маркер
											}
										}
									}
								}
							
						}
					}
				}
			});
			
			/* Производит поиск криво.. объект находится не там, где надо, а чуть смещён..
			ymaps.geocode(address).then(function (res)
			{
				if ( typeof res !== "undefined" && res !== null )
				{
					if ( typeof res.geoObjects !== "undefined" && res.geoObjects !== null )
					{
						var firstGeoObject = res.geoObjects.get(0);
						
						if ( typeof firstGeoObject.properties !== "undefined"
							&& firstGeoObject.properties !== null )
						{
							//console.log( firstGeoObject.properties );
							
							if ( typeof firstGeoObject.properties.get(0).boundedBy !== "undefined"
								&& firstGeoObject.properties.get(0).boundedBy !== null )
							{
								var coords = firstGeoObject.properties.get(0).boundedBy[0]; // .join(',') .split(',')
								//console.log( coords );
								
								setPlacemark(coords, true, res);
								
								{/literal}myYandexMap{$tv->id}{literal}.setCenter(coords); // ставим центр карты на маркер
							}
						}
					}
				}
			});
			*/
		}
		// << Определяем координаты по адресу (геокодирование)
		
		
		// >> Определяем адрес по координатам (обратное геокодирование) + вставляем координаты в TV поле
		function getAddress(coords, pasteToFields, resObject)
		{
			myPlacemark.properties.set('iconContent', 'Ищем адрес....');
			
			/*if ( typeof resObject !== "undefined" && resObject !== null )
			{
				getAddressPartsAndPasteToFields( resObject, pasteToFields );
			}
			else
			{
				ymaps.geocode(coords).then(function (res) {
					
					getAddressPartsAndPasteToFields( res, pasteToFields );
					
				});
			}*/
			
			ymaps.geocode(coords).then(function (res) {
				
				getAddressPartsAndPasteToFields( res, pasteToFields );
				
			});
			
			$('#{/literal}tv{$tv->id}{literal}').val( coords.join(',') );
			
			//alert( coords );
		}
		// << Определяем адрес по координатам (обратное геокодирование) + вставляем координаты в TV поле
		
		
		// >> Определяем координаты по адресу (геокодирование)
		function getAddressPartsAndPasteToFields(resObject, pasteToFields)
		{
			//alert( pasteToFields );
			if ( typeof resObject !== "undefined" && resObject !== null )
			{
				if ( typeof resObject.geoObjects !== "undefined" && resObject.geoObjects !== null )
				{
					var firstGeoObject = resObject.geoObjects.get(0);
					
					myPlacemark.properties
						.set({
							iconContent: firstGeoObject.properties.get('name'),
							balloonContent: firstGeoObject.properties.get('text')
						});
					
					//alert( firstGeoObject.properties.get('text') );
					
					
					
					if ( typeof pasteToFields !== "undefined" && pasteToFields !== null )
					{
						
						var	country					= null,
							countryCode				= null,
							administrativeArea		= null,
							subAdministrativeArea	= null,
							locality				= null,
							dependentLocality		= null,
							dependentLocality2		= null,
							thoroughfare			= null,
							premise					= null,
							premiseNumber			= null,
							text					= null,
							kind					= null;
						
						// получаем данные адреса
						var addressGeoObject = firstGeoObject.properties.get('metaDataProperty').GeocoderMetaData;
						//console.log( addressGeoObject );
						
						
						//if ( typeof addressGeoObject !== "undefined" && addressGeoObject !== null )
						if ( firstGeoObject.properties.get('metaDataProperty').hasOwnProperty('GeocoderMetaData') )
						{
							var kind = addressGeoObject.kind;
							var text = addressGeoObject.text;
							//console.log( addressGeoObject.hasOwnProperty('AddressDetails') );
							
							// страна и код страны
							//if ( typeof addressGeoObject.AddressDetails.Country !== "undefined"
							//	&& addressGeoObject.AddressDetails.Country !== null )
							if ( addressGeoObject.AddressDetails.hasOwnProperty('Country') )
							{
								//if ( typeof addressGeoObject.AddressDetails.Country.CountryName !== "undefined"
								//	&& addressGeoObject.AddressDetails.Country.CountryName !== null )
								if ( addressGeoObject.AddressDetails.Country.hasOwnProperty('CountryName') )
								{
									var country = addressGeoObject.AddressDetails.Country.CountryName;
								}
								//if ( typeof addressGeoObject.AddressDetails.Country.CountryNameCode !== "undefined"
								//	&& addressGeoObject.AddressDetails.Country.CountryNameCode !== null )
								if ( addressGeoObject.AddressDetails.Country.hasOwnProperty('CountryNameCode') )
								{
									var countryCode = addressGeoObject.AddressDetails.Country.CountryNameCode;
								}
								//console.log( addressGeoObject );
								//console.log( country + ' ' + countryCode );
								
								// округ (регион, область)
								//if ( typeof addressGeoObject.AddressDetails.Country.AdministrativeArea !== "undefined"
								//	&& addressGeoObject.AddressDetails.Country.AdministrativeArea !== null )
								if ( addressGeoObject.AddressDetails.Country.hasOwnProperty('AdministrativeArea') )
								{
									var administrativeArea = addressGeoObject.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
									var administrativeAreaObject = addressGeoObject.AddressDetails.Country.AdministrativeArea;
									//console.log( administrativeAreaObject );
									
									// 1) район, если kind = "area"
									// 2) округ, если kind = "house"
									//if ( typeof addressGeoObject.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea !== "undefined"
									//	&& addressGeoObject.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea !== null )
									if ( addressGeoObject.AddressDetails.Country.AdministrativeArea.hasOwnProperty('SubAdministrativeArea') )
									{
										var subAdministrativeArea = addressGeoObject.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName;
										var administrativeAreaObject = addressGeoObject.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea;
									}
								}
								else
								{
									var administrativeAreaObject = addressGeoObject.AddressDetails.Country;
								}
								
								
								if ( typeof administrativeAreaObject !== "undefined"
									&& administrativeAreaObject !== null )
								
								{
									//console.log( administrativeAreaObject );
									var localityObject = '';
									
									// город (населённый пункт, деревня)
									//if ( typeof administrativeAreaObject.Locality !== "undefined"
									//	&& administrativeAreaObject.Locality !== null )
									if ( administrativeAreaObject.hasOwnProperty('Locality') )
									{
										//if ( typeof administrativeAreaObject.Locality.LocalityName !== "undefined"
										//	&& administrativeAreaObject.Locality.LocalityName !== null )
										if ( administrativeAreaObject.Locality.hasOwnProperty('LocalityName') )
										{
											var locality = administrativeAreaObject.Locality.LocalityName;
										}
										var localityObject = administrativeAreaObject.Locality;
									}
									//console.log( localityObject );
									
									// 1) район, если kind = "district"
									// 2) аэропорт, если kind = "airport"
									//if ( typeof localityObject.DependentLocality !== "undefined"
									//	&& localityObject.DependentLocality !== null )
									if ( localityObject.hasOwnProperty('DependentLocality') )
									{
										var dependentLocality = localityObject.DependentLocality.DependentLocalityName;
										
										//if ( typeof localityObject.DependentLocality.Thoroughfare !== "undefined"
										//	&& localityObject.DependentLocality.Thoroughfare !== null )
										if ( localityObject.DependentLocality.hasOwnProperty('Thoroughfare') )
										{
											var localityObject = localityObject.DependentLocality;
										}
										//console.log( localityObject );
										
										//if ( typeof localityObject.DependentLocality.DependentLocality !== "undefined"
										//	&& localityObject.DependentLocality.DependentLocality !== null )
										if ( localityObject.hasOwnProperty('DependentLocality') )
										{
											//console.log( localityObject );
											var dependentLocality2 = localityObject.DependentLocality.DependentLocalityName;
											
											//if ( typeof localityObject.DependentLocality.DependentLocality.Thoroughfare !== "undefined"
											//	&& localityObject.DependentLocality.DependentLocality.Thoroughfare !== null )
											if ( localityObject.DependentLocality.hasOwnProperty('Thoroughfare') )
											{
												var localityObject = localityObject.DependentLocality;
											}
										}
										//console.log( localityObject );
									}
									
									// улица, проспект, авеню
									//if ( typeof localityObject.Thoroughfare !== "undefined"
									//	&& localityObject.Thoroughfare !== null )
									if ( localityObject.hasOwnProperty('Thoroughfare') )
									{
										var thoroughfare = localityObject.Thoroughfare.ThoroughfareName;
										var localityObject = localityObject.Thoroughfare;
									}
									//console.log( localityObject );
									
									
									//if ( typeof localityObject.Premise !== "undefined"
									//	&& localityObject.Premise !== null )
									if ( localityObject.hasOwnProperty('Premise') )
									{
										// 1) служебное помещение (территория, возвышенность), если kind = "other"
										// 2) название залива, реки, моря, если kind = "hydro"
										// 3) название лесопарка, если kind = "vegetation"
										//if ( typeof localityObject.Premise.PremiseName !== "undefined"
										//	&& localityObject.Premise.PremiseName !== null )
										if ( localityObject.Premise.hasOwnProperty('PremiseName') )
										{
											var premise = localityObject.Premise.PremiseName;
										}
										
										// номер дома
										//if ( typeof localityObject.Premise.PremiseNumber !== "undefined"
										//	&& localityObject.Premise.PremiseNumber !== null )
										if ( localityObject.Premise.hasOwnProperty('PremiseNumber') )
										{
											var premiseNumber = localityObject.Premise.PremiseNumber;
										}
									}
								}
							}
							//console.log( addressGeoObject );
							
							
							/* >> Подставляем значения в маску */
							if( typeof input_ids['field_full_address'] !== "undefined" && input_ids['field_full_address'] !== null
								&& typeof field_full_address_mask !== "undefined" && field_full_address_mask !== null )
							{
								var full_address = field_full_address_mask;
								
								{/literal}
								{foreach from=$fields_from_mask key=name item=field}
									
									var field_tmp='';
									field_tmp = {$field};
									{literal}
									
									// если есть переменная с данными
									if( typeof field_tmp !== "undefined" && field_tmp !== null )
									{
										//var full_address = str_replace('--{/literal}{$name}{literal}--', field_tmp, full_address);
										//var full_address = full_address.replace( /\+\+([\S\s]*?)(?=--{/literal}{$name}{literal}--)--{/literal}{$name}{literal}--([\S\s]*?)(?=\+\+)\+\+/gi, "$1" + field_tmp + "$2" );
										
										// проверяем, заменил ли регуляркой выражение ++ABVGD..--tag--..EUYA++
										if ( full_address == (full_address = full_address.replace( /\+\+([^\+]*)(?=--{/literal}{$name}{literal}--)--{/literal}{$name}{literal}--([^\+]*)(?=\+\+)\+\+/gi, "$1" + field_tmp + "$2" ) ) )
										{
											// иначе заменяем обычным str_replace тег --tag--
											var full_address = str_replace('--{/literal}{$name}{literal}--', field_tmp, full_address);
										}
									}
									else // если нет переменной с данными - удаляем тег, который используется для подстановки данных в маску адреса
									{
										//var full_address = full_address.replace( /\+\+([\S\s]*?)(?=--{/literal}{$name}{literal}--)--{/literal}{$name}{literal}--([\S\s]*?)(?=\+\+)\+\+/gi, "" );
										
										// проверяем, заменил ли регуляркой выражение ++ABVGD..--tag--..EUYA++
										if ( full_address == (full_address = full_address.replace( /\+\+([^\+]*)(?=--{/literal}{$name}{literal}--)--{/literal}{$name}{literal}--([^\+]*)(?=\+\+)\+\+/gi, "" ) ) )
										{
											// иначе заменяем обычным str_replace тег --tag--
											var full_address = str_replace('--{/literal}{$name}{literal}--', '', full_address); // удаляем тег
											var full_address = str_replace('--{/literal}{$name}{literal}--,', '', full_address); // удаляем тег и "," сразу после него
										}
									}
									{/literal}
									
								{/foreach}
								{literal}
								
								full_address = full_address.trim();
								full_address = full_address.replace( /^,?(.*?),?$/gi, "$1" );
								full_address = full_address.trim();
								
								$('#' + input_ids['field_full_address'] ).val( full_address );
							}
							/* << Подставляем значения в маску */
							
							
							/* >> Подставляем значения в поля */
							{/literal}
							{foreach from=$fields key=name item=field}
								
								{if $name != 'field_full_address' AND $field != '' AND $field != 'tv'}
									
									var field_tmp='';
									field_tmp = {$fields_from_mask[$name]};
									
									{literal}
										
										if( typeof field_tmp !== "undefined" )
										{
											$('#{/literal}{$input_ids[$name]}{literal}' ).val( field_tmp );
										}
										
									{/literal}
								{/if}
								
							{/foreach}
							{literal}
							/* << Подставляем значения в поля */
						}
					}
				}
			}
		}
		// << Определяем координаты по адресу (геокодирование)
		
	});
	
});
{/literal}
// ]]>
</script>