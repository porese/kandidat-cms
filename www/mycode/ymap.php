<?php
//phpfile
#Яндекс-карты. Выводится тегом div с id="YMapsID".
defined('_JEXEC') or die('Ай-яй-яй, сюда нельзя!');
?>
<head>
<script src="http://api-maps.yandex.ru/1.1/index.xml?key=ALmmxUsBAAAAy5c1BQMAMOA00cIZevN_tlsJ6HjGVZOfJtoAAAAAAAAAAADLGYfWqjrbkbFWJKAveg18KMagyA=="
	//Код получать на яндексе
	type="text/javascript">
</script>
<script type="text/javascript">
	window.onload = function () {
		var map = new YMaps.Map(document.getElementById("YMapsID"));
		map.setCenter(new YMaps.GeoPoint(45.016245, 53.244320), 16);

		map.addControl(new YMaps.TypeControl());
		map.addControl(new YMaps.ToolBar());
		map.addControl(new YMaps.Zoom());
		map.addControl(new YMaps.MiniMap());
		map.addControl(new YMaps.ScaleLine());
    	map.addControl(new YMaps.SearchControl({resultsPerPage: 5, useMapBounds:    1 }));

		var placemark = new YMaps.Placemark(new YMaps.GeoPoint(45.023197, 53.244292), {style: "default#cafeIcon"});
    	placemark.name = "Совхозная 15 база 14";
		placemark.description = "ООО \"Пензенский Продовольственный Дом\"";
		map.addOverlay(placemark);
		placemark.openBalloon();
	}
</script>
</head>
