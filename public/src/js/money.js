var fields = [];
var recordId = 0;
var active_element = {};
var requestGet = {};
requestGet['year'] = '2018';

var app = angular.module('myApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
});

app.controller('moneyCtrl', ['$scope', 'orderByFilter', '$http', function($scope, orderBy, $http) {

    $scope.monthNames = { 1: "Jan", 2: "Feb", 3: "Mar", 4: "Apr", 5: "May", 6: "June", 7: "Jul", 8: "Aug", 9: "Sep", 10: "Oct", 11: "Nov", 12: "Dec" };
    $scope.typeNames = { 1: "Rent", 2: "Food Out", 3: "Food Store", 4: "Transport", 5: "Comunication", 6: "Medical", 7:"Household", 8: "Travel", 9: "Entert", 10: "Other" };
    
    var tmp = JSON.parse(types);
    $scope.types = tmp[0];
    $scope.years = tmp[1];
    $scope.selectedYear = requestGet['year'];
    
    $scope.recordUpdate = function(){
        
        var url = '';
        for( n in requestGet){
            url += n +"="+requestGet[n] +'&';
        }
      
        $http.get("money/get?"+ url).then(function (response) {

            var records = response.data;
            
            $scope.total = 0;
            $scope.reverse = false;
            $scope.propertyName = 'act_date';
            $scope.food_out = 0;
            $scope.food_in = 0;
            $scope.transport = 0;
            
            $scope.sortBy = function(propertyName) {
                if($scope.propertyName === propertyName) {
                    $scope.reverse = !$scope.reverse;
                }
                $scope.propertyName = propertyName;
    
                var tmp1 = orderBy(records.data, $scope.propertyName, $scope.reverse);
                tmp1.map(function(value, index){
                    var tmp = value.act_date.split('-');
                    value.act_date_display = tmp[1] +"/"+ tmp[2] +"/"+ tmp[0];
                    $scope.total += parseInt(value.amount);
                    if(value.type == "Food Out") $scope.food_out += parseInt(value.amount);
                    else if(value.type == "Transport") $scope.transport += parseInt(value.amount);
                    else if(value.type == "Food Store") $scope.food_in += parseInt(value.amount);
                });
                $scope.records = tmp1;
            }
            $scope.sortBy($scope.propertyName);
        
        });
    }
    $scope.recordUpdate();
    
    $scope.active_element = {};
    $scope.recordEdit = function(el) {
        $scope.active_element = el;
   
        if( typeof el == 'string' && el == 'add_record' ){
            $scope.active_element = {};
            $scope.active_element.id = '0';
            $scope.modal_title = 'New record';
            
        } else $scope.modal_title = 'Edit record';

        $('#edit-modal').modal();
    };
    
    $('#date-data').on("change", function() {
       $scope.active_element.act_date = $(this).val();
    });
    
    $scope.recordSave = function() {
        var data = {
            act_date: dateToDB($scope.active_element.act_date_display),
            type:     $scope.active_element.type,
            amount:   $scope.active_element.amount,
            source:  $scope.active_element.source,
            comment:  $scope.active_element.comment,
            recordId: $scope.active_element.id,
            _token: token
        }

        $http.post(url, data).then(function(msg){
        
            if(msg.data['redirect'] == 'reload') $scope.recordUpdate();
            else $('#edit-modal').modal('hide');
        });
    };
    
    $scope.selectedType = '';
    $scope.filterByType = function(){
        requestGet['type'] = $scope.selectedType;
        $scope.recordUpdate();
    }

    $scope.selectedYear = '';
    $scope.filterByYear = function(){
        requestGet['year'] = $scope.selectedYear;
        $scope.recordUpdate();
    }

    
    $scope.selectedMonth = [];
    $scope.filterByMonth = function(month) {
        
        if ($scope.selectedMonth.indexOf(month) === -1) {
            $scope.selectedMonth.push(month);
        } else {
            $scope.selectedMonth.splice($scope.selectedMonth.indexOf(month), 1);
        }

        if( Object.keys($scope.selectedMonth).length > 0 )
            requestGet['month'] = $scope.selectedMonth.join("|");
        else delete(requestGet['month']);

        $scope.recordUpdate();
    }
}]);

function dateToDB(date1){
    var date = date1.split('/');
    return date[2] +'-'+ date[0] +'-' + date[1]
} 
