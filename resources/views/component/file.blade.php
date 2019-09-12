@php($dropzone = str_replace(['[', ']', '.'], '_', $name) . 'dropzone')
<div action="{{route('crud.media.upload')}}" class="dropzone" id="{{ $dropzone }}"></div>
<input type="hidden"
       name="{{$name}}"
       id="{{ $dropzone }}">
@section('script')
    @parent
<script>
    var {{ $dropzone }} = null;
    $(document).ready(function () {
        var _token = $('[name=csrf-token]').attr('content');
        {{ $dropzone }} = new Dropzone('div#{{$dropzone}}', {
            addRemoveLinks: true,
            parallelUploads: 10,
            acceptedFiles: '.jpg, .png, .gif',
            @if(isset($options['max_files']))
            maxFiles: {{$options['max_files']}},
            @endif
            sending: function (file, xhr, formData) {
                formData.append("_token", _token);
            },
            success: function (file, response) {
                file.id = response;
            },
            removedfile: function (file) {
                file.previewElement.remove();
                $.ajax({
                    type: 'POST',
                    url: '{{route('crud.media.unlink')}}/' + file.id,
                    data: {
                        _method: 'DELETE',
                        _token: _token
                    }
                });
            },
        });

        @if(isset($value) and is_object($value))
        @php($get = isset($get) ? $get : $name)
        @php($library = $value->getMedia($get))
        @php($dropzone_data = collect())
        @php($thumb_name = isset($options['thumb']) ? $options['thumb'] : 'small')
        @foreach($library as $lib)
        @php($dropzone_data[] = [
            'name' => $lib->getAttribute('file_name'),
            'size' => $lib->getAttribute('size'),
            'thumb' => $lib->getFullUrl($thumb_name),
            'id' => $lib->id
        ])
        @endforeach
        //Add existing files into dropzone
        var existingFiles = {!! $dropzone_data !!};

        for (i = 0; i < existingFiles.length; i++) {
            {{ $dropzone }}.emit("addedfile", existingFiles[i]);
            {{ $dropzone }}.emit("thumbnail", existingFiles[i], existingFiles[i].thumb);
            {{ $dropzone }}.emit("complete", existingFiles[i]);
            {{ $dropzone }}.files[i] = {id: existingFiles[i].id}
        }
        @endif
    });
    $('form#create').on('submit', function (event) {
        event.preventDefault(); //this will prevent the default submit
        var array = [];
        Object.values({{ $dropzone }}.files).forEach(function (e) {
            array.push(e.id)
        });
        $("input#{{$dropzone}}").val(array);
        $(this).unbind('submit').submit(); // continue the submit unbind preventDefault
    })
</script>
@endsection