var siscpa = angular.module('SiscpaApp', ['ui.bootstrap', 'ngTouch', 'ngAnimate', 'Barbara-Js']);

siscpa.config(function ($httpProvider) {
    $httpProvider.defaults.useXDomain = true;
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
});

siscpa.constant('PATH', {
    AJAX: "./admin-ajax.php",
    POST: "./admin-post.php",
    httpQueryBuilder: function (data) {
        return "?" + Object.keys(data).map(function (key) {
                return [key, data[key]].map(encodeURIComponent).join("=");
            }).join("&");
    },
    ajaxQuery: function (data) {
        return this.AJAX + this.httpQueryBuilder(data);
    },
    postQuery: function (data) {
        return this.POST + this.httpQueryBuilder(data);
    }
});

siscpa.filter('dateToISO', function() {
    return function(input) {
        return new Date(input).toISOString();
    };
});

siscpa.run(function ($rootScope, bootstrap) {
    $rootScope.alert = bootstrap.alert();
    $rootScope.loading = bootstrap.loading();
});

siscpa.directive('materialKit', function(){
    return {
        restrict : 'A',
        link     : function(scope){
            if(angular.isUndefined(scope.materialInit)){
                $.material.init();
                scope.materialInit = true;
            }
        }
    }
});