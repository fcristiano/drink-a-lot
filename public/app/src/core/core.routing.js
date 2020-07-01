'use strict';

(function(angular) {

    angular.module('AwCoBarRouting', [
        'SharedModule'
    ])
    .config(routingConfig);

    routingConfig.$inject = ['$routeProvider'];

    function routingConfig($routeProvider) {

        $routeProvider.when('/', {
            template: '<table-selection></table-selection>',
        });
        $routeProvider.when('/ingredients', {
            template: '<ingredients></ingredients>',
        });
        $routeProvider.when('/drinks/by-ingredient/:name', {
            template: '<drinks-by-ingredient></drinks-by-ingredient>',
        });
        $routeProvider.when('/order/summary', {
            template: '<order-summary></order-summary>',
        });
        $routeProvider.when('/order/status', {
            template: '<order-status></order-status>',
        });

        $routeProvider.otherwise({redirectTo: '/'});
    }

})(window.angular);