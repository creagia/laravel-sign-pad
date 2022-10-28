<form action="{{ route('sign-pad::signature')}}" method="POST">
    @csrf
    <div style="text-align: center">
        <x-laravel-sign-pad::signature-pad
            width="500"
            height="250"
            border-color="#eaeaea"
            pad-classes=""
            button-classes=""
        />
    </div>
</form>
