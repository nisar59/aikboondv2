@extends('layouts.template')
@section('title')
Cities
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
            <div class="collapse multi-collapse" id="multiCollapseExample2">
              <div class="p-3 accordion-body">
                <div class="row">
                  <div class="col-md-6 form-group">
                    <label for="">State Name</label>
                    <select name="state_id" class="form-control filters">
                     <option value="">Select State</option>
                     @foreach(AllStates(167) as $state)
                     <option value="{{$state->id}}">{{$state->name}}</option>
                     @endforeach
                    </select>
                  </div>
                  <div class="col-md-6 form-group">
                    <label for="">City Name</label>
                    <input type="text" class="form-control filters" name="name" placeholder="City Name">
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
            <h4 class="col-md-6">Cities</h4>
           <div class="col-md-6 text-right">
              <a href="{{url('/cities/create')}}" class="btn btn-success">+</a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-striped table-hover" id="cities" style="width:100%;">
                <thead>
                  <tr>
                    <th>Country Name</th>
                    <th>State Name</th>
                    <th>Name</th>
                    <th>Action</th>
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
  data_table = $('#cities').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url:"{{url('/cities')}}",
        data:data,
        },
      buttons:[],
      columns: [
            {data: 'country_id', name: 'country_id',class:"text-center"},
            {data: 'state_id', name: 'state_id',class:"text-center"},
            {data: 'name', name: 'name',class:"text-center"},
            {data: 'action', name: 'action', orderable: false, class:"d-flex justify-content-center w-auto", searchable: false},
      ]
  });
}

DataTableInit();


$(document).on('change', '.filters', function () {
var data={};
$('.filters').each(function() {
data[$(this).attr('name')]=$(this).val();
});
data_table.destroy();
DataTableInit(data);
});


});
</script>
@endsection