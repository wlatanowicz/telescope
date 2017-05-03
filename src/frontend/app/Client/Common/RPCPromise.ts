import Promise from "@framework/Promise";

export default class RPCPromise extends Promise
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
