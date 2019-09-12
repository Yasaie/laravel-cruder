<input type="text" name="{{$name}}" class="form-control" style="{{ isset($options['dir']) ? "direction: {$options['dir']}" : '' }}"
       id="{{$name}}" value="{{isset($value) ? $value : old($name)}}"
       {{ (isset($options['required']) and $options['required']) ? 'required' : '' }}
       @if(isset($options['mask'])) data-inputmask-regex="{{ $options['mask'] }}" @endif>
