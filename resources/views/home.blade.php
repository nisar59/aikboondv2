@extends('layouts.template')
@section('title')
Dashboard
@endsection
@section('content')

<section class="section">
  <div class="section-body">
    <div class="row ">
      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="card" style="height: 130px;">
          <div class="card-statistic-4">
            <div class="align-items-center justify-content-between">
              <div class="row ">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3 m-auto">
                  <div class="card-content">
                    <h5 class="font-15">Donors Registered</h5>
                    <h2 class="mb-3 font-18">{{number_format($donors)}}</h2>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0 m-auto">
                  <div class="banner-img">
                    <img src="{{asset('/icons/homepageaboutus.svg')}}" alt="" class="w-50">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="card" style="height: 130px;">
          <div class="card-statistic-4">
            <div class="align-items-center justify-content-between">
              <div class="row ">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3 m-auto">
                  <div class="card-content">
                    <h5 class="font-15">Served Cases</h5>
                    <h2 class="mb-3 font-18">comming soon</h2>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0 m-auto">
                  <div class="banner-img">
                    <img src="{{asset('/icons/homepageblood-bag.svg')}}" alt=""  class="w-50">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-6 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Statistics</h4>
        </div>
        <div class="card-body">
          <h3 class="text-center text-uppercase">comming soon</h3>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('js')
<script>
$(document).ready(function() {
var ctx = document.getElementById("statistics").getContext('2d');
var myChart = new Chart(ctx, {
type: 'pie',
data: {
labels: [
'Donors Registered',
'Served Cases',
'Compensation',
],
},
options: {
responsive: true,
legend: {
position: 'bottom',
},
}
});
});
</script>
@endsection