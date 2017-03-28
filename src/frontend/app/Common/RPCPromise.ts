import TPromise from "@framework/TPromise";

export default class RPCPromise extends TPromise
{
    done(fn)
    {
        return this.on('done', function(param) {
            fn( param.result );
        });
    }

    error(fn)
    {
        return this.on('error', function(param) {
            fn( param.error );
        });
    }
}
