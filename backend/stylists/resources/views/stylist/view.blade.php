@extends('layouts.master')

@section('title', ($stylist->name ? $stylist->name : "Stylist not found"))

@section('content')
<div id="contentCntr">
    <div class="container">
        <ol class="selectable">
            <li class="ui-state-default" id="{{$stylist->stylish_id}}">
                <div class="resource_view">
                    <div class="image">
                        <img src="{!! strpos($stylist->image, "stylish") === 0 ? asset('images/' . $stylist->image) : $stylist->image !!}"/>
                    </div>
                    <table class="info">
                        <tr class="row">
                            <td class="title" colspan="2">
                                {{$stylist->name}}
                                @if($is_owner_or_admin)
                                    <a style="color:blue;font-size:13px;" href="{{url('stylist/edit/' . $stylist->stylish_id)}}" title="{{$stylist->name}}" >Edit</a>
                                @endif
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="description" colspan="2">{{$stylist->description}}</td>
                        </tr>
                        @if($is_owner_or_admin)
                            <tr class="row">
                                <td class="head">Email</td><td class="content">{{$stylist->email}} </td>
                            </tr>
                        @endif
                        <tr class="row">
                            <td class="head">Status</td><td class="content">{{$status_list[$stylist->status_id]->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Expertise</td><td class="content">{{$stylist->expertise->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Age</td><td class="content">{{$stylist->age}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Gender</td><td class="content">{{$stylist->gender->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Designation</td><td class="content">{{$stylist->designation->name}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Blog URL</td><td class="content">{{$stylist->blog_url}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Facebook Id</td><td class="content">{{$stylist->facebook_id}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Twitter Id</td><td class="content">{{$stylist->twitter_id}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Pinterest Id</td><td class="content">{{$stylist->pinterest_id}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Instagram Id</td><td class="content">{{$stylist->instagram_id}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Code</td><td class="content">{{$stylist->code}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Profile</td><td class="content">{{$stylist->profile}} </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Looks created</td>
                            <td class="content">
                                @if(count($looks))
                                    @foreach($looks as $look)
                                        <a href="{{url('look/view/' . $look->id)}}" title="{{$look->name}}" target="look_win">
                                            <img class="entity" src="{{strpos($look->image, "http") !== false ? $look->image : asset('images/' . $look->image)}}"/>
                                        </a>
                                    @endforeach
                                    <br />
                                    <a class="product_link" href="{{url('look/list?stylish_id=' . $stylist->stylish_id)}}">View all</a>
                                    <a class="product_link" href="{{url('look/list?stylish_id=' . $stylist->stylish_id.'&status_id=1')}}">Active</a>
                                    <a class="product_link" href="{{url('look/list?stylish_id=' . $stylist->stylish_id.'&status_id=6')}}">Approved</a>
                                    <a class="product_link" href="{{url('look/list?stylish_id=' . $stylist->stylish_id.'&status_id=5')}}">Submitted</a>
                                    <a class="product_link" href="{{url('look/list?stylish_id=' . $stylist->stylish_id.'&status_id=4')}}">In Progress</a>
                                    <a class="product_link" href="{{url('look/list?stylish_id=' . $stylist->stylish_id.'&status_id=7')}}">Rejected</a>
                                @else
                                    None
                                @endif
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Profile images</td>
                            <td class="content">
                                @if(count($profile_images))
                                    @foreach($profile_images as $image)
                                        <img class="entity" src="{{strpos($image, "http") !== false ? $image : asset('images/' . $image)}}"/>
                                    @endforeach
                                @else
                                    None
                                @endif
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="head">Clients</td>
                            <td class="content">
                                <a class="product_link" href="{{url('client/list?stylish_id=' . $stylist->stylish_id)}}">View All</a>
                            </td>
                        </tr>
                    </table>

                </div>
            </li>
        </ol>
    </div>


    @include('look.create')

</div>

@endsection
