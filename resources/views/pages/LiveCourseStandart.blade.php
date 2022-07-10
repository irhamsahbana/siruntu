<x-row>
    <x-col :col="8">
        <div class="embed-responsive embed-responsive-16by9">
            <video id="video" class="embed-responsive-item" src="" allowfullscreen controls>
                <source src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0">
                    Your browser does not support the video tag.
            </video>
        </div>
    </x-col>

    <x-col :col="4">
        <!-- DIRECT CHAT PRIMARY -->
        <div class="card card-primary card-outline direct-chat direct-chat-primary">
            <div class="card-header">
            <h3 class="card-title">Pesan Masuk</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages">
                @for($i = 0; $i < 10; $i++)
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left">Alexander Pierce</span>
                        <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="{{ asset('assets') }}/dist/img/user1-128x128.jpg" alt="Message User Image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                        Is this template really for free? That's unbelievable!
                    </div>
                    <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->

                <!-- Message to the right -->
                <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-right">Sarah Bullock</span>
                        <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="{{ asset('assets') }}/dist/img/user3-128x128.jpg" alt="Message User Image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                        You better believe it!
                    </div>
                    <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->
                @endfor
            </div>
            <!--/.direct-chat-messages-->

            <!-- Contacts are loaded here -->
            <div class="direct-chat-contacts">
                <ul class="contacts-list">
                <li>
                    <a href="#">
                    <img class="contacts-list-img" src="{{ asset('assets') }}/dist/img/user1-128x128.jpg" alt="User Avatar">

                    <div class="contacts-list-info">
                        <span class="contacts-list-name">
                        Count Dracula
                        <small class="contacts-list-date float-right">2/28/2015</small>
                        </span>
                        <span class="contacts-list-msg">How have you been? I was...</span>
                    </div>
                    <!-- /.contacts-list-info -->
                    </a>
                </li>
                <!-- End Contact Item -->
                </ul>
                <!-- /.contatcts-list -->
            </div>
            <!-- /.direct-chat-pane -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            <form action="#" method="post">
                <div class="input-group">
                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                <span class="input-group-append">
                    <button
                        id="btn-send-chat"
                        type="button"
                        class="btn btn-primary">Kirim</button>
                </span>
                </div>
            </form>
            </div>
            <!-- /.card-footer-->
        </div>
        <!--/.direct-chat -->

        <x-row>
            <x-col :col="2">
                <button
                    id="broadcastWebcam"
                    title="Mulai broadcast kamera"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-video"></i></button>
            </x-col>

            <x-col :col="2">
                <button
                    id="broadcastScreenshare"
                    title="Mulai braodcast screen"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-window-maximize"></i></button>
            </x-col>

            <x-col :col="2">
                <button
                    title="Mulai rekam broadcast"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-record-vinyl"></i></button>
            </x-col>

            <x-col :col="2">
                <button
                    id="terminate"
                    title="Hentikan broadcasting"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-stop-circle"></i></button>
            </x-col>

            <x-col :col="2">
                <button
                    id="view"
                    title="viewing broadcasting"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i></button>
            </x-col>


            {{-- <x-col :col="2">
                <button
                    type="button"
                    title="Unggah file"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-file-upload"></i></button>
            </x-col> --}}

            <x-col :col="2">
                <button
                    type="button"
                    title="Kirim file"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-paper-plane"></i></button>
            </x-col>

            <x-col :col="2">
                <button
                    id="btn-change-mode"
                    type="button"
                    title="Ubah mode"
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-image"></i></button>
            </x-col>
        </x-row>
    </x-col>
</x-row>