<link href="/css/classic.css" rel="stylesheet">
<link href="/css/classic.date.css" rel="stylesheet">

<input type="text" id="from_date" name="from_date" value="{{$from_date}}" placeholder="From Date" class="form-control search">
<input type="text" id="to_date" name="to_date" value="{{$to_date}}" placeholder="To Date" class="form-control search">

<script src="/js/picker.js"></script>
<script src="/js/picker.date.js"></script>
<script>
    $(function() {
        $('#from_date').pickadate({
            format: 'dd mmm yyyy',
        });
        $('#to_date').pickadate({
            format: 'dd mmm yyyy',
        });

//        to_date should be greater than from_date
    });
</script>