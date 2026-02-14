@extends('layouts.template')
@section('title')
Cities
@endsection
@section('content')
<section class="section">
  <div class="section-body">
    <form action="{{url('/cities/store')}}" method="post">
      @csrf
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-primary">
            <div class="card-header bg-white">
              <h4>Cities</h4>
            </div>
            <div class="card-body">
          <div class="row">
            <input type="hidden" name="country_id" value="167">
            <div class="col-md-6">
              <div class="form-group">
                <label for="">States</label>
               <select class="form-control select2" name="state_id">
                <option value="">Select</option>
                 @foreach(AllStates(167) as $state)
                  <option value="{{$state->id}}">{{$state->name}}</option>
                 @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter City Name">
              </div>
            </div>
          </div>
        </div>
            <div class="card-footer text-right">
              <button class="btn btn-primary" type="submit">Submit</button>
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

});
</script>
@endsection