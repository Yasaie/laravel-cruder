<select name="{{$name}}[]" id="{{$name}}" multiple class="form-control select2"
        style="width: 100%"
        data-placeholder="">
    @foreach($options['all'] as $c)
        @php($id = isset($options['id']) ? dot($c, $options['id']) : $c->id)
    <option value="{{$id}}"
        {{ (isset($value) and in_array($id, $value)) ? 'selected' : '' }}>
        {{ dot($c, $options['name']) }}
    </option>
    @endforeach
</select>