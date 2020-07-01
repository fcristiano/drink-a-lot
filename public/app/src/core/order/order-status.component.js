'use strict';

(function(angular) {

    angular.module('AwCoBar')
        .component('orderStatus', {
            templateUrl: '/app/src/core/order/order-status.component.html',
            controller: OrderStatus
        });

    OrderStatus.$inject = ['$http', '$location', 'LocalStorage', '$interval', '$timeout'];

    function OrderStatus($http, $location, LocalStorage, $interval, $timeout) {
        if(!LocalStorage.getItem('orderConfirmed')) {
            $location.path('/');
            return;
        }


        var order = {
            isLoading: false,
            data: null,
            // progress: {
            //     value: 0,
            //     description: null
            // },
            statuses: {
                ready_to_be_made: {
                    progress: 20,
                    description: 'Your order is waiting to be processed.'
                },
                bartender_making: {
                    progress: 30,
                    description: 'A cool bartender is making your cocktails.'
                },
                ready_to_be_delivered: {
                    progress: 60,
                    description: 'Your order is ready.'
                },
                waiter_delivering: {
                    progress: 70,
                    description: 'An even more cool waiter is delivering your cocktails.'
                },
                delivered: {
                    progress: 90,
                    description: 'Enjoy your drinks!'
                },
                payed: {
                    progress: 100,
                    description: 'Order payed. Thanks :)'
                },
                discarded: {
                    progress: 100,
                    description: 'Sadly your order has gone in a better place, please make another one.'
                },
            }
        };

        this.order = order;


        function getOrder(orderId, enablePolling = false) {
            order.isLoading = true;

            $http({
                method: 'GET',
                url:    '/api/v1/customer/orders/'+ orderId
            })
            .then(
                function(response) {
                    order.isLoading = false;
                    order.data = response.data.payload;

                    if(enablePolling === true) {
                        polling();
                    }
                },
                function(response, status, headers, config) {
                    order.isLoading = false;
                    alert(response.data.error.message);
                }
            );
        }

        function polling() {
            var intervalId = $interval(function() {
                getOrder(parseInt(LocalStorage.getItem('orderId')));

                if(order.data.status.name === 'payed' || order.data.status.name === 'discarded') {
                    $interval.cancel(intervalId);

                    LocalStorage.remove('orderConfirmed');
                    LocalStorage.remove('orderId');
                    LocalStorage.remove('tableNumber');

                    $timeout(function() {
                        $location.path('/')
                    }, 5000)
                }
            }, 3000)
        }

        getOrder(parseInt(LocalStorage.getItem('orderId')), true);
    }
})(window.angular);