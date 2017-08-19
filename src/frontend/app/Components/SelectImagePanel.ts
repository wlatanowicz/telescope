import EventResponderInterface from "@framework/EventResponderInterface";
import EventResponder from "@framework/EventResponder";
import PixelCoordinates from "@app/ValueObject/PixelCoordinates";
import ImagePanel from "@app/Components/ImagePanel";

export default class SelectImagePanel extends ImagePanel implements EventResponderInterface
{
    private _event = null;

    get event():EventResponder
    {
        if (this._event === null) {
            this._event = new EventResponder(this, ['Select']);
        }
        return this._event;
    }

	_marker = null;

    protected _Selection: PixelCoordinates;

    get Selection(): PixelCoordinates
    {
        return this._Selection;
    }

	createMainElement()
    {
		var d = super.createMainElement();

		this._map.on( 'click', (e) => {

			var pixel = new PixelCoordinates(
                Math.round(this._height * e.latlng.lat / (this._bounds.getSouth() - this._bounds.getNorth())),
				Math.round(this._width * e.latlng.lng / (this._bounds.getEast() - this._bounds.getWest()))
			);

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

		});

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

			//L.circle([latlon.lat, latlon.lng], 4).addTo(this._map);
		}
	}
}