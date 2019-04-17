
user.run(function($rootScope, $templateCache) {
    $rootScope.$on('$viewContentLoaded', function() {
       $templateCache.removeAll();
    });
 });