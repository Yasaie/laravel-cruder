<textarea name="{{$name}}" id="{{$name}}" class="form-control" rows="8"
    {{ (isset($options['required']) and $options['required']) ? 'required' : '' }}>{{isset($value) ? $value : ''}}</textarea>