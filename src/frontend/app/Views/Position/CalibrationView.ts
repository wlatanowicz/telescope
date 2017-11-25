import TemplateControl from "@framework/TemplateControl";
import template from "@app/Views/Position/CalibrationView.tpl";
import PositionCalibration from "@app/ValueObject/PositionCalibration";
import PositionCalibrationRegistry from "@app/Registry/PositionCalibrationRegistry";
import Coordinates from "@app/ValueObject/Coordinates";
import Camera from "@app/Client/Camera";
import Telescope from "@app/Client/Telescope";
import PositionCalibrationFactory from "@app/Factory/PositionCalibrationFactory";

export default class CalibrationView extends TemplateControl
{
    template = template;


    primaryCoordinates : Coordinates;
    secondaryCoordinates : Coordinates;

    calibrationData: PositionCalibration;

    calibrationDataRegistry: PositionCalibrationRegistry;
    camera: Camera;
    telescope: Telescope;
    calibrationFactory: PositionCalibrationFactory;

    constructor(
        calibrationDataRegistry: PositionCalibrationRegistry,
        calibrationFactory: PositionCalibrationFactory,
        camera: Camera,
        telescope: Telescope
    ) {
        super();
        this.calibrationDataRegistry = calibrationDataRegistry;
        this.camera = camera;
        this.telescope = telescope;
        this.calibrationFactory = calibrationFactory;
    }

    startCalibrationClicked()
    {
        let telescopeName = this.$('TelescopeName').Value;
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
        let cameraName = this.$('CameraName').Value;
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
        let telescopeName = this.$('TelescopeName').Value;
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
        let cameraName = this.$('CameraName').Value;
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
            this.calibrationData = this.calibrationFactory.fromPositionAndImageOffsets(
                this.primaryCoordinates,
                this.secondaryCoordinates,
                primaryStar,
                secondaryStar
            );

            this.$('DiffAngle').Text = this.calibrationData.AngleDiff;
            this.$('DiffShift').Text = this.calibrationData.LengthRatio;
        }
    }

    storeClicked()
    {
        if (this.calibrationData) {
            this.calibrationDataRegistry.store(this.calibrationData);
        }
    }
}