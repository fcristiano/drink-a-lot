'use strict';

(function(angular) {

    angular.module('AwCoBar', [
        'ng',
        'ngRoute',

        'SharedModule',

        'AwCoBarRouting',
    ]);

    angular.module('AwCoBar').run(bootstrap);

    bootstrap.$inject = [];

    function bootstrap() {

    }

})(window.angular);