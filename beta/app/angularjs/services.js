/**
 * The Angular service script
 */

'use strict';

/**
 * The http service based on the $http angular intrisic service
 * It shows the loader before request and hides it after request is completed
 */
user.factory('httpReq', function($http, $window, showAlert, constants){
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
    var send = function(url, data, method = 'POST', callbackObject, background = false){
        var rest_data = JSON.stringify(data);
        var module = localStorage.getItem("module");
        (background)?null:show(); //show loader
        $http({
            method: method,
            url: 'http://localhost/laser/beta' + url,
            data: rest_data
        }).then(function success(response){
            hide() //hide loader
            callbackObject.success(response)
        }, function error(response){
            hide(); //hide loader
            if(callbackObject.error){
                callbackObject.error(response)
            }
            else{
                switch(response.status){
                    case 400:{
                        showAlert.warning(response.data.message);
                        break;
                    }
                    case 401:{
                        showAlert.warning(response.data.message);
                        break;
                    }
                    case 403:{
                        showAlert.danger(response.data.message);
                        break;
                    }
                    case 404:{
                        showAlert.warning(response.data.message);
                        break;
                    }
                    default:{
                        showAlert.warning(constants.unknownError);
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
           url:    'http://localhost/laser/modules/' + module + '/api' + uploadUrl, 
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

/**
 * Alert services that shows alert messages and takes action
 */
user.factory('showAlert', function($window, $timeout){
      //register close event
    $(".close-alert").click(function(){
        $('.alert').css('display', 'none');
        $('.alert-header').css('z-index', '1');
    });
    var success = function(msg){
        $('.alert-header').css('z-index', '22');
        $('#success-msg').text(msg);
        //document.getElementById('success-msg').nodeValue = msg;
        $('.alert-success').css('display', 'block');
    }
    var warning = function(msg){
        $('.alert-header').css('z-index', '22');
        $('#warning-msg').text(msg);
        //document.getElementById('warning-msg').nodeValue = msg;
        $('.alert-warning').css('display', 'block');
    }
    var danger = function(msg){
        $('.alert-header').css('z-index', '22');
        $('#danger-msg').text(msg);
        //document.getElementById('danger-msg').nodeValue = msg;
        $('.alert-danger').css('display', 'block');
        $timeout(function(){
            $('.alert-danger').alert('close');
            $window.location.href = 'http://localhost/laser';
        }, 3000);
    }
    return {
        success: success,
        warning: warning,
        danger: danger
        };
});

//Constants factory
user.factory('constants', function(){
    const data = {unknownError: "unknown internal error, workspace might be empty. Hint: Reload browser window"};
    return data;
})