'use strict';

(function(angular) {

    angular.module('AwCoBar')
        .component('tableSelection', {
            templateUrl: '/app/src/core/bar-table/table-selection.component.html',
            controller: TableSelection
        });

    TableSelection.$inject = ['$http', '$location', 'LocalStorage'];

    function TableSelection($http, $location, LocalStorage) {
        if(LocalStorage.getItem('tableNumber') && LocalStorage.getItem('orderId')) {
            $location.path('/ingredients');
            return;
        }


        var barTable = {
            form: {
                isLoading: false,
                input: {
                    tableNumber: null
                },
                selectTable: selectTable
            },
        };

        this.barTable = barTable;


        function selectTable() {
            // @TODO for now, frontend checks are skipped

            barTable.form.isLoading = true;

            $http({
                method: 'POST',
                url:    '/api/v1/customer/orders',
                data: {
                    table_number: barTable.form.input.tableNumber
                }
            })
            .then(
                function(response) {
                    barTable.form.isLoading = false;
                    LocalStorage.setItem('tableNumber', response.data.payload.bar_table.number);
                    LocalStorage.setItem('orderId',     response.data.payload.id);

                    $location.path('/ingredients')
                },
                function(response, status, headers, config) {
                    barTable.form.isLoading = false;
                    alert(response.data.error.message);
                }
            );
        }
    }
})(window.angular);