@extends('layout')

@section('title'){{$room[0]['name']}} @endsection

@section('main_content')

<section class="container  bg-light">
    <div class="row featurette py-3">
        <div class="col-md-7 order-md-2">
          <h2 class="featurette-heading">{{$room[0]['name']}}</span></h2>
          <hr />
          <div class="lead">Facilities: <span class="text-warning fw-bold">{!! $room[0]['facilities'] !!}</span></div>
          <div class="lead">Rooms size: <span class="text-warning fw-bold">{!! $room[0]['rooms_size'] !!}</span></div>
          <hr />
          <div class="card-title pricing-card-title text-end text-success h1"><span class="text-success text-lg fw-bold">${{$room[0]['price']}}</div>
          <hr />
          <p class="lead">{{$room[0]['description']}}</p>
        </div>
        <div class="col-md-5 order-md-1">
          <img class="featurette-image img-fluid mx-auto" data-src="holder.js/500x500/auto" alt="500x500" src="{{$room[0]['photo']}}" data-holder-rendered="true" style="width: 500px; height: auto;">
        </div>
      </div>
</section>
<section class="container py-5 bg-light" id="bookingform">
    <div class="row pt-3">
        <div class="col-12 text-center">
            <h2 class="text-success">Book In!</h2>
        </div>
    </div>
    @if ($errors->any())
    <div class="row py-3">
        <div class="col-12">
            <div class="alert alert-danger pt-3 pb-1  mt-3 mp-2">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <form  method="POST" class="bg-success pb-3" action="/bookin">
    @csrf
    <input type="hidden" name="room_id" value="{{$room[0]['id']}}"/>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-6 py-2"><input  type="text" class="form-control"  placeholder="Name" name="name"  id="name" value="{{old('name')}}"/></div>
    </div>    
    <div class="row py-3">
        <div class="col-3"></div>
        <div class="col-2">
            <div class="input-group date" id="datepicker1">
                <input autocomplete="off" type="text" class="form-control" id="date" placeholder="From" name="dfrom" id="dfrom" value="{{old('dfrom')}}"/>
                <span class="input-group-append">
                <span class="input-group-text bg-light d-block" placeholder="to">
                    <i class="fa fa-calendar"></i>
                </span>
                </span>
            </div>
        </div>
        <div class="col-2">
            <div class="input-group date" id="datepicker2">
                <input autocomplete="off" type="text" class="form-control" id="date"  placeholder="To" name="dto" id="dto"  value="{{old('dto')}}"/>
                <span class="input-group-append">
                <span class="input-group-text bg-light d-block">
                    <i class="fa fa-calendar"></i>
                </span>
                </span>
            </div>
        </div>
        <div class="col-2">
            <select  class="form-control" placeholder="Guests" name="guests"  id="guests">
                <option>Guests count</option>
                @for ($i = 1; $i <= 15; $i++)
                    <option value="{{$i}}">{{$i}}</option>
                    @if(old('guests')==$i)
                    <option value="{{$i}}" selected>{{$i}}</option>
                @else
                    <option value="{{$i}}">{{$i}}</option>
                @endif  
                @endfor
            </select>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-1 py-2 text-end">Code:</div>
        <div class="col-1">
            <select class="form-control" placeholder="Code" name="phone_code" id="phone_code">
                <option>Select</option>
                @foreach ($phone_codes as $one)
                    @if(old('phone_code')==$one)
                        <option value="{{$one}}" selected>{{$one}}</option>
                    @else
                        <option value="{{$one}}">{{$one}}</option>
                    @endif                        
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <input type="text" class="form-control"  placeholder="Phone number" name="phone_number" id="phone_number" value="{{old("phone_number")}}"/>
        </div>        
    </div>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-6 py-2"><input type="text" class="form-control"  placeholder="Email" name="email"  id="email" value="{{old("email")}}"/></div>
    </div>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-2">
            <button class="btn btn-md btn-warning text-dark" type="submit">BookIn</button>
        </div>
    </div>          
  </form>
</section>
@endsection