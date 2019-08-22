<select name="{{$name}}" id="{{$name}}" class="form-control select2"
        style="width: 100%"
        data-placeholder="">
    <option></option>
</select>

<script>
    var select_{{$name}} = $('select#{{$name}}');
    var default_{{$name}} = {{isset($value) ? $value : 0}};
    var check_{{$options['check']}} = $("#{{$options['check']}}");

    function load_{{$name}}() {
        id = check_{{$options['check']}}.val();

        $.ajax("{{$options['url']}}".replace('{{$options['check']}}', id), {
            dataType: "json",
        }).done(function(data) {
            var s2 = $.map(data, function (item) {
                return {
                    text: item.name,
                    id: item.id
                }
            });
            select_{{$name}}.empty().select2(Object.assign({}, select2_array, {
                data: s2,
            }));
            if (default_{{$name}}) {
                select_{{$name}}.val(default_{{$name}});
                default_{{$name}} = 0;
            }
            select_{{$name}}.trigger('change');
        });
    }

    check_{{$options['check']}}.on('change', function () {
        load_{{$name}}();
    });

    if (check_{{$options['check']}}.val()) {
        load_{{$name}}();
    }
</script>