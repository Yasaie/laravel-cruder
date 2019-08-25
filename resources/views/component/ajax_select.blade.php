<select name="{{$name}}" id="{{$name}}"
        class="form-control select2 w-100">
</select>

<script>
    $(document).ready(function () {
        $("#{{$name}}").select2(Object.assign({}, select2_array, {
            minimumResultsForSearch: 0,
            ajax: {
                url: "{{ $options['url'] }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
        }));
    });
</script>