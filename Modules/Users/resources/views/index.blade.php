@extends('layouts.template')
@section('title')
Users
@endsection
@section('content')
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="col-md-6">Users</h4>
                    <div class="col-md-6 text-right">
                    <a href="{{url('/users/create')}}" class="btn btn-success">+</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-striped table-hover" id="users" style="width:100%;">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>State</th>
                            <th>City</th>
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
  var roles_table = $('#users').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{url('users')}}",
              buttons:[],
              columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'role', name: 'role'},
                {data: 'state_id', name: 'state_id'},
                {data: 'city_id', name: 'city_id'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
          });
      });
</script>
@endsection
