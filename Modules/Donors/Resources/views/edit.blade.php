@extends('layouts.template')
@section('title')
Blood Donor
@endsection
@section('content')
<section class="section">
  <div class="section-body">
    
    <form action="{{url('/donors/update', $donor->id)}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h4>Blood Donor</h4>
            </div>
            <div class="card-body pt-1">
              <div class="row">

<!--                 <div class="col-12 text-center">
                  <input type="file" name="image" hidden onchange="document.getElementById('image-preview').src = window.URL.createObjectURL(this.files[0])" id="image-input">
                 <label for="image-input"> <img width="120px" height="120px" id="image-preview" class="rounded-circle" src="{{asset('img/donors/'.$donor->image)}}" alt="">
                 </label>
                </div> -->

                <div class="form-group col-md-6">
                  <label>Name</label>
                  <input type="text" value="{{old('name', $donor->name)}}" class="form-control" name="name" placeholder="Enter Name">
                </div>

                <div class="form-group col-md-6">
                  <label>DOB</label>
                  @php
                    $dob=\Carbon\Carbon::parse($donor->dob)->format('Y-m-d');
                  @endphp
                  <input type="date" value="{{old('dob', $dob)}}" name="dob" class="form-control" placeholder="Enter dob">
                </div>

                <div class="form-group col-md-6">
                  <label>Phone No</label>
                  <input type="number" id="phone" value="{{old('phone', $donor->phone)}}" class="form-control" name="phone"  placeholder="Enter Phone No">
                </div>

                <div class="col-md-6">
                    <label>Verification Code</label>
                    <div class="input-group mb-2 mr-sm-2">
                      <input type="text" class="form-control" value="{{old('verification_code')}}" name="verification_code" placeholder="Verification Code">
                      <div class="input-group-append">
                        <a href="javascript:void(0)" id="get-code" class="btn btn-primary input-group-text">Get Code</a>
                      </div>
                    </div> 
                </div>

                <div class="form-group col-md-6">
                  <label>Blood Group</label>
                  <select name="blood_group" class="form-control">
                    <option value="">-- Select Blood Group --</option>
                    @foreach(BloodGroups() as $key=> $bg)
                      <option @if(old('blood_group', $donor->blood_group)==$key) selected @endif value="{{$key}}">{{$bg}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label>Last Donate Date</label>
                  @php
                    $last_donate_date=\Carbon\Carbon::parse($donor->last_donate_date)->format('Y-m-d');
                  @endphp
                  <input type="date" value="{{old('last_donate_date', $last_donate_date)}}" class="form-control" name="last_donate_date" placeholder="Enter Last Donate Date">
                </div>
                <input type="text" hidden name="country_id" value="167">
                <div class="form-group col-md-6">
                  <label for="">States</label>
                  <select name="state_id" id="state-dropdown" class="form-control select2">
                    <option value="">-- Select State --</option>
                    @foreach($states as $state)
                    <option @if(old('state_id' , $donor->state_id)==$state->id) selected @endif value="{{$state->id}}">{{$state->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">City</label>
                    <select id="city-dropdown" class="form-control select2" name="city_id">
                      <option value="">-- Select City --</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Union Councils Name</label>
                    <select id="union-dropdown" class="form-control select2" name="ucouncil_id">
                      <option value="">-- Select Council Name --</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label>Address</label>
                  <input type="text" value="{{old('address', $donor->address)}}" class="form-control" name="address" placeholder="Enter Address">
                </div>


              </div>
              <div class="card-footer text-right">
                <button class="btn btn-primary mr-1" type="submit">Submit</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
  @endsection
  @section('js')
  <script type="text/javascript">
  $(document).ready(function() {
    setTimeout(function() {
        $("#state-dropdown").trigger('change');
    }, 500);
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
                    if(value.id=="{{old('city_id', $donor->city_id)}}"){
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
        $('#union-dropdown').html('<option value="">--  Select Council Name --</option>');
    });
    /*-----------------area union-council-----------*/
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
                $('#union-dropdown').html('<option value=""> Select Council Name</option>');
                $.each(result.unioncouncil, function(key, value) {
                    var selected='';
                    if(value.id=="{{old('ucouncil_id',$donor->ucouncil_id)}}"){
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
    /*Address*/
    /*$(document).on('change','#union-dropdown', function() {
        var area_id = this.value;
        $("#address-dropdown").html('');
        $.ajax({
            url: "{{url('address')}}",
            type: "POST",
            data: {
                area_id: area_id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#address-dropdown').html('<option value="">Select Address</option>');

                $.each(result.address, function(key, value) {                
                    var selected='';
                    if(value.id=="{{old('town_id', $donor->town_id)}}"){
                        selected='selected';
                    }
                    $("#address-dropdown").append('<option '+selected+' value="' + value.id + '">' + value.name + '</option>');
                });
            },
            error: function(err) {
                error(err.statusText);
            }
        });
    });*/




    $(document).on('click', '#get-code', function() {
        var phone = $("#phone").val();
        var domObj=$(this);
        domObj.html(`<div class="spinner-border" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>`);
        domObj.prop('disabled', true);

        $.ajax({
            url: "{{url('send-code')}}",
            type: "POST",
            data: {
                phone: phone,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                if(result.success){
                    success(result.message)
                    countdown(1, 00);
                }else{
                    error(result.message);
                    domObj.html('Get Code');
                    domObj.prop('disabled', false);

                }
            },
            error: function(err) {
                error(err.statusText);
            }
        });
    });



var timeoutHandle;
function countdown(minutes, seconds) {
    function tick() {
        var counter = document.getElementById("get-code");
        counter.innerHTML =
            minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds);
        seconds--;
        if (seconds >= 0) {
            timeoutHandle = setTimeout(tick, 1000);
        } else {
            if (minutes >= 1) {
                // countdown(mins-1);   never reach “00″ issue solved:Contributed by Victor Streithorst
                setTimeout(function () {
                    countdown(minutes - 1, 59);
                }, 1000);
            }else{
              counter.innerHTML='Get Code';
              $("#get-code").prop('disabled', false);  
            }
        }
    }
    tick();
}


  });
  </script>
  @endsection