@extends('layouts.template')
@section('title')
Your Requests
@endsection

@section('css')
  <style type="text/css">
      @media (max-width: 1024px) {
          body{
          zoom: 0.6;
        }

        .main-content {
                padding-left: 8px;
                padding-right: 8px;
                width: 100% !important;
            }

        
      }
  </style>
@endsection

@section('content')
<section class="section">
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary" id="filters-container">
          <div class="card-header bg-white" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">
            <h4><i class="fas fa-filter"></i> Filters</h4>
          </div>
          <div class="card-body p-0">
            <div class="collapse multi-collapse" id="multiCollapseExample2" data-bs-parent="#filters-container">
              <div class="p-3 accordion-body">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label for="">State</label>
                      <select name="state_id" id="state-dropdown" class="form-control filters">
                        <option value="">-- Select State --</option>
                        @foreach($states as $state)
                        <option @if(old('state_id')==$state->id) selected @endif value="{{$state->id}}">{{$state->name}}</option>
                        @endforeach
                      </select>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="">City</label>
                      <select id="city-dropdown" class="form-control filters" name="city_id">
                        <option value="">-- Select City --</option>
                      </select>
                  </div>

                  <div class="col-md-6 form-group">
                    <label for="">Council Name</label>
                    <select id="union-dropdown" class="form-control filters" name="area_id">
                      <option value="">-- Select Council Name --</option>
                    </select>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="">Blood Group</label>
                    <select class="form-control filters" name="blood_group">
                      <option value="">-- Select Blood Group --</option>
                      @foreach(BloodGroups() as $key => $bg)
                      <option value="{{$key}}">{{$bg}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-12 form-group text-right">
                    <button id="search" class="btn btn-primary btn-block">Search</button>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="col-md-6">Your Requests</h4>
            <div class="col-md-6 text-right">

            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-striped table-hover table-sm" id="donors" style="width:100%;">
                <thead>
                  <tr>
                    <th>User Name</th>                    
                    <th>User Phone No</th>                    
                    <th>Request Date</th>                    
                    <th>Blood Group</th>                    
                    <th>State Name</th>
                    <th>City Name</th>
                    <th>Union Council Name</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('js')
<script type="text/javascript">
//Roles table
$(document).ready( function(){
  var data_table;
  function DataTableInit(data={}) {
  data_table = $('#donors').DataTable({
      bLengthChange: false,
      bFilter: false,
      bInfo: false,
      processing: true,
      serverSide: true,
      ajax: {
        url:"{{url('/requests')}}",
        data:data,
      },
      buttons:[],
      buttons:[],
              columns: [    
                {data: 'user_name', name: 'user_name'},
                {data: 'phone_no', name: 'phone_no'},
                {data: 'created_at', name: 'created_at'},
                {data: 'blood_group', name: 'blood_group'},
                {data: 'state_id', name: 'state_id'},
                {data: 'city_id', name: 'city_id'},
                {data: 'ucouncil_id', name: 'ucouncil_id'},
                {data: 'status', name: 'status'},
            ]
  });
}

DataTableInit();


$(document).on('click', '#search', function () {
var data={};
$('.filters').each(function() {
data[$(this).attr('name')]=$(this).val();
});
if(data_table){
  data_table.destroy();
}
DataTableInit(data);
});


});
</script>




  <script type="text/javascript">
  $(document).ready(function() {
    setTimeout(function() {
        $("#state-dropdown").trigger('change');
    }, 500);
    // $(document).on('change','#country-dropdown', function() {
    //     var idCountry = this.value;
    //     $("#state-dropdown").html('');
    //     $.ajax({
    //         url: "{{url('states')}}",
    //         type: "POST",
    //         data: {
    //             country_id: idCountry,
    //             _token: '{{csrf_token()}}'
    //         },
    //         dataType: 'json',
    //         success: function(result) {
    //             console.log(result);
    //             $('#state-dropdown').html('<option value="">-- Select State --</option>');
    //             $.each(result.states, function(key, value) {
    //                 var selected=("{{old('state_id')}}"==value.id) ? 'selected' : '';

    //                 $("#state-dropdown").append('<option '+selected+' value="' + value
    //                     .id + '">' + value.name + '</option>');
    //             });
    //             $('#city-dropdown').html('<option value="">-- Select City --</option>');
    //         },
    //         error: function(err) {
    //             error(err.statusText);
    //         }
    //     });
    // });
    /*------------------city listing----------------*/
    $(document).on('change','#state-dropdown', function() {
        var idState = this.value;
        $("#city-dropdown").html('');
        $.ajax({
            url: "{{url('cities')}}",
            type: "POST",
            data: {
                state_id: idState,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(res) {
                $('#city-dropdown').html('<option value="">-- Select City --</option>');
                $.each(res.cities, function(key, value) {
                    var selected='';
                    if(value.id=="{{old('city_id')}}"){
                        selected='selected';
                    }
                    $("#city-dropdown").append('<option '+selected+' value="' + value
                        .id + '">' + value.name + '</option>');
                });
                setTimeout(function () {
                 $("#city-dropdown").trigger('change');
                }, 500)
            },
            error: function(err) {
                error(err.statusText);
            }
        });
        $('#union-dropdown').html('<option value="">-- Select Area --</option>');
    });
    /*-----------------area listing-----------*/
 
  
    /*-----------------area listing-----------*/
    $(document).on('change','#city-dropdown', function() {
    var city_id = this.value;
    $("#union-dropdown").html('');
    $.ajax({
    url: "{{url('union-council')}}",
    type: "POST",
    data: {
    city_id: city_id,
    _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function(result) {
    $('#union-dropdown').html('<option value="">Select Council Name</option>');
    $.each(result.unioncouncil, function(key, value) {
    var selected='';
    if(value.id=="{{old('city_id')}}"){
    selected='selected';
    }
    $("#union-dropdown").append('<option '+selected+' value="' + value.id + '">' + value.name + '</option>');
    });
    setTimeout(function () {
    $("#union-dropdown").trigger('change');
    },500)
    },
    error: function(err) {
    error(err.statusText);
    }
    });
   
    });



  });
  </script>



@endsection
