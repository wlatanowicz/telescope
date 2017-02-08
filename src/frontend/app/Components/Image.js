//= require TPanel
//= require TEventResponder

klass( 'Image', TPanel, [TEventResponderMixin], {
	
	_triggersEvents : ['Select'],
	
	_map : null,
	_marker : null,

	_width : null,
	_height : null,
	_bounds : null,
	_layer : null,

	getPublicProperties : function(){
		var a = this.base();
		a.push(
				{ name:'Selection', type:'object', default: null }
				);
		return a;
	},
	
	constructor : function( options ){
		this.base( options );
		this._map = null;
		this._marker = null;
        this._width = null;
		this._height = null;
		this._bounds = null;
		this._layer = null;
	},
	
	createMainElement : function(){
		var d = this.base();
		
		var mymap = L.map( d,{
			zoomControl: true,
			dragging: true,
			touchZoom: true,
			scrollWheelZoom: true,
            minZoom: 1,
            maxZoom: 4,
            center: [0, 0],
            zoom: 1,
            crs: L.CRS.Simple
		});

		mymap.on( 'click', function(e){

			var pixel = {
                y: Math.round(this._height * e.latlng.lat / (this._bounds.getSouth() - this._bounds.getNorth())),
				x: Math.round(this._width * e.latlng.lng / (this._bounds.getEast() - this._bounds.getWest()))
			};

			var latlon = e.latlng;



            if ( pixel.x >= 0 && pixel.y >= 0
                && pixel.x <= this._width && pixel.y <= this._height ) {

                this.setCrosshair(latlon, pixel);
                this._Selection = pixel;
                this.triggerEvent('Select', pixel);

            } else {
            	if (this._marker) {
                    this._map.removeLayer(this._marker);
                    this._marker = null;
                }
			}

		}.bind(this));

		setTimeout( function(){
			mymap.invalidateSize();
		}, 200 );
		
		this._map = mymap;

		return d;
	},

	setCrosshair : function(latlon, pixel) {
		var label = pixel.x + " x " + pixel.y;

		if (this._marker) {
			this._marker.setLatLng([latlon.lat, latlon.lng]).bindPopup(label).openPopup();
		} else {
			var crosshair = L.icon({
				iconUrl: 'assets/crosshair.png',

				iconSize: [7, 7], // size of the icon
				iconAnchor: [4, 4], // point of the icon which will correspond to marker's location
				popupAnchor: [0, -3] // point from which the popup should open relative to the iconAnchor
			});

			this._marker = L.marker([latlon.lat, latlon.lng], {icon: crosshair}).addTo(this._map).bindPopup(label).openPopup();
		}
	},

	setImage : function(url, width, height) {

		this._width = width;
		this._height = height;

        var southWest = this._map.unproject([0, height], this._map.getMaxZoom()-1);

        var northEast = this._map.unproject([width, 0], this._map.getMaxZoom()-1);
        this._bounds = new L.LatLngBounds(southWest, northEast);

        if (this._layer) {
        	this._map.removeLayer(this._layer);
		}

        this._layer = L.imageOverlay(url, this._bounds);
        this._layer.addTo(this._map);

        this._map.setMaxBounds(this._bounds);
	}
});