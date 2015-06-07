// JavaScript Document
var storeUrl = $("#storeaddres").val();
$(document).ready(function () {
	
$("#facebookreg").click(function (e) {
	$("#toaster-text").html('Please Wait');
	$("#toaster").show();
	e.preventDefault();
	fblogin();
});
$("#instagramreg").click(function (e) {
	$("#toaster-text").html('Please Wait');
	$("#toaster").show();
	e.preventDefault();
	insta_login();
});
});
window.fbAsyncInit = function() {
    FB.init({
       appId: $('#fbappid').val(),
       cookie: true,
       xfbml: true,
       oauth: true
    }); 
};


(function() {
var e = document.createElement('script'); e.async = true;
e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
document.getElementById('fb-root').appendChild(e);
}());

function fblogin(){
FB.login(function(response){
if (response.authResponse) {
FB.api('/me', function (resp) {


$.ajax({
            type: "POST",
            url: storeUrl + "controllers/checkregister.php",
            data: "email=" + resp.email,
			 error: function () {
				 $("#toaster").hide();
				 alert('Unable to Connect');
			},
             success: function (res) {
				 if(res == 1000){
				 FB.api('/me/picture?type=normal&amp;&height=200&width=200', function (profile) {
					if (typeof resp.first_name != 'undefined') $('input[name="fName"]').val(resp.first_name);
					if (typeof resp.last_name != 'undefined') $('input[name="lName"]').val(resp.last_name);
					if (typeof resp.email != 'undefined') $('input[name="email2"]').val(resp.email);
					if (typeof resp.location != 'undefined') $('input[name="town2"]').val(resp.location.name);
					$('input[name="socialreg"]').val('1');
					if (typeof profile.data.url != 'undefined')  $('input[name="profilepic"]').val(profile.data.url);
					var cover = '' ;
					//FB.api('/me?fields=cover', function (cover) {	
					//if (typeof cover.cover.source != 'undefined') $('input[name="coverpic"]').val(cover.cover.source);
					$("#registerform").submit();
					//$("#toaster").hide();
					//});
					
					
				 });
					
				 }else if(res == 000){
					$('input[name="username2"]').val(resp.email);
					$('input[name="sociallog"]').val('1');
					$("#toaster").hide();
					$("#logform").submit();
					 
				 }
			}
           
        });
    });
}
},{scope: 'email,offline_access,user_photos,friends_photos'});
}
/////// instagram code
var ACCESS_TOKEN;
var USER_ID;
var ig;

