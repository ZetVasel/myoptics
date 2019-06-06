@extends('admin.layout')


@section('main')

	<h1>
		<a href="/master/products_management" class="glyphicon glyphicon-circle-arrow-left"></a>
		<a href="/master/categories/add" class="glyphicon glyphicon-plus-sign"></a>{{ $title }} <span class="label label-default">{{ $categories_count }}</span>
	</h1>
	
		@if( $categories_count>0 )
			{!! Form::open() !!}
			
				<table class="table" style="margin-bottom: 23px;">
					<tr>
						<th style="width: 40%;">Название</th>
						<th style="text-align: right;">Управление</th>
					</tr>
				</table>

					<div ng-app="treeApp">
						<div ng-controller="treeCtrl">

							<script type="text/ng-template" id="nodes_renderer.html">
								<div class="tree-node">
								  <div class="pull-left tree-handle" ui-tree-handle>
									<span class="glyphicon glyphicon-list"></span>
								  </div>
								  <div class="tree-node-content">
									<!--<a class="btn btn-success btn-xs" ng-if="node.children && node.children.length > 0" nodrag ng-click="toggle(this)"><span class="glyphicon" ng-class="{'glyphicon-chevron-right': collapsed, 'glyphicon-chevron-down': !collapsed}"></span></a>//-->
									<div style="display: inline-block; margin-right: 9px;"> <input name="check[]" type="checkbox" value="[[node.id]]"></div><a class="black_link" href="/master/categories/edit/[[node.id]]">[[node.name]]</a>
									<div class="pull-right btn-group" style="margin-top: -6px; margin-right: -5px;">
										<a title="Редактировать" href="/master/categories/edit/[[node.id]]" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
										<button title="Удалить" type="button" class="delete btn btn-danger" data-id="[[node.id]]"><span class="glyphicon glyphicon-remove"></span></button>
									</div>
								  </div>
								</div>
								<ol ui-tree-nodes="" ng-model="node.children" ng-class="{hidden: collapsed}">
								  <li ng-repeat="node in node.children" ui-tree-node ng-include="'nodes_renderer.html'">
								  </li>
								</ol>
							</script>

							<div ui-tree="dataOptions" id="tree-root">
							  <ol ui-tree-nodes ng-model="data">
								<li ng-repeat="node in data" ui-tree-node ng-include="'nodes_renderer.html'"></li>
							  </ol>
							</div>
						</div>
					</div>

				<div class="select_form">
					<label id="check_all" class="link">Выбрать все</label>
					<select name="action" class="form-control">
					  <option value="delete">удалить</option>
					</select>
					
					<button type="submit" style="margin-left: 20px;" class="btn btn-success">Применить</button>
				</div>
			{!! Form::close() !!}
		
		@else
			<div class="alert alert-warning" role="alert">
			 Нету категорий
			</div>
		@endif
@endsection

@section('scripts')

<script src="/dashboard/bower_components/angular/angular.min.js"></script>
<script src="/dashboard/bower_components/angular/angular-ui-tree.min.js"></script>

<script>
	//angular
	(function() {
	  'use strict';

		var app = angular.module('treeApp', ['ui.tree'], function($interpolateProvider) {
			$interpolateProvider.startSymbol('[[');
			$interpolateProvider.endSymbol(']]');
		});
		
		app.controller('treeCtrl', function($scope, $http) {

			$scope.data = {!!$categories!!}

			$scope.dataOptions = {
				dropped: function(event) {
				
					$http.post('/master/categories', { _token: '{{ Session::token() }}', data: $scope.data, action: 'rebuild' }).
					  success(function(data, status, headers, config) {
						console.log(data);
					  }).
					  error(function(data, status, headers, config) {
						alert('error');
					});
				}
			};
		});
	 })();

	 // jquery
	 $(function() {
		
		// отметим всех детей, если они есть и отметили родителя
		$('input[name*="check"]').on( 'click', function() { 
			if( !$(this).is(':checked') ) $(this).closest("li").find('input[type="checkbox"][name*="check"]').prop('checked', false);
			else $(this).closest("li div").find('input[type="checkbox"][name*="check"]').prop('checked', true);
		}); 
		
		// удаление записи
		$('.delete').click( function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', false);
			$(this).closest("li .tree-node-content").find('input[type="checkbox"][name*="check"]').prop('checked', true);
			$(this).closest("form").find('select[name="action"] option[value=delete]').attr('selected', true);
			$(this).closest("form").submit();
		})
		
		// удаление записей
		$("form").submit(function() {
			if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
				return false;	
		});
		
		// выделить все
		$("#check_all").on( 'click', function() {
			$('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
		});	
	})
</script>
@endsection