@extends('layouts.template')
@section('title')
Compensation
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
                  
                  <div class="col-md-4 form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control filters" name="name" placeholder="Name">
                  </div>
                  <div class="col-md-3 form-group">
                    <label for="">Date</label>
                    <input type="date" value="{{now()->format('Y-m-d')}}" class="form-control filters" name="last_donate_date" placeholder="Date Time">
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
            <h4 class="col-md-6">Compensation</h4>
            <div class="col-md-6 text-right">
            <a href="javascript:void(0)" data-toggle="modal" data-target="#calculate-compensation" class="btn btn-success">Calculate</a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-striped table-hover" id="users" style="width:100%;">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>State</th>
                    <th>City</th>                            
                    <th>Union Council Name</th>
                    <th>Compensation</th>
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


@section('mdl')
<!-- Modal -->
<div class="modal fade" id="calculate-compensation" tabindex="-1" role="dialog" aria-labelledby="CalculateCompensationCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="{{url('/compensation/update')}}" method="post" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="CalculateCompensationCenterTitle">Calulate Compensation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
         <div class="col-12 form-group">
           <label for="">Month</label>
           <input name="month" value="{{now()->format('Y-m')}}" type="month" class="form-control">
         </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>



<div class="modal fade" id="pay-compensation" tabindex="-1" role="dialog" aria-labelledby="PayCompensationCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="" method="post" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="PayCompensationCenterTitle">Pay compensation to <span class="small" id="user-name"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="row">
         <div class="col-12 form-group">
           <label for="">Attachment (Paid Receipt)</label>
           <input name="paid_attachment" type="file" class="form-control">
         </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
</div>


@endsection

@section('js')
<script type="text/javascript">
    //Roles table
$(document).ready( function(){
  var roles_table = $('#users').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{url('compensation')}}",
              buttons:[],
              columns: [
                {data: 'name', name: 'name'},
                {data: 'phone', name: 'phone'},
                {data: 'role', name: 'role'},
                {data: 'state_id', name: 'state_id'},
                {data: 'city_id', name: 'city_id'},
                {data: 'ucouncil_id', name: 'ucouncil_id'},
                {data: 'compensation', name: 'compensation', orderable: false, searchable: false},
                {data: 'status', name: 'status', orderable: false, searchable: false},
            ]
  });


  $(document).on('click', '.pay', function () {
    var url=$(this).data('href');
    var name=$(this).data('name');
    $("#pay-compensation .modal-content").attr('action', url);
    $("#pay-compensation .modal-content #user-name").text(name);
    $("#pay-compensation").modal('show');
  });
});
</script>
@endsection
