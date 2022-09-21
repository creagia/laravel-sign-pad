<form action="{{ route('sign-pad::signature')}}" method="POST">
    @csrf
    <div style="text-align: center">
        <x-signature-pad
            width="500"
            height="250"
            border-color="#eaeaea"
            pad-classes=""
            button-classes=""
        />
    </div>
</form>
