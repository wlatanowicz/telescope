import TemplateControl from "@framework/TemplateControl";
import template from "@app/Views/Position/CalibrationView.tpl.ts";
import PositionCalibration from "@app/ValueObject/PositionCalibration";
import PositionCalibrationRegistry from "@app/Registry/PositionCalibrationRegistry";
import Coordinates from "@app/ValueObject/Coordinates";
import Camera from "@app/Client/Camera";
import Telescope from "@app/Client/Telescope";

export default class CalibrationView extends TemplateControl
{
    template = template;


    primaryCoordinates : Coordinates;
    secondaryCoordinates : Coordinates;

    calibrationData: PositionCalibration;

    calibrationDataRegistry: PositionCalibrationRegistry;
    camera: Camera;
    telescope: Telescope;

    constructor(calibrationDataRegistry: PositionCalibrationRegistry, camera: Camera, telescope: Telescope) {
        super();
        this.calibrationDataRegistry = calibrationDataRegistry;
        this.camera = camera;
        this.telescope = telescope;
    }

    startCalibrationClicked()
    {
        let telescopeName = this.$('TelescopeName').Text;
        this.telescope.getPosition(telescopeName).done(function (response) {
            this.primaryCoordinates = new Coordinates(
                response.result.right_ascension,
                response.result.declination
            );
            this.makePrimaryExposition();
        }.bind(this))
    }

    makePrimaryExposition()
    {
        let cameraName = this.$('CameraName').Text;
        let exposeTime = this.converters.int(this.$('ExposeTime').Text);
        this.camera.expose(cameraName, exposeTime).done(function (response) {
            this.previewExposureFinished(response, "PrimaryImage");
            this.slewTelescope()
        }.bind(this));
    }

    previewExposureFinished(result, target)
    {
        this.camera.getInfo(
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
        this.secondaryCoordinates = new Coordinates(
            this.primaryCoordinates.RightAscension + this.converters.float(this.$('RaShift').Value),
            this.primaryCoordinates.Declination + this.converters.float(this.$('DecShift').Value)
        );
        this.telescope.setPosition(
            telescopeName,
            this.secondaryCoordinates
        ).done(function(result){
            this.makeSecondaryExposition();
        }.bind(this));
    }

    makeSecondaryExposition()
    {
        let cameraName = this.$('CameraName').Text;
        let exposeTime = this.converters.int(this.$('ExposeTime').Text);
        this.camera.expose(cameraName, exposeTime).done(function (response) {
            this.previewExposureFinished(response, "SecondaryImage");
        }.bind(this));
    }

    starSelected()
    {
        let primaryStar = this.$('PrimaryImage').Selection;
        let secondaryStar = this.$('SecondaryImage').Selection;

        if (primaryStar && secondaryStar) {
            let primaryRa = 360 * this.primaryCoordinates.RightAscension / 24;
            let secondaryRa = 360 * this.secondaryCoordinates.RightAscension / 24;
            let primaryDec = this.primaryCoordinates.Declination;
            let secondaryDec = this.secondaryCoordinates.Declination;

            let starAngle = Math.atan2(
                secondaryStar.x - primaryStar.x,
                secondaryStar.y - primaryStar.y
            );

            let slewAngle = Math.atan2(
                secondaryRa - primaryRa,
                secondaryDec - primaryDec
            );

            let diffAngle = starAngle - slewAngle;

            let starShift = Math.sqrt(
                Math.pow(secondaryStar.x - primaryStar.x, 2)
                + Math.pow(secondaryStar.y - primaryStar.y, 2)
            );

            let slewShift = Math.sqrt(
                Math.pow(primaryRa - secondaryRa, 2)
                + Math.pow(primaryDec - secondaryDec, 2)
            );

            let diffShift = slewShift / starShift;

            while (diffAngle > Math.PI) {
                diffAngle -= Math.PI;
            }

            while (diffAngle < -Math.PI) {
                diffAngle += Math.PI;
            }

            this.$('DiffAngle').Text = diffAngle;
            this.$('DiffShift').Text = diffShift;

            this.calibrationData = new PositionCalibration(
                diffAngle,
                diffShift
            );
        }
    }

    storeClicked()
    {
        if (this.calibrationData) {
            this.calibrationDataRegistry.store(this.calibrationData);
        }
    }
}