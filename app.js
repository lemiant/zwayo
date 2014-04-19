
/**
 * Module dependencies.
 */

var express = require('express');
var routes = require('./routes');
var user = require('./routes/user');
var http = require('http');
var path = require('path');
var api_router = require('zw_api/router')
var api_ws = require('zw_api/ws')
var socketio = require('socket.io')

// Mongo Stuff ///////////////////////
var mongo = require('mongodb');
var monk = require('monk');
var db = monk('localhost:27017/nodetest');
//////////////////////////////////////

var app = express();

// all environments
app.set('port', 80);
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');
app.use(express.favicon());
app.use(express.logger('dev'));
app.use(express.json());
app.use(express.urlencoded());
//app.use(express.methodOverride());
app.use(express.cookieParser('your secret here'));
//app.use(express.session());
app.use(app.router);
app.use(express.static(path.join(__dirname, 'public')));

// development only
if ('development' == app.get('env')) {
  app.use(express.errorHandler());
}

app.get('/list', routes.list(db));
app.get('/api/*', function(q,r,n){ api_router(q,r,n,db) });
app.get('*', routes.static_views);

var server = http.createServer(app),
    io = socketio.listen(server)

api_ws(io)

server.listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});



