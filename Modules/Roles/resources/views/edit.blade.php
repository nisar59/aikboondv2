@extends('layouts.template')
@section('title')
Roles & Permissions
@endsection
@section('content')
        <section class="section">
          <div class="section-body">
            <form action="{{url('/roles/update/'.$data['role']->id)}}" method="post">
              @csrf  
            <div class="row">  
              <div class="col-12 col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Edit Roles & Permissions</h4>
                  </div>
                  <div class="card-body">
                    <div class="form-group">
                      <label>Role</label>
                      <input type="text" class="form-control" value="{{$data['role']->name}}" name="role" placeholder="Role">
                    </div>
                    <div class="form-group row">
                      @foreach(AllPermissions() as $name=> $permissions)
                      <div class="col-4">
                      <label class="d-block text-capitalize">{{$name}}</label>
                        @foreach($permissions as $permission)
                        @php
                        $val=$name.'.'.$permission;
                        @endphp
                        
                      <div class="form-check">
                        <input class="form-check-input" name="permissions[]" value="{{$val}}" type="checkbox" id="defaultCheck{{$val}}"
                        @foreach($data['permission'] as $perm)
                        @if($perm->name==$val && in_array($perm->id, $data['rolepermissions']))
                        checked
                        @endif
                        @endforeach
                        >
                        <label class="form-check-label text-capitalize" for="defaultCheck{{$val}}">
                          {{$permission}}
                        </label>
                      </div>
                      @endforeach
                    </div>
                      @endforeach
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

@endsection
