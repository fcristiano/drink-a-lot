'use strict';

(function(angular) {

    angular.module('AwCoBar')
        .component('orderSummary', {
            templateUrl: '/app/src/core/order/order-summary.component.html',
            controller: OrderSummary
        });

    OrderSummary.$inject = ['$http', '$location', 'LocalStorage'];

    function OrderSummary($http, $location, LocalStorage) {
        if(!LocalStorage.getItem('tableNumber') || !LocalStorage.getItem('orderId')) {
            $location.path('/');
            return;
        }

        if(LocalStorage.getItem('orderConfirmed')) {
            $location.path('/order/summary');
            return;
        }


        var order = {
            isLoading: false,
            data: null,
            amount: 0,
            confirm: {
                idLoading: false,
                perform: confirmOrder
            }
        };

        var orderDetails = {
            isLoading: false,
            data: null,
        };

        this.order        = order;
        this.orderDetails = orderDetails;


        function getOrderDetails(orderId) {
            orderDetails.isLoading = true;

            $http({
                method: 'GET',
                url:    '/api/v1/customer/orders/'+ orderId +'/details'
            })
            .then(
                function(response) {
                    orderDetails.isLoading = false;
                    orderDetails.data = response.data.payload;

                    angular.forEach(orderDetails.data, function(orderDetail, key) {
                        order.amount += orderDetail.price;
                        fillOrderDetail(orderDetail);
                    })
                },
                function(response, status, headers, config) {
                    orderDetails.isLoading = false;
                    alert(response.data.error.message);
                }
            );
        }

        function fillOrderDetail(orderDetail) {
            $http({
                method: 'GET',
                url:    '/api/v1/drinks/'+ orderDetail.drink_id
            })
            .then(
                function(response) {
                    orderDetail.drink_thumb = response.data.payload.strDrinkThumb.replace('http:', '')
                    orderDetail.drink_name = response.data.payload.strDrink
                },
                function(response, status, headers, config) {

                }
            );
        }


        function confirmOrder() {
            order.confirm.isLoading = true;

            $http({
                method: 'PATCH',
                url:    '/api/v1/customer/orders/'+ parseInt(LocalStorage.getItem('orderId')),
                data: {
                    status: 'customer_confirmed'
                }
            })
            .then(
                function(response) {
                    order.confirm.isLoading = false;
                    LocalStorage.setItem('orderConfirmed', 1);

                    $location.path('/order/status');
                },
                function(response, status, headers, config) {
                    order.confirm.isLoading = false;
                    alert(response.data.error.message);
                }
            );
        }


        getOrderDetails(parseInt(LocalStorage.getItem('orderId')));
    }
})(window.angular);