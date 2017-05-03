import Panel from "@framework/WebControls/Panel";
import EventResponderInterface from "@framework/EventResponderInterface";
import EventResponder from "@framework/EventResponder";

export default class Image extends Panel implements EventResponderInterface
{

    private _event = null;

    get event():EventResponder
    {
        if (this._event === null) {
            this._event = new EventResponder(this, ['Select']);
        }
        return this._event;
    }

	_map = null;
	_marker = null;

	_width = null;
	_height = null;
	_bounds = null;
	_layer = null;

    protected _Selection;

    get Selection()
    {
        return this._Selection;
    }

	createMainElement()
    {
		var d = super.createMainElement();

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
                this.event.trigger('Select', pixel);

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
	}

	setCrosshair(latlon, pixel)
    {
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
	}

	setImage(url, width, height)
    {
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
}