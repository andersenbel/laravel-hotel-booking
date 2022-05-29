@extends('layout')

@section('title')Rooms list @endsection

@section('main_content')

<section class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="py-2 text-white">Look for free rooms:</h2>
        </div>
    </div>
    <form class="row" method="GET" action="/search">
      <div class="col-2">
        <div class="input-group date" id="datepicker1">
          <input autocomplete="off" type="text" class="form-control" id="dfrom" placeholder="{{$dfrom}}" name="dfrom" value="{{old('dfrom')}}"/>
          <span class="input-group-append">
            <span class="input-group-text bg-light d-block" placeholder="to">
              <i class="fa fa-calendar"></i>
            </span>
          </span>
        </div>
      </div>
      <div class="col-2">
        <div class="input-group date" id="datepicker2">
          <input autocomplete="off" type="text" class="form-control" id="dto"  placeholder="{{$dto}}" name="dto"  value="{{old('dto')}}"/>
          <span class="input-group-append">
            <span class="input-group-text bg-light d-block">
              <i class="fa fa-calendar"></i>
            </span>
          </span>
        </div>
      </div>
      <div class="col-2">
          <button class="btn btn-md btn-warning text-white" type="submit">Search</button>
      </div>
    </form>
    <div class="row mt-3">
        <div class="col-12">
            @if ($dfrom != 'From' && $dto != 'To')
            <div class="py-2 text-warning">Search range: from <b>{{$dfrom}}</b> --  to <b>{{$dto}}</b></div>
            @elseif($dfrom == 'From' && $dto != 'To')
            <div class="py-2 text-warning">Search range: to <b>{{$dto}}</b></div>
            @elseif($dfrom != 'From' && $dto == 'To')
            <div class="py-2 text-warning">Search range: from <b>{{$dfrom}}</b></div>
            @endif
        </div>
    </div>
  </section>

<div class="album py-3">
    <div class="container">
        <div class="row">                  
            <div class="col-12">
                {{$roomslist->withQueryString()->links('vendor/pagination/bootstrap-5')}}    
            </div>
        </div>        
        <div class="row">
            @foreach($roomslist as $room)            
            <div class="col-md-4">
                <div class="card mb-4 box-shadow position-relative">
                    {{-- @if (

                            isset($room['bookings'][0]['dfrom']) &&
                            isset($room['bookings'][0]['dto']) )
                        {{$room['bookings'][0]['dfrom']}} -- {{$room['bookings'][0]['dto']}}
                    @endif --}}
                    <?php $flag=false;?>
                    @foreach ($room['bookings'] as $b)
                        @if (
                            isset($b['dfrom']) &&
                            isset($b['dto']) &&
                            \Carbon\Carbon::now()->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['dfrom'])->timestamp >=0 &&
                            \Carbon\Carbon::now()->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['dto'])->timestamp <=0
                            )
                               <?php $flag=true;?>
                        @endif
                    @endforeach
                    @if ($flag)
                        <div class="badge bg-md bg-danger position-absolute top-0 end-0 p-2">Booked!</div>
                    @else 
                        <div class="badge bg-md bg-success position-absolute top-0 end-0 p-2">Available</div>
                    @endif

                    <a href="/room/{{$room['id']}}" style="max-height:225px;overflow:hidden;background:url({{$room['photo']}}) center center;height: 225px; width: 100%; display: block;background-size:cover;">
                    </a>
                    <div class="card-body">
                        <p class="card-text"><a href="/room/{{$room['id']}}" class="text-dark text-decoration-none">{{$room['name']}}</a></p>
                        <h2 class="card-title pricing-card-title text-end text-success ">${{$room['price']}} <small class="text-muted text-secondary">/ day</small></h2>
                        <div class="d-flex justify-content-left align-items-center">
                            <div class="btn-group">
                                <a type="button" class="btn btn-md btn-secondary text-white" href="/room/{{$room['id']}}">Details</a>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach            
        </div>
        <div class="row">                  
            <div class="col-12">
                {{$roomslist->withQueryString()->links('vendor/pagination/bootstrap-5')}}    
            </div>
        </div>
    </div>
</div>
@endsection