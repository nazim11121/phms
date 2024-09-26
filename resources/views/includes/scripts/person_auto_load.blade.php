<script type="text/javascript">
    !(function($){
        "use strict";
            // Load country wise state
            $('#order_by').on('change',function(e){console.log('data');
                let order_by = e.target.value;
                $.ajax({
                    url: "{{url('/admin/order-management/create/person/')}}"+'/'+order_by,
                    type: "GET",
                    dataType: "JSON",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data){
                        console.log(data);
                        let name =  $('#name').empty();
                        name.append('<option class="form-control" value="0" selected disabled>Select Upazila</option>');
                        $.each(data,function(key,val){
                            upazila_id.append('<option value ="'+val.id+'">'+val.name+'</option>');
                        });
                    }
                })
            });

    })(jQuery)
</script>
