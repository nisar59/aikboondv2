<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.head')
<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      @include('layouts.header')
      @include('layouts.sidebar')
      <div class="main-content">
      @yield('content')
      @php
      $set=Settings();
      @endphp
        <div class="settingSidebar">
          <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
          </a>
          <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
              <div class="setting-panel-header">Setting Panel
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Select Layout</h6>
                <div class="selectgroup layout-color w-50">
                  <label class="selectgroup-item">
                    <input type="radio" name="value" @if($set->layout==1) checked @endif value="1" class="selectgroup-input-radio select-layout">
                    <span class="selectgroup-button">Light</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="value" @if($set->layout==2) checked @endif value="2" class="selectgroup-input-radio select-layout">
                    <span class="selectgroup-button">Dark</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Sidebar Color</h6>
                <div class="selectgroup selectgroup-pills sidebar-color">
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" @if($set->sidebar_color==1) checked @endif value="1" class="selectgroup-input select-sidebar">
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" @if($set->sidebar_color==2) checked @endif value="2" class="selectgroup-input select-sidebar">
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Color Theme</h6>
                <div class="theme-setting-options">
                  <ul class="choose-theme list-unstyled mb-0">
                    <li title="white" @if($set->color_theme=='white') class="active" @endif>
                      <div class="white"></div>
                    </li>
                    <li title="cyan" @if($set->color_theme=='cyan') class="active" @endif>
                      <div class="cyan"></div>
                    </li>
                    <li title="black" @if($set->color_theme=='black') class="active" @endif>
                      <div class="black"></div>
                    </li>
                    <li title="purple" @if($set->color_theme=='purple') class="active" @endif>
                      <div class="purple"></div>
                    </li>
                    <li title="orange" @if($set->color_theme=='orange') class="active" @endif>
                      <div class="orange"></div>
                    </li>
                    <li title="green" @if($set->color_theme=='green') class="active" @endif>
                      <div class="green"></div>
                    </li>
                    <li title="red" @if($set->color_theme=='red') class="active" @endif>
                      <div class="red"></div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" {{$set->mini_sidebar}} name="custom-switch-checkbox" class="custom-switch-input"
                      id="mini_sidebar_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Mini Sidebar</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" {{$set->sticky_header}} name="custom-switch-checkbox" class="custom-switch-input"
                      id="sticky_header_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Sticky Header</span>
                  </label>
                </div>
              </div>
              <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                  <i class="fas fa-undo"></i> Restore Default
                </a>
              </div>
            </div>
          </div>
      </div>
      </div>
      @include('layouts.footer')
    </div>
  </div>
    @yield('mdl')
    @include('layouts.footer-js')

<script type="text/javascript">
@if (count($errors) > 0)
      @foreach ($errors->all() as $error)
            error("{{$error}}", 'Input error');
      @endforeach
@elseif (Session::has('warning'))
      warning("{{ Session::get('warning') }}");
@elseif (Session::has('success'))
      success("{{ Session::get('success') }}");
@elseif (Session::has('error'))
      error("{{ Session::get('error') }}");
@elseif (Session::has('info'))
      info(`{{ Session::get('info') }}`);
@elseif (isset($warning))
      warning("{{ $warning }}");
@elseif (isset($success))
      success("{{ $success }}");
@elseif (isset($error))
      error("{{ $error }}");
@elseif (isset($info))
      info("{{ $info }}");
@else
@endif
</script>

  @yield('js')


</body>
</html>
