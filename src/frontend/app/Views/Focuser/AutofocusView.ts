import TTemplateControl from "@framework/TTemplateControl";
import RPC from "@app/Common/RPC";
import template from "@app/Views/Focuser/AutofocusView.tpl.ts";

export default class AutofocusView extends TTemplateControl
{
    template = template;

    rpc = null;

    constructor()
    {
        super();
        this.rpc = new RPC();
    }

    capturePreviewClicked()
    {
        this.rpc.focuserSetPosition(
            this.$('FocuserName').Value,
            this.$('InitialPosition').Value
        ).done(this.previewFocuserSet.bind(this));
    }

    previewFocuserSet()
    {
        this.rpc.cameraExpose(
            this.$('CameraName').Value,
            this.$('ExposureTime').Value
        ).done(this.previewExposureFinished.bind(this));
    }

    previewExposureFinished(result)
    {
        this.rpc.getInfo(
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
        this.rpc.autofocus(
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