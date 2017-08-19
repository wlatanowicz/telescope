import Panel from "@framework/WebControls/Panel";

export default class ImagePanel extends Panel
{
	_map = null;

	_width = null;
	_height = null;
	_bounds = null;
	_layer = null;

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


		setTimeout( function(){
			mymap.invalidateSize();
		}, 200 );

		this._map = mymap;

		return d;
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