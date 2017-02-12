//= require THttp

klass("NodeClient", THttp, {
    applyHeaders : function(xhttp) {
        this.base(xhttp);
        xhttp.timeout = 5000;
    }
});