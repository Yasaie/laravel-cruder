<div action="{{route('admin.media.upload')}}" class="dropzone" id="{{$name}}"></div><input type="hidden"
                                                                                           name="{{$name}}"
                                                                                           id="{{$name}}">
<script>
    var {{$name}}Dropzone = null;
    $(document).ready(function () {
        var _token = $('[name=csrf-token]').attr('content');
        {{$name}}Dropzone = new Dropzone('div#{{$name}}', {
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
                    url: '{{route('admin.media.unlink')}}/' + file.id,
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
            {{$name}}Dropzone.emit("addedfile", existingFiles[i]);
            {{$name}}Dropzone.emit("thumbnail", existingFiles[i], existingFiles[i].thumb);
            {{$name}}Dropzone.emit("complete", existingFiles[i]);
            {{$name}}Dropzone.files[i] = {id: existingFiles[i].id}
        }
        @endif
    });
    $('form#create').on('submit', function (event) {
        event.preventDefault(); //this will prevent the default submit
        var array = [];
        Object.values({{$name}}Dropzone.files).forEach(function (e) {
            array.push(e.id)
        });
        $("input#{{$name}}").val(array);
        $(this).unbind('submit').submit(); // continue the submit unbind preventDefault
    })
</script>
