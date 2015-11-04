<script type="text/javascript">
if(typeof jQuery == "undefined"){
	document.write('<script type="text/javascript" src="//yandex.st/jquery/2.1.1/jquery.min.js" ></'+'script>');
}

if(typeof ymaps == "undefined"){
	//document.write('<script type="text/javascript" src="//api-maps.yandex.ru/2.1/?lang=ru_RU" ></'+'script>');
	document.write('<script type="text/javascript" src="//api-maps.yandex.ru/2.1/?lang=ru_RU&load=Map,Placemark,GeoObjectCollection,map.addon.balloon,geoObject.addon.balloon,package.controls,templateLayoutFactory,overlay.html.Placemark" ></'+'script>');
}
</script>


<div id="[[+idMap]]" style="[[+styleMapBlock]]" class="[[+classMapBlock]]"></div>

[[+filtersFormItems:notempty=`<div class="[[+classFiltersBlock]]" style="[[+styleFiltersBlock]]">
	<form id="[[+idFiltersForm]]" class="ymFiltersForm" action="[[~[[*id]]?scheme=`full`]]" method="POST">
		[[+filtersFormItems]]
		<div class="loader"></div>
	</form>
</div>`]]

<div style="clear:both"></div>


<style>
.search-map-result-view {
display: inline-block;
white-space: nowrap;
position: relative;
top: -13.5px;
left: -13.5px;
height: 27px;
}
.search-map-result-view._active {
top: -42px;
}

.search-map-result-view__overlay {
position: absolute;
width: 100%;
height: 100%;
z-index: 3;
cursor: pointer;
}

.search-map-result-view__icon {
background: url("//yastatic.net/maps-beta/_/QGuTq1D4vadJPJijE7xfGoI0UBY.svg");
width: 27px;
height: 27px;
}
.search-map-result-view__icon {
display: inline-block;
position: relative;
vertical-align: middle;
z-index: 2;
}
.search-map-result-view._active .search-map-result-view__icon {
background: url("//yastatic.net/maps-beta/_/ZDo_aSO8szfWh3gPYVWHwgyDa_E.svg");
width: 34px;
height: 42px;
}
.search-map-result-view__title {
height: 27px;
line-height: 27px;
}
.search-map-result-view__title {
background: rgba(255,255,255,.89);
display: inline-block;
vertical-align: top;
z-index: 1;
padding: 0 5px 0 19px;
margin-left: -14px;
border-radius: 5px;
border:1px solid #ddd;
max-width: none;
text-overflow: ellipsis;
overflow: hidden;
font-size:13px;
}
.search-map-result-view._active .search-map-result-view__title:not(:empty) {
display: inline-block;
margin-left: -21px;
}
</style>

<script type="text/javascript">
var ymFormId = '#[[+idFiltersForm]]';
var ymFormAction = $(ymFormId).attr('action');

