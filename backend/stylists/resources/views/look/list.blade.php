@extends('layouts.master')

@section('title', 'Looks list')

@section('content')
<div id="contentCntr">
    {!! $looks->render() !!}
    <div class="clear"></div>

    <div class="section">
        <ol class="selectable" >
        @foreach($looks as $look)
            <li class="ui-state-default" look_id="{{$look->id}}">
                <div class="items">
                    <div class="name text"><a href="{{url('look/view/' . $look->id)}}">{{$look->look_name}}</a></div>
                    <div class="image"><img src="{!! asset('images/' . $look->look_image) !!}" /></div>
                    <div class="extra text">
                        <span>{{$look->bodytype}}</span>
                        <span>Rs.{{$look->budget}}</span>
                        <span>{{$look->age}}</span>
                        <span>{{$look->occasion}}</span>
                        <span>{{$look->gender}}</span>
                        <span>Rs.{{$look->lookprice}}</span>
                    </div>
                </div>
            </li>
        @endforeach
        </ol>
    </div>
    <div class="clear"></div>

    {!! $looks->render() !!}

    @include('look.create')

</div>

@endsection
