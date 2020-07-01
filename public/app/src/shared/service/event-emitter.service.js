'use strict';

(function(angular) {
    angular.module('SharedModule')
        .factory('EventBroadcaster', EventBroadcaster);

    EventBroadcaster.$inject = ['$rootScope'];

    function EventBroadcaster($rootScope) {
        var service;
        service = {
            trigger: trigger
        };
        return service;

        function trigger(evtName, args) {
            $rootScope.$broadcast(evtName, args);
        }
    }

})(window.angular);