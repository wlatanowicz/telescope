//= require TTemplateControl
//= require RPC

klass("AutofocusView", TTemplateControl, {

    capturePreviewClicked : function() {
        var rpc = new RPC();
        rpc.focuserSetPosition(
            this.$('FocuserName').getValue(),
            this.$('InitialPosition').getValue()
        ).done(function(){
            rpc.cameraExpose(
                this.$('CameraName').getValue(),
                3
            ).done(function (result) {
                rpc.getInfo(
                    result.session_id,
                    result.result
                ).done(function (result) {

                    this.$('Image').setImage(
                        result.url,
                        result.size.width,
                        result.size.height
                    );

                }.bind(this));
            }.bind(this));
        }.bind(this));
    },

    buttonClicked : function() {

    }
});