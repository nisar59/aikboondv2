@extends('layouts.template')
@section('title')
Users
@endsection
@section('content')
        <section class="section">
          <div class="section-body">

            <form action="{{url('/users/update/'.$data['user']->id)}}" method="post" enctype="multipart/form-data">
              @csrf
            <div class="row">
              <div class="col-12 col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit Users</h4>
                  </div>
                  <div class="card-body">
                  <div class="row">
                    <input type="file"  hidden class="form-control" name="image" id="image" onchange="document.getElementById('image-display').src = window.URL.createObjectURL(this.files[0])">

                    @php
                    $image_name=$data['user']->image;
                    $image_path=public_path('img/users/'.$image_name);
                    if(file_exists($image_path) AND $image_name!=''){
                      $image_path=url('/img/users/'.$image_name);
                    }
                    else{
                      $image_path=url('/img/images.png');
                    }
                    @endphp

                    <label for="image" class="form-group col-md-12 text-center">
                      <img src="{{$image_path}}" class="image-display rounded-circle" id="image-display" width="100" height="100">
                    </label>
                  </div>


                    <div class="row">
                    <div class="form-group col-md-4">
                      <label>Name</label>
                      <input type="text" class="form-control" value="{{$data['user']->name}}" name="name" placeholder="Name">
                    </div>
                    <div class="form-group col-md-4">
                      <label>Email</label>
                      <input type="email" class="form-control" value="{{$data['user']->email}}" name="email" placeholder="Email">
                    </div>
                    <div class="form-group col-md-4">
                      <label>Phone No</label>
                      <input type="phone" class="form-control" value="{{$data['user']->phone}}" name="phone" placeholder="Phone No">
                    </div>


                  </div>
                  <div class="row">
                    <div class="form-group  @if(!$data['user']->hasRole('super-admin')) col-md-6 @else col-md-12 @endif">
                      <label>Password</label>
                      <input type="password" class="form-control" name="password" placeholder="Password">
                      <p class="text-muted">leave empty, if you don't want to update the password</p>
                    </div>
                    @if(!$data['user']->hasRole('super-admin'))
                    <div class="form-group col-md-6">
                      <label>Role</label>
                      <select class="form-control" name="role">
                        @foreach($data['role'] as $role)
                        <option value="{{$role->name}}" @if($data['user']->hasRole($role->name)) selected @endif>{{$role->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    @else
                    <input type="hidden" name="role" value="super-admin">
                    @endif
                  </div>

                  <div class="row">

                <div class="form-group col-md-6">
                  <input type="text" hidden name="country_id" value="167">
                  <label for="">States</label>
                  <select name="state_id" id="state-dropdown" class="form-control select2">
                    <option value="">-- Select State --</option>
                    @foreach($data['states'] as $state)
                    <option @if(old('state_id' , $data['user']->state_id)==$state->id) selected @endif value="{{$state->id}}">{{$state->name}}</option>
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

                <div class="form-group col-md-12">
                  <label>Address</label>
                  <input type="text" value="{{old('address', $data['user']->address)}}" class="form-control" name="address" placeholder="Enter Address">
                </div>

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
                    if(value.id=="{{old('city_id', $data['user']->city_id)}}"){
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
                    if(value.id=="{{old('ucouncil_id',$data['user']->ucouncil_id)}}"){
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
