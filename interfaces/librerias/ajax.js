function ObjAJAX() {
    var obj;
    if (window.XMLHttpRequest) { // no es IE
        obj = new XMLHttpRequest();
    } else { // Es IE o no tiene el objeto
        try {
            obj = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            alert("Navegador no soportado");
        }
    }
    return obj;
}


function Najax() {
    this.obj = ObjAJAX();
    this.asynchronous = true;

    this.prepare = function(params) {
        var keys = Object.keys(params);
        var limit = keys.length;
        var chain = '';
        for (i = 0; i < limit; i++) {
            if (i > 0) {
                chain += '&';
            }
            chain += keys[i] + '=' + escape(params[keys[i]]);
        }
        return chain;
    };

    this.connect = function(method, url, params, callback) {
        this.obj.open(method, url, this.asynchronous);
        this.obj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        if (this.asynchronous) {
            var x = this;
            this.obj.onreadystatechange = function() {
                if (x.obj.readyState == 4) {
                    callback(x.obj.responseText);
                }
            };
            this.obj.send(params);
        } else {
            this.obj.send(params);
            return this.obj.responseText;
        }
    }

    this.get = function(url, params, callback) {
        params = this.prepare(params);
        url = (params != '') ? url + '?' + params : url;
        return (typeof(callback) == "undefined") ? this.connect("GET", url, params) : this.connect("GET", url, params, callback);
    };

    this.post = function(url, params, callback) {
        params = this.prepare(params);
        return (typeof(callback) == "undefined") ? this.connect("POST", url, params) : this.connect("POST", url, params, callback);
    };

    this.load_script = function(route, sType, oCallback) {
        sType = sType || 'text/javascript';

        var oHead = document.getElementsByTagName('head')[0] || document.documentElement;
        var sScriptId = 'JaSper_script_' + route.replace(/[^a-zA-Z\d_]+/, '');

        if (!document.getElementById(sScriptId)) {
            var oScript = document.createElement('script');
            oScript.setAttribute('id', sScriptId);
            oScript.setAttribute('type', sType);
            oScript.setAttribute('src', route);

            oScript.onload = oScript.onreadystatechange = function() {
                if (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete') {
                    oScript.onload = oScript.onreadystatechange = null;
                    if (typeof(oCallback) != "undefined") {
                        eval(oCallback);
                    }

                    if (oHead && oScript.parentNode) {
                        oHead.removeChild(oScript);
                    }
                }
            };

            oHead.insertBefore(oScript, oHead.firstChild);
        } else {
            if (typeof(oCallback) != "undefined") {
                eval(oCallback);
            }
        }

        return true;
    };


}