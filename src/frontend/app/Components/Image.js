//= require TPanel
//= require TEventResponder

klass( 'Image', TPanel, [TEventResponderMixin], {
	
	_triggersEvents : ['Change'],
	
	_map : null,
	
	_maxZoom : 19,
	
	getPublicProperties : function(){
		var a = this.base();
		a.push(
				{ name:'Location', type:'object', default: { Latitude: 0, Longitude: 0 } },
				{ name:'Center', type:'object', default: { Latitude: 0, Longitude: 0 } },
				{ name:'Zoom', type:'int', default: 17 },
				{ name:'Dragable', type:'bool', default: true },
				{ name:'Zoomable', type:'bool', default: true }
				);
		return a;
	},
	
	constructor : function( options ){
		this.base( options );
		this._map = null;
		this._myposition = null;
	},
	
	createMainElement : function(){
		var d = this.base();
		
		var mymap = L.map( d,{
//			zoomControl: false,
//			attributionControl: false,
			dragging: this.getDragable(),
			touchZoom: this.getZoomable(),
			scrollWheelZoom: this.getZoomable(),
            minZoom: 1,
            maxZoom: 4,
            center: [0, 0],
            zoom: 1,
            crs: L.CRS.Simple
		});
		
		var w = 2000,
            h = 1500,
            url = 'http://localhost/newspaper-big.jpg';

        var southWest = mymap.unproject([0, h], mymap.getMaxZoom()-1);
        var northEast = mymap.unproject([w, 0], mymap.getMaxZoom()-1);
        var bounds = new L.LatLngBounds(southWest, northEast);


		mymap.on( 'moveend', function(){
			var latlon = mymap.getCenter();
			this._Center = {
				Latitude: latlon.lat,
				Longitude: latlon.lng
			};
			this.triggerEvent('Change',{});
		}.bind(this) );
		
		mymap.on( 'zoomend', function(){
			this._Zoom = mymap.getZoom();
			this.triggerEvent('Change',{});
		}.bind(this) );

		setTimeout( function(){
            L.imageOverlay(url, bounds).addTo(mymap);

            mymap.setMaxBounds(bounds);
			mymap.invalidateSize();
		}, 200 );
		
		this._map = mymap;

		return d;
	},
	
	setCenter : function( v ){
		this._Center = v;
		if ( this._map ){
			var latlon = this.getCenter();
			this._map.panTo( [latlon.Latitude, latlon.Longitude] );
		}
	},
	
	setZoom : function( v ){
		this._Zoom = v;
		if ( this._map ){
			this._map.setZoom( this.getZoom() );
		}
	},
	
	setLocation : function( v ){
		this._Location = v;
		if ( this._myposition ){
			var latlon = this.getLocation();
			this._myposition.setLatLng( [latlon.Latitude, latlon.Longitude] );
		}
	},
	
	getBounds : function(){
		if ( this._map ){
			var bounds = this._map.getBounds();
			return {
				'North' : bounds.getNorth(),
				'South' : bounds.getSouth(),
				'West' : bounds.getWest(),
				'East' : bounds.getEast()
			};
		}
		return null;
	}

});