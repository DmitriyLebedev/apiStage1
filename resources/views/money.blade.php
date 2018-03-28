@extends('layouts.app')

@section('content')
<script src="public/src/js/money.js"></script>

<script>
var types = '<?= $types ?>';
</script>

<div ng-app="myApp" ng-controller="moneyCtrl">
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="filters">
                        <label>Filter:</label>
                        <select ng-model="selectedType" ng-change="filterByType()">
                            <option value="">Expence Type</option>
                            <option ng-repeat="x in types" value="<% x.type %>"><%  x.type %></option>
                        </select>
                        <select ng-model="selectedYear" ng-change="filterByYear()">
                            <option value="">Year</option>
                            <option ng-repeat="x in years" value="<% x.year %>"><%  x.year %></option>
                        </select>

                        <label ng-repeat="(key, month) in monthNames">
                            <input type="checkbox" value="<% month %>" ng-checked="selection.indexOf(month) > -1" ng-click="filterByMonth(key)">
                            <% month %>
                        </label>
                    </div>
                    <br />
                    <hr />
                    <div>
                        Total:<% total %>&nbsp;|&nbsp;Transport:<% transport %>&nbsp;|&nbsp;Food Out:<% food_out %>&nbsp;|&nbsp;Food Store:<% food_in %>

                        <a href="#" id="add_record" ng-click="recordEdit('add_record')">Add Record</a>
                    </div>
                    <hr />
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 15%" ng-click="sortBy('act_date')">Date
                                    <span class="sortorder" ng-show="propertyName === 'act_date'" ng-class="{reverse: reverse}"></span>
                                </th>
                                <th style="width: 10%" ng-click="sortBy('type')">Type
                                    <span class="sortorder" ng-show="propertyName === 'type'" ng-class="{reverse: reverse}"></span>
                                </th>
                                <th style="width: 15%" ng-click="sortBy('amount')">Amount
                                    <span class="sortorder" ng-show="propertyName === 'amount'" ng-class="{reverse: reverse}"></span>                            
                                </th>
                                <th style="width: 10%" ng-click="sortBy('source')">Source
                                    <span class="sortorder" ng-show="propertyName === 'source'" ng-class="{reverse: reverse}"></span>
                                </th>
                                <th style="width: 25%" ng-click="sortBy('comment')">Comment
                                    <span class="sortorder" ng-show="propertyName === 'comment'" ng-class="{reverse: reverse}"></span>                              
                                </th>
                                <th style="width: 5%">&nbsp;</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="record in records">
                                <td><% record.act_date_display %></td>                            
                                <td><% record.type %></td>
                                <td><% record.amount %></td>
                                <td><% record.source %></td>
                                <td><% record.comment %></td>
                                <td><a href="#" ng-click="recordEdit(record)">Edit</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
   
<div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><% modal_title %></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" ng-model="active_element.act_date_display" id="date-data" />
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select ng-model="active_element.type" >
                            <option value=""></option>
                            <option ng-repeat="(key, type) in typeNames" value="<% type %>"><% type %></option>
                        </select>

                    </div>
                     <div class="form-group">
                        <label>Amount</label>
                        <input type="text" ng-model="active_element.amount" />
                    </div>
                    <div class="form-group">
                        <label>Source</label>
                        <input type="text" ng-model="active_element.source" />
                    </div>
                     <div class="form-group">
                        <label>Comment</label>
                        <input type="text" ng-model="active_element.comment" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" ng-click="recordSave()">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- ng-app -->
<script>
var token = '{{ Session::token() }}';
var url = '{{ route('edit') }}';
$( function() {
   $( "#date-data" ).datepicker();
} );
</script>

@endsection
