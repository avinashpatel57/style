<select name="brand_id">
    <option value="">All</option>
    @foreach($brands as $brand)
        <option value="{{$brand->id}}" {{$brand_id == $brand->id ? "selected" : ""}}>{{$brand->name}} ({{$brand->product_count}})</option>
    @endforeach
</select>