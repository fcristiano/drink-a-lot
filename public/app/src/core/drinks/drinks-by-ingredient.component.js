'use strict';

(function(angular) {

    angular.module('AwCoBar')
        .component('drinksByIngredient', {
            templateUrl: '/app/src/core/drinks/drinks-by-ingredient.component.html',
            controller: DrinksByIngredients
        });

    DrinksByIngredients.$inject = ['$http', '$location', '$routeParams', 'LocalStorage'];

    function DrinksByIngredients($http, $location, $routeParams, LocalStorage) {
        if(!LocalStorage.getItem('tableNumber') || !LocalStorage.getItem('orderId')) {
            $location.path('/');
            return;
        }

        if(LocalStorage.getItem('orderConfirmed')) {
            $location.path('/order/summary');
            return;
        }


        if(!$routeParams.name) {
            alert('Ingredient not specified');
            $location.path('/ingredients');
        }

        var drinks = {
            ingredient: $routeParams.name,
            list: {
                isLoading: false,
                items: []
            },
            order: {
                isLoading: false,
                addDrinkToOrder: addDrinkToOrder
            },
            detail: {
                isLoading: false,
                data: null,
                get: getDrinkDetail
            }
        };

        this.drinks = drinks;


        function getDrinksByIngredient() {
            drinks.list.isLoading = true;

            $http({
                method: 'GET',
                url:    '/api/v1/drinks',
                params: {
                    ingredient_name: drinks.ingredient
                }
            })
            .then(
                function(response) {
                    drinks.list.isLoading = false;
                    drinks.list.items = response.data.payload;
                },
                function(data, status, headers, config) {
                    drinks.list.isLoading = false;
                    alert(data.data.error.message);
                }
            );
        }

        function addDrinkToOrder(drinkId) {
            // @TODO for now, frontend checks are skipped
            drinks.order.isLoading = true;

            var orderId = parseInt(LocalStorage.getItem('orderId'));

            $http({
                method: 'POST',
                url:    '/api/v1/customer/orders/'+ orderId +'/details',
                params: {
                    drink_id: drinkId
                }
            })
            .then(
                function(response) {
                    drinks.order.isLoading = false;
                    $location.path('/order/summary')
                },
                function(response, status, headers, config) {
                    drinks.order.isLoading = false;
                    alert(response.data.error.message);
                }
            );
        }

        function getDrinkDetail(drinkId) {
            drinks.detail.isLoading = true;

            $http({
                method: 'GET',
                url:    '/api/v1/drinks/'+ drinkId
            })
            .then(
                function(response) {
                    drinks.detail.isLoading = false;
                    drinks.detail.data = response.data.payload;
                },
                function(response, status, headers, config) {
                    drinks.detail.isLoading = false;
                    alert(response.data.error.message);
                }
            );
        }


        getDrinksByIngredient()
    }
})(window.angular);