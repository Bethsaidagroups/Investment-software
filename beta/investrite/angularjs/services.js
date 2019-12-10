/**
 * The Angular service script
 */

'use strict';

 /**
 * The http service based on the $http angular intrisic service
 * It shows the loader before request and hides it after request is completed
 */

user.factory('httpReq', function($http, $window, $httpParamSerializerJQLike, constants){
    //script for loader
		var show = function(){
            $('#loader').Wload({
              text:'Loading'
            })
          };
      var hide = function(){
            $('#loader').Wload('hide',{
              time:1000 // auto close after 1 seconds
                })
          };
    
    //This http send function works by using module context url
    var send = function(init = {url:'', data:null, method:'POST', callback:null, background:false}){
        var rest_data = init.data;
        (init.background)?null:show(); //show loader
        $http({
            method: init.method,
            url: constants.base_url + init.url,
            data: $httpParamSerializerJQLike(rest_data),
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        }).then(function success(response){
            hide() //hide loader
            init.callback.success(response)
        }, function error(response){
            hide(); //hide loader
            if(init.callback.error){
                init.callback.error(response)
            }
            else{
                switch(response.status){
                    case 400:{
                        swal("Something Went Wrong", response.data.error, "error");
                        break;
                    }
                    case 401:{
                        swal("Unauthorized Access", 'You might not have the permission to perform this operation' , "error");
                        break;
                    }
                    case 403:{
                        swal("Forbiden Access", response.data.error, "error");
                        window.location.assign(constants.main_login)
                        break;
                    }
                    case 404:{
                        swal("What do you Want?",'Resource not found', "error");
                        break;
                    }
                    default:{
                        swal("Something Went Wrong", constants.unknownError, "error");
                    }
                }
            }
        });
    }

    //This is a variant of the former send function it does not use module context url
    var sendNoContext = function(url, data, method = 'POST', callback){
        var rest_data = JSON.stringify(data);
        var module = localStorage.getItem("module");
        show(); //show loader
        $http({
            method: method,
            url: url,
            data: rest_data
        }).then(function success(response){
            hide() //hide loader
            callback.success(response)
        }, function error(response){
            hide(); //hide loader
            callback.error(response)
        });
    }

    //File upload
    var uploadFileToUrl = function(file, uploadUrl, callback) {
        var module = localStorage.getItem("module");
        var fd = new FormData();
        fd.append('file', file);
        show(); //show loader
        $http({
           method: 'post',
           url:    constants.base_url + module + '/api' + uploadUrl, 
           data:    fd,
           headers: {'Content-Type': undefined}
        }).then(function success(response){
            hide() //hide loader
            callback.success(response)
        }, function error(response){
            hide(); //hide loader
            callback.error(response)
        });
     }
    return {send: send,
            sendNoContext: sendNoContext,
            uploadFileToUrl: uploadFileToUrl
            };
});


//Constants factory
user.factory('constants', function(){
  const data = {unknownError: "unknown internal error, workspace might be empty. Hint: Reload browser window",
                base_url: 'http://127.0.0.1:5000/staff/api',
                home_url: 'http://localhost/laser/beta/app',
                main_login: 'http://localhost/laser/',
                };
  return data;
});

//UserModel
user.factory('userModel', function(){
    var factory = {};
    var model = {};

    factory.setUser = function(value){
        model = value;
    }
    factory.getUser = function(){
        return model;
    }
    return factory;
});