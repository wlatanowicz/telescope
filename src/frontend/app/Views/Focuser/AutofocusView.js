//= require TTemplateControl
//= require RPC

klass("AutofocusView", TTemplateControl, {

    capturePreviewClicked : function() {
        var rpc = new RPC();
        rpc.focuserSetPosition(
            "sim",
            4000
        ).done(function(){
            rpc.cameraExpose(
                "sim",
                3
            ).done(function (result) {
                console.log(result);
            });
        }.bind(this));
    },

    buttonClicked : function() {

        this.$('Image').setImage(
            "http://localhost/radio-telescope/index.php/api/job/sess20170201/results/capture-2017-02-01-20-14-01.jpeg",
            564,
            470
        );
    }
});