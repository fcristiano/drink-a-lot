'use strict';

(function(angular) {

    angular.module('AwCoBar')
        .component('ingredients', {
            templateUrl: '/app/src/core/ingredients/ingredients.component.html',
            controller: Ingredients
        });

    Ingredients.$inject = ['$http', '$location', 'LocalStorage'];

    function Ingredients($http, $location, LocalStorage) {
        if(!LocalStorage.getItem('tableNumber') || !LocalStorage.getItem('orderId')) {
            $location.path('/');
            return;
        }

        if(LocalStorage.getItem('orderConfirmed')) {
            $location.path('/order/summary');
            return;
        }


        var ingredients = {
            list: {
                isLoading: false,
                filter: '',
                items: []
            }
        };

        this.ingredients = ingredients;


        function getIngredients() {
            ingredients.list.isLoading = true;

            $http({
                method: 'GET',
                url:    '/api/v1/ingredients'
            })
            .then(
                function(response) {
                    ingredients.list.isLoading = false;
                    ingredients.list.items = response.data.payload;
                },
                function(data, status, headers, config) {
                    ingredients.list.isLoading = false;
                    alert(data.data.error.message);
                }
            );
        }


        getIngredients()
    }
})(window.angular);