ymaps.ready()
	.done(function (ymaps) {
		var mapCenter[[+idMap]] = [[+centerCoords]],
			myMap[[+idMap]] = new ymaps.Map('[[+idMap]]', {
				center: mapCenter[[+idMap]],
				zoom: [[+zoom]],
				controls: ['zoomControl']
			});
		
		myMap[[+idMap]].controls.add(new ymaps.control.TypeSelector({ options: { position: { left: 10, top: 10 }}}));
		
		/*
		myPlacemark = new ymaps.Placemark(myMap[[+idMap]].getCenter(), {
			iconContent: 'Собственный значок метки',
			hintContent: 'Собственный значок метки',
			imageClass: 'search-map-result-view'
		}, {
			pointOverlay: ymaps.overlay.html.Placemark,
			preset: 'twirl#stretchyIcon',
			iconLayout: ymaps.templateLayoutFactory.createClass('<div class="$[properties.imageClass] _active"><div class="$[properties.imageClass]__icon"></div><div class="$[properties.imageClass]__title">[if properties.iconContent]<i>$[properties.iconContent]</i>[else]$[properties.hintContent][endif]</div></div>')
			//iconContentLayout: ymaps.templateLayoutFactory.createClass( '<div style="width:auto; max-width:none; background-color:rgba(255,255,255,0.49);">[if properties.iconContent]<i>$[properties.iconContent]</i>[else]$[properties.hintContent][endif]</div>' )
		});
		
		myMap[[+idMap]].geoObjects.add(myPlacemark);
		
		console.log( myPlacemark.getOverlay() );
		
		myPlacemark.getOverlay().then(function (overlay)
		{
			console.log( overlay.getElement().markerLayer );
			console.log( overlay.markerLayer );
			
			ymaps.domEvent.manager
			.add(overlay.markerLayer.get(0), 'mouseenter', function (event) {
				console.log(event.get('type')); // click
			});
		});
		*/
		
		
		
		
		
		/*
		domEventsGroup = ymaps.domEvent.manager.group(myPlacemark);
		domEventsGroup.add('mouseenter', function (e)
		{
			console.log(e.get('type'));
			
			domEventsGroup.removeAll();
		});
		*/
		
		/*
		myMap[[+idMap]].myPlacemark.events.add('mouseenter', function (e)
		{
			console.log( e );
			
		});
		*/
		
		
		
		
		
		$.getJSON( ymFormAction , "ymJSON=1" ).done( function (json) {
			window.geoObjects = ymaps.geoQuery(json);
			
			window.clusters = geoObjects.search("geometry.type == 'Point'").clusterize({ preset: 'islands#invertedVioletClusterIcons'});
			myMap[[+idMap]].geoObjects.add(clusters);
			
			
			geoObjects.then(function () {
				//window.placemarks = geoObjects.search("geometry.type == 'Point'");
				
				//myMap[[+idMap]].geoObjects.options.unsetAll();
				myMap[[+idMap]].geoObjects.options.set({
					pointOverlay: ymaps.overlay.html.Placemark,
					preset: 'twirl#stretchyIcon',
					pane: 'overlaps',
					iconLayout: ymaps.templateLayoutFactory.createClass('<div id="marker_$[properties.modx_id]" class="$[properties.imageClass] _active"><div class="$[properties.imageClass]__overlay"></div><div class="$[properties.imageClass]__icon"></div><div class="$[properties.imageClass]__title">[if properties.iconContent]<i>$[properties.iconContent]</i>[else]$[properties.hintContent][endif]</div></div>')
				});
				
				//myMap[[+idMap]].geoObjects.events.add('click', function (e) {
				//	var target = e.get('target');
				//	console.log( target );
				//});
			});
			
			
			
			[[+checkZoomRange:is=`0`:or:is=`false`:or:is=``:then=` `:else=`
				geoObjects.applyBoundsToMap(myMap[[+idMap]], {
					checkZoomRange: true
				});
			`]]
		});
		
		
		// >> Обработка события клика на маркере
		myMap[[+idMap]].geoObjects.events.add('click', function (e)
		{
			var object = e.get('target');
			var goToRes = [[+goToRes]];
			var goToResBlank = [[+goToResBlank]];
			var goToJS = [[+goToJS:is=`0`:or:is=`false`:or:is=``:then=`''`:else=`1`]];
			
			if( goToRes !== 'undefined' && goToRes !== 'null' && goToRes !== '' && goToRes !== 0 )
			{
				if( typeof object.properties.get(0).modx_id !== 'undefined'
					&& object.properties.get(0).modx_id !== 'null' )
				{
					var modx_id = object.properties.get(0).modx_id;
					//console.log( modx_id );
					
					if( goToJS !== 'undefined' && goToJS !== 'null' && goToJS !== '' && goToJS !== 0 )
					{
						[[+goToJS:is=`0`:or:is=`false`:or:is=``:then=` `:else=`[[+goToJS]]`]]
					}
					else if( goToResBlank !== 'undefined' && goToResBlank !== 'null' && goToResBlank !== '' && goToResBlank !== 0 )
					{
						window.open( '[[++site_url]]index.php?id=' + modx_id );
					}
					else
					{
						window.location.href = '[[++site_url]]index.php?id=' + modx_id;
					}
					
					e.preventDefault(); // остановим открытие балуна
				}
			}
		});
		// << Обработка события клика на маркере
		
		
		[[+filtersFormItems:notempty=`
		$(".[[+classFiltersItem]]").click(function() {
			var thisItem = this;
			var filtersItemId = $(this).attr('id'); // id кликнутого элемента
			
			$(ymFormId).find('.loader').show(); // покажем лоадер
			
			// >> Если нужно скрыть элементы
			if( $(thisItem).hasClass('ymFiltersItemHide') )
			{
				$(ymFormId).find('#checkbox_' + filtersItemId).val('0'); // ставим input[type=hidden] - val
				$(thisItem).removeClass('ymFiltersItemHide').addClass('ymFiltersItemShow'); // ставим нужный класс
				
				// >> Собираем строку из формы для передачи аяксом
				var ymFormData = $(ymFormId).serializeArray(), ymFormDataString = '';
				
				$.each(ymFormData, function(i_1, val_1) {
					$.each(val_1, function(i_2, val_2) {
						if(i_2 == 'name') {
							ymFormDataString += val_2 + '=';
						} else if(i_2 == 'value') {
							ymFormDataString += val_2 + '&';
						}
					});
				});
				// << Собираем строку из формы для передачи аяксом
				
				$.getJSON( ymFormAction , ymFormDataString )
					.done( function (json)
					{
						window.geoObjects2 = ymaps.geoQuery(json);
						window.clusters2 = geoObjects2.search("geometry.type == 'Point'").clusterize();
						myMap[[+idMap]].geoObjects.add(clusters2);
						
						geoObjects2.applyBoundsToMap(myMap[[+idMap]], {
							checkZoomRange: true
						});
						
						geoObjects.remove(geoObjects2).removeFromMap(myMap[[+idMap]]); // удаляем текущие объекты с карты
						myMap[[+idMap]].geoObjects.remove(clusters);
						geoObjects = geoObjects2; // колдуем
						clusters = clusters2; // колдуем
						geoObjects2=''; // колдуем
						clusters2=''; // колдуем
						
						$(ymFormId).find('.loader').hide(); // спрячем лоадер
						$(thisItem).parent().find('.ymFiltersWrapper').slideUp(); // прячем подпункты, если есть..
					});
			} // << Если нужно скрыть элементы
			else
			{ // >> Если нужно показать элементы
				$(ymFormId).find('#checkbox_' + filtersItemId).val('1'); // ставим input[type=hidden] - val
				$(thisItem).removeClass('ymFiltersItemShow').addClass('ymFiltersItemHide'); // ставим нужный класс
				
				// >> Собираем строку из формы для передачи аяксом
				var ymFormData = $(ymFormId).serializeArray(), ymFormDataString = '';
				
				$.each(ymFormData, function(i_1, val_1) {
					$.each(val_1, function(i_2, val_2) {
						if(i_2 == 'name') {
							ymFormDataString += val_2 + '=';
						} else if(i_2 == 'value') {
							ymFormDataString += val_2 + '&';
						}
					});
				});
				// << Собираем строку из формы для передачи аяксом
				
				$.getJSON( ymFormAction , ymFormDataString )
					.done( function (json)
					{
						window.geoObjects2 = ymaps.geoQuery(json);
						window.clusters2 = geoObjects2.search("geometry.type == 'Point'").clusterize();
						myMap[[+idMap]].geoObjects.add(clusters2);
						
						geoObjects2.applyBoundsToMap(myMap[[+idMap]], {
							checkZoomRange: true
						});
						
						geoObjects.remove(geoObjects2).removeFromMap(myMap[[+idMap]]); // удаляем текущие объекты с карты
						myMap[[+idMap]].geoObjects.remove(clusters);
						geoObjects = geoObjects2; // колдуем
						clusters = clusters2; // колдуем
						geoObjects2=''; // колдуем
						clusters2=''; // колдуем
						
						$(ymFormId).find('.loader').hide(); // спрячем лоадер
						$(thisItem).parent().find('.ymFiltersWrapper').slideDown(); // покажем подпункты, если есть..
					});
			}
			// << Если нужно показать элементы
		});
		`]]
	});
</script>
