import TTemplateControl from "@framework/TTemplateControl";
import template from "@app/Views/Position/CalibrationView.tpl.ts";
import RPC from "@app/Common/RPC";

export default class CalibrationView extends TTemplateControl
{
    template = template;

    rpc: RPC = null;

    primaryCoordinates : any;
    secondaryCoordinates : any;

    constructor()
    {
        super();
        this.rpc = new RPC();
    }

    startCalibrationClicked()
    {
        let telescopeName = this.$('TelescopeName').Text;
        this.rpc.telescopeGetPosition(telescopeName).done(function (response) {
            this.primaryCoordinates = response.result;
            this.makePrimaryExposition();
        }.bind(this))
    }

    makePrimaryExposition()
    {
        let cameraName = this.$('CameraName').Text;
        let exposeTime = this.converters.int(this.$('ExposeTime').Text);
        this.rpc.cameraExpose(cameraName, exposeTime).done(function (response) {
            this.previewExposureFinished(response, "PrimaryImage");
            this.slewTelescope()
        }.bind(this));
    }

    previewExposureFinished(result, target)
    {
        this.rpc.getInfo(
            result.session_id,
            result.result
        ).done(this.previewImageInfoGet.bind(this, target));
    }

    previewImageInfoGet(target, result)
    {
        console.log(target, result);
        this.$(target).setImage(
            result.url,
            result.size.width,
            result.size.height
        );
    }

    slewTelescope()
    {
        let telescopeName = this.$('TelescopeName').Text;
        this.secondaryCoordinates = {
            right_ascension : this.primaryCoordinates.right_ascension + this.converters.int($('RaShift').Value),
            declination : this.primaryCoordinates.declination + this.converters.int($('DecShift').Value),
        };
        this.rpc.telescopeSetPosition(
            telescopeName,
            this.secondaryCoordinates.right_ascension,
            this.secondaryCoordinates.declination
        ).done(function(result){
            this.makeSecondaryExposition();
        }.bind(this));
    }

    makeSecondaryExposition()
    {
        let cameraName = this.$('CameraName').Text;
        let exposeTime = this.converters.int(this.$('ExposeTime').Text);
        this.rpc.cameraExpose(cameraName, exposeTime).done(function (response) {
            this.previewExposureFinished(response, "SecondaryImage");
        }.bind(this));
    }

    starSelected()
    {
        let primaryStar = this.$('PrimaryImage').Selection;
        let secondaryStar = this.$('SecondaryImage').Selection;

        if (primaryStar && secondaryStar) {

            

        }
    }
}