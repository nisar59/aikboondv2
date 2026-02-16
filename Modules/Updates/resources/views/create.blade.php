@extends('layouts.template')
@section('title')
Updates
@endsection
@section('content')
<section class="section">
  <div class="section-body">
    <form action="{{url('/updates/store')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-primary">
            <div class="card-header bg-white">
              <h4>Updates</h4>
            </div>
            <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Name">
              </div>
            </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="">Time</label>
                      <input type="text" class="form-control" name="time" placeholder="Enter Time">
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="">Image</label>
                      <input type="file" class="form-control" name="image">
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
