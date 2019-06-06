var app = angular.module('myApp', ['ui.grid','ui.grid.edit','ui.grid.pagination', 'ui.grid.selection']);

 app.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
  });

app.controller('MainCtrl', ['$scope', '$http', 'uiGridConstants', '$httpParamSerializer', '$window', 'i18nService',
function ($scope, $http, uiGridConstants, $httpParamSerializer, $window, i18nService) {

i18nService.setCurrentLang('en');

	$scope.gridOptions = {
	    enableSorting: true,
	    enableFiltering: true,
	    paginationPageSizes: [25, 50, 75],
	    paginationPageSize: 25,
	    rowHeight: 40,
	    minRowsToShow: 15,

	    columnDefs: [
	      { field: 'name', name: 'Название товара', minWidth: 300, enableColumnMenu: false, sort: { direction: uiGridConstants.ASC, priority: 1 }},
	      { field: 'code', name: 'Код товара', minWidth: 150, sort: { direction: uiGridConstants.DESC, priority: 2 }, enableColumnMenu: false }, 
	      { field: 'cat_name',  name: 'Категория', minWidth: 150, enableColumnMenu: false },
	      { field: 'price', name: 'Цена', minWidth: 80, enableFiltering: false, enableColumnMenu: false, enableSorting: false },
		  { field: 'reserve', name: 'Запас', minWidth: 80, enableFiltering: false, enableColumnMenu: false, enableSorting: false },
		  { 
			name: 'Управление',
			enableFiltering: false,
			enableSorting: false,
			enableColumnMenu: false,
			minWidth: 120,
			cellTemplate:'<a ng-click="grid.appScope.edit([[row.entity.type]], [[row.entity.id]])" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a><a title="Удалить" ng-click="grid.appScope.delete([[row.entity.id]])" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></a>'
		  }
	    ]
	};

	// опции плагина grid
	$scope.gridOptions.onRegisterApi = function(gridApi){
	  $scope.gridApi = gridApi;
	}

	$scope.loading = true;

	// получение всех товаров и дерева категорий
	$http.get('/master/products/info').success(function(data){

		$scope.gridOptions.data = data.products;
		$scope.categories = data.categories;
		$scope.loading = false;
	});

	// сортировка по категории
	$scope.categoryChecking = function(id){

        $http({
            method: 'POST',
            url: '/master/products/category',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $httpParamSerializer({id: id})
        }).success(function(data){
        	console.log(data);
        	$scope.gridOptions.data = data;
        });
	}

	// перенаправление на редактирование товара
	$scope.edit = function(type, id){
		$window.location.href = '/master/products/edit/'+ type + '/' + id;
	}

	// удаление 1 товара
	$scope.delete = function(id){

        $http({
            method: 'POST',
            url: '/master/products/show',
            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
            data: $httpParamSerializer([Number(id)])
        }).success(function(data){
        	$scope.gridOptions.data = data;
        	console.log(data);
        });
	}

	// даление выбранных товаров
	$scope.deleteSelected = function(){

		var ids = [];
		var selected = $scope.gridApi.selection.getSelectedRows();
		if( selected.length ){

			angular.forEach(selected, function(item){
				ids.push(item.id);
			})

	        $http({
	            method: 'POST',
	            url: '/master/products/show',
	            headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
	            data: $httpParamSerializer(ids)
	        }).success(function(data){
	        	$scope.gridOptions.data = data;
	        });
		}
	}
}]);