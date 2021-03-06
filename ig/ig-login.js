// Generated by CoffeeScript 1.3.3

(function($, require) {
  var Instagram, ig, param, store, token;
  Instagram = require.Instagram;
  store = {
    get: function(key) {
      return window.localStorage.getItem(key);
    },
    set: function(key, val) {
      return window.localStorage.setItem(key, val);
    },
    clear: function() {
      return window.localStorage.clear();
    },
    remove: function(key) {
      return window.localStorage.removeItem(key);
    }
  };
  ig = new Instagram();
  if (window.location.hash) {
    token = ig.getToken();
    store.set('ig_token', token);
  }
  if (!store.get('ig_token')) {
    param = {
      client_id: 'fe16937bc20c4863be833635e8326ecf',
      client_secret: '9a836e7ef5b645f5971ba40f03d1e58a',
      redirect_uri: 'http://www.photocase.ie/ig/ig.html',
      scope: 'basic+likes+relationships',
      response_type: 'token'
    };
    ig.auth(param);
  }
  ig.setOptions({
    token: store.get('ig_token'),
  });
  if (store.get('ig_token')) {
    opener.insta_init();
  	window.close();
	}
})(jQuery, window);