function insta_login() {
    if (!(window.localStorage && window.localStorage.getItem('ig_token'))) {

        var igloginwin = window.open('ig/ig.html', 'iglogin', 'width=480,height=370');
    } else {
        insta_init();
    }
}
function insta_init() {
    if (window.localStorage && window.localStorage.getItem('ig_token')) {
        ig = new Instagram();
        ig.setOptions({
            token: window.localStorage.getItem('ig_token')
        });
        if (!USER_ID) {
            ig.currentUser(function (resp) {
               if(resp.data.username){
				  
$.ajax({
            type: "POST",
            url: storeUrl + "controllers/checkregister.php",
            data: "email=" + resp.data.username+"&insta=1",
			 error: function () {
				 alert('Unable to Connect');
			},
             success: function (res) {
				 if(res == 1000){
				 	var name =  resp.data.full_name.split(' ');
					$('input[name="fName"]').val(name[0]);
					$('input[name="lName"]').val(name[1]);
					$('input[name="email2"]').val(resp.data.username);
					$('input[name="socialreg"]').val('2');
					if (typeof resp.data.profile_picture != 'undefined') $('input[name="profilepic"]').val(resp.data.profile_picture);
					//$('input[name="coverpic"]').val(resp.email);
					$("#toaster").hide();
					$("#registerform").submit();
				
					
				 }else if(res == 000){
					$('input[name="username2"]').val(resp.data.username);
					$('input[name="sociallog"]').val('2');
					$("#toaster").hide();
					$("#logform").submit();
					 
				 }
			}
           
        });
			   }
           
            });
        }
    }

}
(function ($, exports) {
    var Instagram;
    Instagram = (function () {
        Instagram.prototype.api = 'ig/ajax.php';
        Instagram.prototype.endPoint = 'https://instagram.com/oauth/authorize/?';

        function Instagram() {}
        Instagram.prototype.auth = function (options) {
            var params;
            params = '';
            $.each(options, function (key, value) {
                return params += key + '=' + value + '&';
            });
            this.authUri = this.endPoint + params;
            return window.location.href = this.authUri;
        };
        Instagram.prototype.getToken = function () {
            return window.location.hash.replace('#access_token=', '');
        };
        Instagram.prototype.setOptions = function (options) {
            var self;
            self = this;
            return $.each(options, function (key, value) {
                return self[key] = value;
            });
        };
        Instagram.prototype.fetch = function (url, callback, params, method) {
            var ajaxData, data;
            data = {};
            if (this.token) {
                data['access_token'] = this.token;
            }
            if (this.client_id) {
                data['client_id'] = this.client_id;
            }
            if (params != '') {
                data['max_id'] = params;
            }
            ajaxData = {
                url: this.api,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: method || 'GET',
                    url: url,
                    params: $.extend({}, data, params)
                },
                success: function (res) {
                    var code;
                    code = res.result.meta.code;
                    switch (code) {
                    case '200':
                        callback(res.data);
                        break;
                    case '400':
                        console.log;
                    }
                    return callback(res.result);
                }
            };
            return $.ajax(ajaxData);
        };
		Instagram.prototype.fetch2 = function (url, callback, params, method) {
            var ajaxData, data;
            data = {};
            if (this.token) {
                data['access_token'] = this.token;
            }
            if (this.client_id) {
                data['client_id'] = this.client_id;
            }
            if (params != '') {
                data['cursor'] = params;
            }
            ajaxData = {
                url: this.api,
                type: 'POST',
                dataType: 'json',
                data: {
                    method: method || 'GET',
                    url: url,
                    params: $.extend({}, data, params)
                },
                success: function (res) {
                    var code;
                    code = res.result.meta.code;
                    switch (code) {
                    case '200':
                        callback(res.data);
                        break;
                    case '400':
                        console.log;
                    }
                    return callback(res.result);
                }
            };
            return $.ajax(ajaxData);
        };
        Instagram.prototype.currentUser = function (callback) {
            return this.fetch('/users/self', callback);
        };
        Instagram.prototype.getFeeds = function (callback, params) {
            return this.fetch('/users/self/feed', callback, params);
        };
        Instagram.prototype.getLiked = function (callback, params) {
            return this.fetch('/users/self/media/liked', callback, params);
        };
        Instagram.prototype.getReqs = function (callback) {
            return this.fetch('/users/self/requested-by', callback);
        };
        Instagram.prototype.getIdByName = function (name, callback) {
            return this.searchUser(name, function (res) {
                var lists, obj;
                lists = res.data;
                name = name.toLowerCase();
                if (lists) {
                    obj = lists[0];
                }
                if (obj && obj['username'] === name) {
                    return callback(obj['id']);
                } else {
                    return callback(false);
                }
            });
        };
        Instagram.prototype.getUser = function (id, callback) {
            return this.fetch('/users/' + id, callback);
        };
        Instagram.prototype.getUserByName = function (name, callback) {
            var self;
            self = this;
            return this.getIdByName(name, function (id) {
                if (id) {
                    return self.getUser(id, function (res) {
                        return callback(res);
                    });
                }
            });
        };
        Instagram.prototype.getPhotos = function (id, callback, params) {
            return this.fetch('/users/' + id + '/media/recent', callback, params);
        };
        Instagram.prototype.getPhotospag = function (id, callback, params) {
            return this.fetch('/users/' + id + '/media/recent', callback, params);
        };
        Instagram.prototype.getFollowing = function (id, callback, params) {
            return this.fetch('/users/' + id + '/follows', callback, params);
        };
        Instagram.prototype.getFans2 = function (id, callback, params) {
            return this.fetch2('/users/' + id + '/followed-by', callback, params);
        };
        Instagram.prototype.getRelationship = function (id, callback) {
            return this.fetch('/users/' + id + '/relationship', callback);
        };
        Instagram.prototype.isPrivate = function (id, callback) {
            return this.getUser(id, function (res) {
                return callback(res.meta.error_message === 'you cannot view this resource');
            });
        };
        Instagram.prototype.isFollowing = function (id, callback) {
            return this.getRelationship(id, function (res) {
                return callback(res.data.outgoing_status === 'follows');
            });
        };
        Instagram.prototype.isFollowedBy = function (id, callback) {
            return this.getRelationship(id, function (res) {
                return callback(res.data.incoming_status !== 'none');
            });
        };
        Instagram.prototype.editRelationship = function (id, callback, action) {
            return this.fetch('/users/' + id + '/relationship', callback, {
                action: action
            }, 'POST');
        };
        Instagram.prototype.follow = function (id, callback) {
            return this.editRelationship(id, callback, 'follow');
        };
        Instagram.prototype.unfollow = function (id, callback) {
            return this.editRelationship(id, callback, 'unfollow');
        };
        Instagram.prototype.block = function (id, callback) {
            return this.editRelationship(id, callback, 'block');
        };
        Instagram.prototype.unblock = function (id, callback) {
            return this.editRelationship(id, callback, 'unblock');
        };
        Instagram.prototype.approve = function (id, callback) {
            return this.editRelationship(id, callback, 'approve');
        };
        Instagram.prototype.deny = function (id, callback) {
            return this.editRelationship(id, callback, 'deny');
        };
        Instagram.prototype.searchUser = function (q, callback) {
            return this.fetch('/users/search?q=' + q, callback);
        };
        Instagram.prototype.getPhoto = function (id, callback, params) {
            return this.fetch('/media/' + id, callback, params);
        };
        Instagram.prototype.searchPhoto = function (callback, params) {
            return this.fetch('/media/search', callback, params);
        };
        Instagram.prototype.getPopular = function (callback, params) {
            return this.fetch('/media/popular', callback, params);
        };
        Instagram.prototype.getComments = function (id, callback, params) {
            return this.fetch('/media/' + id + '/comments', callback, params);
        };
        Instagram.prototype.postComment = function (id, callback, params) {
            return this.fetch('/media/' + id + '/comments', callback, params, 'POST');
        };
        Instagram.prototype.deleteComment = function (id, callback) {
            return this.fetch('/media/' + id + '/comments', callback, {}, 'DELETE');
        };
        Instagram.prototype.getLikes = function (id, callback, params) {
            return this.fetch('/media/' + id + '/likes', callback, params);
        };
        Instagram.prototype.addLike = function (id, callback) {
            return this.fetch('/media/' + id + '/likes', callback, {}, 'POST');
        };
        Instagram.prototype.deleteLike = function (id, callback) {
            return this.fetch('/media/' + id + '/likes', callback, {}, 'DELETE');
        };
        Instagram.prototype.getTag = function (tagName, callback, params) {
            return this.fetch('/tags/' + tagName, callback, params);
        };
        Instagram.prototype.getRecentTags = function (tagName, callback, params) {
            return this.fetch('/tags/' + tagName + '/media/recent', callback, params);
        };
        Instagram.prototype.searchTag = function (q, callback, params) {
            return this.fetch('/tags/search?q=' + q, callback, params);
        };
        Instagram.prototype.getLocation = function (locId, callback, params) {
            return this.fetch('/locations/' + locId, callback, params);
        };
        Instagram.prototype.getRecentLocations = function (locId, callback, params) {
            return this.fetch('/locations/' + locId + '/media/recent', callback, params);
        };
        Instagram.prototype.searchLocation = function (callback, params) {
            return this.fetch('/locations/search', callback, params);
        };
        Instagram.prototype.getNearby = function (id, callback, params) {
            return this.fetch('/geographies/' + id + '/media/recent', callback, params);
        };
        return Instagram;
    })();
    return exports.Instagram = Instagram;
})(jQuery, window);