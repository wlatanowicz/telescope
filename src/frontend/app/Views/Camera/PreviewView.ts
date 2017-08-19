import TemplateControl from "@framework/TemplateControl";
import template from "@app/Views/Camera/PreviewView.tpl";
import Camera from "@app/Client/Camera";

export default class PreviewView extends TemplateControl
{
    template = template;

    camera: Camera;

    constructor(camera: Camera)
    {
        super();
        this.camera = camera;
    }

    capturePreviewClicked()
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
}