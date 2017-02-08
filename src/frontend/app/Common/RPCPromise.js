//= require TPromise

klass( 'RPCPromise', TPromise, {

    constructor : function(){
        this.base();
        this.wrapPromise();
    },

    wrapPromise : function(){
        var promise = this;

        promise.done = function(fn) {
            return promise.on( 'done', function(param) {
                fn( param.result );
            });
        };

        promise.error = function(fn) {
            return promise.on( 'error', function(param) {
                fn( param.error );
            });
        };
     }

} );