<script type="text/javascript" src="{{asset('vendor/cruder/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/cruder/plugins/iziToast/js/iziToast.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/cruder/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
@yield('script')
<script type="text/javascript" src="{{asset('vendor/cruder/js/adminlte.min.js')}}" defer async></script>

<script>
    const iziToastConst = {
        position: '{{ isRTL() ? 'bottomLeft' : 'bottomRight' }}',
        timeout: 10000,
        transitionIn: 'flipInX',
        transitionOut: 'flipOutX',
        maxWidth: '40vw',
        rtl: {{isRTL()}}
    };
    $(document).ready(function () {
        jQuery('input[type=text]').inputmask();

        @foreach ($errors->all() as $error)
        iziToast.error(Object.assign({}, iziToastConst, {
            message: '{{ $error }}'
        }));
        @endforeach

    });
</script>
