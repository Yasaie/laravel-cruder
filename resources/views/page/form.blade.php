@extends('Cruder::layout')

@section('title', $title)

@section('body')

    <form method="post" action="{{route("$route.$form_action", $form_id)}}" id="create">
        @csrf
        @if($form_id)
            @method('PUT')
        @endif

        @foreach($rows as $row)
            @include('Cruder::include.form-group', [
                'tabs' => isset($row['tabs']) ? $row['tabs'] : [],
                'body' => isset($row['body']) ? $row['body'] : $row
            ])
        @endforeach

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">@lang('Cruder::crud.save')</button>
                        <a href="{{url()->previous()}}" class="btn btn-default float-left">@lang('Cruder::crud.return')</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('vendor/cruder/plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/cruder/plugins/dropzone/min/dropzone.min.css')}}">
    <script type="text/javascript" src="{{asset('vendor/cruder/plugins/select2/select2.full.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/cruder/plugins/select2/i18n/' . app()->getLocale() . '.js')}}"></script>
    <script>
        var select2_array = {
            dir: '{{isRTL(0)}}',
            language: '{{app()->getLocale()}}',
            minimumResultsForSearch: 5,
        };
        $(document).ready(function () {
            $('.select2').select2(select2_array);
        });
    </script>
@endsection

@section('script')
    <script src="{{asset('vendor/cruder/plugins/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('vendor/cruder/plugins/dropzone/min/dropzone.min.js')}}"></script>
    <script src="{{asset('vendor/cruder/plugins/dropzone/i18n/fa.js')}}"></script>
    <script>
        Dropzone.autoDiscover = false;
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea.text-html',
                height: 300,
                theme: "modern",
                plugins: [
                    "autolink link image lists charmap print hr anchor spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "table contextmenu directionality template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | print | forecolor backcolor",
                content_css: '{{asset('vendor/cruder/css/tinymce-reset.css')}}',
                directionality: '{{isRTL(0)}}',
                language: '{{app()->getLocale()}}',
                extended_valid_elements: 'img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"\n' +
                    '  +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"\n' +
                    '  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"\n' +
                    '  +"|onmouseup|src|style|title|usemap|vspace|width]'
            });
        });
    </script>
@endsection