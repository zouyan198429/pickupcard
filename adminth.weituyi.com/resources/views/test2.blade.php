@extends('layouts.app')

@push('headscripts')
    {{--  本页单独使用 --}}
@endpush

@section('content')
@endsection


@push('footscripts')
    <script type="text/javascript">
        $(function(){
            countdown_alert('请选择需要操作的记录!',3,5);

        })
    </script>
@endpush