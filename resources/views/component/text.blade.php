<input type="text" name="{{$name}}" class="form-control"
       id="{{$name}}" value="{{isset($value) ? $value : ''}}"
        {{ (isset($options['required']) and $options['required']) ? 'required' : '' }}>