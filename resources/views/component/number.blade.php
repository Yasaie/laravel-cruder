<input type="number" name="{{$name}}" class="form-control"
       id="{{$name}}" value="{{isset($value) ? $value : ''}}"
        {{ isset($options['min']) ? 'min=' . $options['min'] : '' }}
        {{ isset($options['max']) ? 'max=' . $options['max'] : '' }} >