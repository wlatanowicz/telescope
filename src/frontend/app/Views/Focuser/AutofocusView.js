//= require TTemplateControl
//= require RPC

klass("AutofocusView", TTemplateControl, {

    rpc : null,

    constructor : function(options) {
        this.base(options);

        this.rpc = new RPC();
    },

    capturePreviewClicked : function() {
        this.rpc.focuserSetPosition(
            this.$('FocuserName').getValue(),
            this.$('InitialPosition').getValue()
        ).done(this.previewFocuserSet.bind(this));
    },

    previewFocuserSet : function() {
        this.rpc.cameraExpose(
            this.$('CameraName').getValue(),
            this.$('ExposureTime').getValue()
        ).done(this.previewExposureFinished.bind(this));
    },

    previewExposureFinished : function(result) {
        this.rpc.getInfo(
            result.session_id,
            result.result
        ).done(this.previewImageInfoGet.bind(this));
    },

    previewImageInfoGet : function(result) {
        this.$('Image').setImage(
            result.url,
            result.size.width,
            result.size.height
        );
    },

    starSelected : function(sender, position) {
        this.$('ImageX').setValue(position.x);
        this.$('ImageY').setValue(position.y);
    },

    autofocusClicked : function () {
        
    }

});