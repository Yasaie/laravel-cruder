<select name="{{$name}}" id="{{$name}}" class="form-control select2"
        style="width: 100%"
        data-placeholder="">
    <option></option>
    @foreach($options['all'] as $c)
        @php($id = isset($options['id'])
            ? dot($c, $options['id'])
            : dot($c, 'id')
        )
        @php($title = isset($options['name'])
            ? dot($c, $options['name'])
            : dot($c, 'title')
        )
    <option value="{{$id}}"
        {{ (isset($value) and $value == $id) ? 'selected' : '' }}>
        {{ $title }}
    </option>
    @endforeach
</select>