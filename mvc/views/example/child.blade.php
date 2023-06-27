@layout('example/test')

@section('content')

    @include('example/in')
    <?php dd($newarray['message']); ?>

@endsection

  @include('example/in')

    @dd("ok")
@endsection
