@extends('layouts.master')

@section('title', 'Products list')

@section('content')
<div id="contentCntr">
    <div class="section">
        <div class="container">
            <div class="filters">
                <form method="get" action="">
                    @include('merchant.select')
                    @include('stylist.select')
                    @include('brand.select')
                    @include('category.select')
                    @include('common.gender.select')
                    @include('common.color.select')
                    @include('common.search')
                    @include('common.daterange')
                    @include('common.pricerange')
                    <input type="submit" name="filter" value="Filter"/>
                    <a href="{{url('product/list')}}" class="clearall">Clear All</a>
                </form>
                {!! $products->render() !!}
            </div>

            <div class="clear"></div>

            @foreach($errors->all() as $e)
                <span class="errorMsg">{{$e}}</span><br/>
            @endforeach

            @if(Auth::user()->hasRole('admin') )
                <div class="filters">
                    @include('product.bulk_update')
                </div>

                <div class="clear"></div>
            @endif


            <ol class="selectable" id="selectable">
            @if(count($products) == 0)
                No Products found
            @endif
            @foreach($products as $product)
                <li class="ui-state-default" product_id="{{$product->id}}">
                    <div class="items">
                        <div class="name text"><a href="{{url('product/view/' . $product->id)}}">{{$product->name}}</a></div>
                        <div class="image"><img src="{!! strpos($product->upload_image, "uploadfile") === 0 ? asset('images/' . $product->upload_image) : $product->upload_image !!}" /></div>
                        <div class="extra text">
                            <span><a href="{{$product->product_link}}">View</a></span>
                            <span>{{$product->product_type}}</span>
                            <span>{{$product->category->name}}</span>
                            <span>{{$product->price}}</span>
                            <span>{{$genders_list[$product->gender_id]->name}}</span>
                            <span style="background-color:{{$product->primary_color->name}}">{{$product->primary_color->name}}
                                {{$product->secondary_color->id != 0 ? "({$product->secondary_color->name})" : ""}}</span>
                        </div>
                    </div>
                </li>
            @endforeach
            </ol>

            <div class="clear"></div>

            {!! $products->render() !!}

        </div>


        @include('look.create')

    </div>
</div>

@endsection
