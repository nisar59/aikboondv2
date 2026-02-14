@extends('layouts.template')
@section('title')
Cities
@endsection
@section('content')
<section class="section">
  <div class="section-body">
    <form action="{{url('/cities/update/'.$city->id)}}" method="post">
      @csrf
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-primary">
            <div class="card-header bg-white">
              <h4>Cities</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">State</label>
                    <select name="state_id" class="form-control">
                       @foreach(AllStates(167) as $state)
                        <option value="{{$state->id}}" @if($state->id==$city->state_id) selected @endif>{{$state->name}}</option>
                       @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" value="{{$city->name}}" name="name" placeholder="Enter Name">
                  </div>
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
    
});
</script>
@endsection