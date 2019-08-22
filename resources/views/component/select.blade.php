<select name="{{$name}}" id="{{$name}}" class="form-control select2"
        style="width: 100%"
        data-placeholder="">
    <option></option>
    @foreach($options['all'] as $c)
        @php($id = isset($options['id'])
            ? Y::dotObject($c, $options['id'])
            : Y::dotObject($c, 'id')
        )
        @php($title = isset($options['name'])
            ? Y::dotObject($c, $options['name'])
            : Y::dotObject($c, 'title')
        )
    <option value="{{$id}}"
        {{ (isset($value) and $value == $id) ? 'selected' : '' }}>
        {{ $title }}
    </option>
    @endforeach
</select>