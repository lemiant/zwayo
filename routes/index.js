
/*
 * GET home page.
 */

var fs = require('fs')
var path = require('path');
var api = require('zw_api')

exports.api = function(req, res, next){
    func = req.url.slice(5)
    if(typeof api[func] == "function")
        api[func](req, res, next)
    else
        next()
}

exports.static_views = function(req, res, next){
    view = req.url.slice(1)
    view_path = path.join(path.dirname(require.main.filename), 'views', view)+".jade"
    fs.exists(view_path, function(exists){
        if(exists){
            res.render(view, {})
        }
        else next()
    })
}

