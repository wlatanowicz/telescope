import TemplateControl from "@framework/TemplateControl";
import PositionCalibrationRegistry from "@app/Registry/PositionCalibrationRegistry";
import Camera from "@app/Client/Camera";
import Telescope from "@app/Client/Telescope";
import PositionCalibrationFactory from "@app/Factory/PositionCalibrationFactory";
import Coordinates from "@app/ValueObject/Coordinates";
import template from "@app/Views/Position/RepositionView.tpl";
import PixelCoordinates from "@app/ValueObject/PixelCoordinates";

export default class RepositionView extends TemplateControl
{
    template = template;

    primaryCoordinates: Coordinates;
    targetCoordinates: Coordinates;

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

    activated() {
        this.$('DiffAngle').Text = this.calibrationDataRegistry.read().AngleDiff;
        this.$('DiffShift').Text = this.calibrationDataRegistry.read().LengthRatio;
    }

    starSelected() {
        let primaryStar = this.$('PrimaryImage').Selection;
        let targetStar = new PixelCoordinates(
            this.$('PrimaryImage').Width / 2,
            this.$('PrimaryImage').Height / 2
        );

        let targetCoordinates = this.calibrationFactory.calculateTargetCoordinates(
            this.calibrationDataRegistry.read(),
            this.primaryCoordinates,
            primaryStar,
            targetStar
        );

        this.$('RaShift').Text = targetCoordinates.RightAscension - this.primaryCoordinates.RightAscension;
        this.$('DecShift').Text = targetCoordinates.Declination - this.primaryCoordinates.Declination;

        this.targetCoordinates = targetCoordinates;
    }

    exposeClicked() {
        let telescopeName = this.$('TelescopeName').Value;
        let cameraName = this.$('CameraName').Value;
        let exposeTime = this.converters.int(this.$('ExposeTime').Text);
        this.camera.expose(cameraName, exposeTime).done((response) => {
            this.previewExposureFinished(response, "PrimaryImage");
        });
        this.telescope.getPosition(telescopeName).done((response) => {
            this.primaryCoordinates = new Coordinates(
                response.result.right_ascension,
                response.result.declination
            );
        })
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

    scopeRepositionClicked() {
        let telescopeName = this.$('TelescopeName').Value;
        this.telescope.setPosition(
            telescopeName,
            this.targetCoordinates
        ).done(() => {
            this.exposeClicked();
        })
    }
}
