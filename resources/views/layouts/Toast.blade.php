@if (session('message'))
    <input type="hidden" id="message"  value="{{ session('message') }}" disabled>

    <script>
        $(document).ready(function() {
            let msg = $("#message").val();

            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Sukses!',
                body: msg
            })
        });
    </script>
@endif

@if ($errors->any())

    <div class="error_msgs">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        $(document).ready(function() {
            let msg = $('.error_msgs').clone();
            $('.error_msgs').hide();

            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Terjadi Kesalahan!',
                body: msg
            })
        });
    </script>
@endif