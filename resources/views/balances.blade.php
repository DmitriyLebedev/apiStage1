@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Expensies report.</div>

                <div class="panel-body">
                    <table style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width: 15%">Date</th>
                                <th style="width: 10%">Balance</th>
                                <th style="width: 30%">Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                    @php $dt = 0 @endphp
                    @if(count($records) > 0)
                         @foreach($records as $record)
                            @if($dt != date('y', strtotime($record->date)))
                                @php $dt = date('y', strtotime($record->date)) @endphp
                            <tr>
                                <td colspan="6"><hr /></td>                            
                            </tr>       
                            @endif
                            
                            <tr id="record_{{$record->id}}">
                                <td>{{date('m/d/Y', strtotime($record->date))}}</td>                            
                                <td>{{$record->balance}}</td>
                                <td>{{$record->spent}}</td>
                            </tr>

                        @endforeach
                    @endif
                    
                        </tbody>
                    </table>                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
