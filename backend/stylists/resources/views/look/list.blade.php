@extends('layouts.master')

@section('title', 'Looks list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="filters">

                <form method="get" action="">
                    @include('stylist.select')
                    @include('common.occasion.select')
                    @include('common.status.select')
                    @include('common.gender.select')
                    @include('common.body_type.select')
                    @include('common.budget.select')
                    @include('common.age_group.select')
                    @include('common.search')
                    @include('common.daterange')
                    @include('common.pricerange')
                    <input type="submit" name="filter" value="Filter"/>
                    <a href="{{url('look/list')}}" class="clearall">Clear All</a>
                </form>
                {!! $looks->render() !!}
            </div>

            <div class="clear"></div>

            <ol class="selectable" >
            @if(count($looks) == 0)
                No Looks found
            @endif

            @foreach($looks as $look)
                <li class="ui-state-default" look_id="{{$look->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('look/view/' . $look->id)}}">{{$look->name == "" ? "Error! Look name empty" : $look->name }}</a></div>
                        <div class="image"><img src="{!! asset('images/' . $look->image) !!}" /></div>
                        <div class="extra text">
                            <?php
                                if (isset($status_rules) && isset($status_rules[$look->status->id]['edit_status']['new_status'])){
                                    $new_statuses = $status_rules[$look->status->id]['edit_status']['new_status'];
                                    if(isset($new_statuses['id'])){
                                        $new_statuses = array($new_statuses);
                                    }
                                    foreach($new_statuses as $new_status){
                                        if(isset($new_status['roles']['role'])){
                                            $roles = $new_status['roles']['role'];
                                            if((is_array($roles) && in_array($user_role, $roles)) ||
                                                    (!is_array($roles) && $user_role == $roles)){
                                                echo '<a href="' . url('look/changestatus/' . $look->id . '/' . $new_status['id']) . '"
                                                   title="Make ' . $new_status['name'] . '"
                                                   class="action ' . $new_status['name'] . '"></a>';
                                            }
                                        }
                                    }
                                }
                            ?>
                            <span>{{$look->status->name}}</span>
                            <span>{{$look->gender->name}}</span>
                            <span>{{$look->occasion->name}}</span>
                            <span>{{$look->body_type->name}}</span>
                        </div>
                        <div class="extra text">
                            <span>{{$look->age_group->name}}</span>
                            <span>Rs.{{$look->price}}</span>
                            <span>Rs.{{$look->budget->name}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $looks->render() !!}
        </div>

        @include('look.create')
    </div>
</div>
@endsection
