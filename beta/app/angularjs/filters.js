/**
 * The angular filters
 */
'use strict';

//Date time filter
user.filter('moment', function () {
    return function (input, momentFn /*, param1, param2, ...param n */) {
      var args = Array.prototype.slice.call(arguments, 2),
          momentObj = moment(input);
      return momentObj[momentFn].apply(momentObj, args);
    };
  });

  //user type and office filter filter
user.filter('database', function(){
    //get the user type and office from server
    return function(input, context){
        var types = JSON.parse(localStorage.getItem('types'));
        var offices = JSON.parse(localStorage.getItem('offices'));
        //do for context equal to type
        if(context == 'type'){
            for(var index in types){
                if(types[index].id == input){
                    return types[index].name;
                }
            }
        }
        //do for context equal to office
        if(context == 'office'){
            for(var index in offices){
                if(offices[index].id == input){
                    return offices[index].location + " | " + offices[index].description;
                }
            }
        }
        //do for context equal to access
        if(context == 'access'){
            if(input == 0){
                return 'Restricted';
            }
            else{
                return 'Open'
            }
        }
    };
});

//Date time filter
user.filter('get_amount_left', function () {
    return function (amount, balance, status) {
      if(status == 'unpaid'){
        if(balance >= 0){
            return (0.00).toLocaleString()
        }
        else{
            return Math.abs(parseFloat(balance)).toLocaleString()
        }
      }
      else{
            return (0.00).toLocaleString()
      }
    };
  });