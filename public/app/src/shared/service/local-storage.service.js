'use strict';

(function(angular) {
    angular.module('SharedModule')
        .factory('LocalStorage', LocalStorage);

    LocalStorage.$inject = ['$window'];

    function LocalStorage($window) {
        var service;
        service = {
            setItem: setItem,
            getItem: getItem,
            remove: remove
        };
        return service;


        function setItem(key, object) {
            $window.localStorage.setItem(key, JSON.stringify(object));
        }

        function getItem(key, object) {
            return JSON.parse($window.localStorage.getItem(key));
        }

        function remove(key) {
            $window.localStorage.removeItem(key);
        }

    }

})(window.angular);