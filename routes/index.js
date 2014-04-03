
/*
 * GET home page.
 */

var fs = require('fs')
var path = require('path');

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

