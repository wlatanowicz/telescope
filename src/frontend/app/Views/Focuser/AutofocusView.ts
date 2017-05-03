import TemplateControl from "@framework/TemplateControl";
import template from "@app/Views/Focuser/AutofocusView.tpl.ts";
import Focuser from "@app/Client/Focuser";
import Camera from "@app/Client/Camera";

export default class AutofocusView extends TemplateControl
{
    template = template;

    focuser: Focuser;
    camera: Camera;

    constructor(focuser: Focuser, camera: Camera)
    {
        super();
        this.focuser = focuser;
        this.camera = camera;
    }

    capturePreviewClicked()
    {
        this.focuser.setPosition(
            this.$('FocuserName').Value,
            this.$('InitialPosition').Value
        ).done(this.previewFocuserSet.bind(this));
    }

    previewFocuserSet()
    {
        this.camera.expose(
            this.$('CameraName').Value,
            this.$('ExposureTime').Value
        ).done(this.previewExposureFinished.bind(this));
    }

    previewExposureFinished(result)
    {
        this.camera.getInfo(
            result.session_id,
            result.result
        ).done(this.previewImageInfoGet.bind(this));
    }

    previewImageInfoGet(result)
    {
        this.$('Image').setImage(
            result.url,
            result.size.width,
            result.size.height
        );
    }

    starSelected(sender, position)
    {
        this.$('ImageX').Value = position.x;
        this.$('ImageY').Value = position.y;
    }

    autofocusClicked()
    {
        this.focuser.autofocus(
            this.$('CameraName').Value,
            this.$('FocuserName').Value,
            this.$('ExposureTime').Value,
            this.$('MinPosition').Value,
            this.$('MaxPosition').Value,
            this.$('ImageX').Value,
            this.$('ImageY').Value,
            this.$('Radius').Value,
            this.$('Iterations').Value
        );
    }
}