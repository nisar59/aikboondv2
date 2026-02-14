@extends('layouts.template')
@section('title')
Roles & Permissions
@endsection
@section('content')
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="col-md-6">Roles & Permissions</h4>
                    <div class="col-md-6 text-right">
                    <a href="{{url('/roles/create')}}" class="btn btn-success">+</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-striped table-hover" id="roles" style="width:100%;">
                        <thead>
                          <tr>
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
  var roles_table = $('#roles').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{url('roles')}}",
              buttons:[],
              columnDefs: [ {
                  "targets": 1,
                  "orderable": false,
                  "searchable": false
              } ]
          });
      });
</script>
@endsection
