@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-4 col-xl-3 chat">
                    <div class="card mb-sm-3 mb-md-0 contacts_card">
                        <div class="alert alert-primary" role="alert">
                            <button type="button"class="btn btn-primary" data-toggle="modal" data-target="#create_group">
                                Tạo nhóm
                            </button>
                            <a href="{{ url('/home') }}" class="btn btn-primary">Quay về</a>
                        </div>
                        <div class="card-header">


                            <div class="input-group">
                                <input type="text" placeholder="Search..." name=""
                                    class="form-control search search-text">
                                <div class="input-group-prepend">
                                    <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <input id="group-chat-id" type="hidden" value="{{ $groupChat->id }}">
                        <div class="card-body contacts_body">
                            <ui class="contacts">
                                <li>
                                    <a id="user{{ $leader->id }}"
                                        href="{{ Auth::user()->id == $leader->id ? 'javascript:void(0);' : url('chat-private/' . $leader->id) }}">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="{{ $leader->image }}" class="rounded-circle user_img">

                                                {{-- <span class="online_icon"></span> --}}
                                            </div>

                                            <div class="user_info">
                                                <span>{{ $leader->name }}</span>
                                                <p>Trưởng nhóm</p>
                                            </div>


                                        </div>
                                    </a>

                                </li>
                                @foreach ($member as $item)
                                    <li>
                                        <a href="{{ url('chat-private/' . $item->id) }}" id="user{{ $item->id }}">
                                            <div class="d-flex bd-highlight">
                                                <div class="img_cont">
                                                    <img src="{{ $item->image }}" class="rounded-circle user_img">

                                                    {{-- <span class="online_icon"></span> --}}
                                                </div>

                                                <div class="user_info">
                                                    <span>{{ $item->name }}</span>
                                                    <p>Thành viên</p>
                                                </div>


                                            </div>
                                        </a>

                                    </li>
                                @endforeach




                            </ui>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-md-8 col-xl-6 chat">
                    <div class="card">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg"
                                        class="rounded-circle user_img">
                                    <span class="online_icon"></span>
                                </div>
                                <div class="user_info">
                                    <span>{{ $groupChat->name }}</span>
                                    <p style="font-size:20px">{{ $count + 1 }} thành viên trong đoạn chat</p>
                                </div>
                                {{-- <div class="video_cam">
                                    <span><i class="fas fa-video"></i></span>
                                    <span><i class="fas fa-phone"></i></span>
                                </div> --}}
                            </div>
                            {{-- <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span> --}}
                            {{-- <div class="action_menu">
                                <ul>
                                    <li><i class="fas fa-user-circle"></i> View profile</li>
                                    <li><i class="fas fa-users"></i> Add to close friends</li>
                                    <li><i class="fas fa-plus"></i> Add to group</li>
                                    <li><i class="fas fa-ban"></i> Block</li>
                                </ul>
                            </div> --}}
                        </div>
                        <div class="card-body msg_card_body">

                            @foreach ($messageGroup as $item)
                                @if ($item->user_send == Auth::user()->id)
                                    <div class="d-flex justify-content-end mb-4">
                                        <div class="msg_cotainer_send">
                                            {{ $item->message }}
                                            <span class="msg_time"
                                                data-timestamp="{{ $item->created_at->timestamp }}"></span>
                                        </div>
                                        <div class="img_cont_msg">
                                            <img src="{{ $item->image }}" class="rounded-circle user_img_msg">
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-start mb-4">
                                        <div class="img_cont_msg">
                                            <a href="{{ url('chat-private/' . $item->id) }}" id="user{{ $item->id }}">
                                                <img src="{{ $item->image }}" class="rounded-circle user_img_msg">
                                            </a>

                                        </div>
                                        <div class="msg_cotainer">
                                            {{ $item->message }}
                                            <span class="msg_time"
                                                data-timestamp="{{ $item->created_at->timestamp }}"></span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach


                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                </div>
                                <textarea name="" id="content_message" class="form-control type_msg" placeholder="Type your message..."></textarea>
                                <div class="input-group-append">
                                    <span style=" cursor: default;" id="send_message"
                                        class="input-group-text send_btn"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create_group" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tạo nhóm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Tên nhóm</label>
                        <input type="text" class="form-control" id="name" aria-describedby="emailHelp"
                            placeholder="Tên nhóm ...">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Trưởng nhóm</label>
                        <input type="text" id="id_leader" class="form-control" value="{{ Auth::user()->name }}"
                            disabled aria-describedby="emailHelp">

                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Thành viên</label>
                        <select id="members" multiple class="form-select">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" id="createGroup" class="btn btn-primary">Lưu nhóm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.all.min.js"></script>

    <script type="module">
        var createGroup = document.querySelector('#createGroup')


        // Now you can access the value of the input field using nameGroupInput.value
        document.addEventListener('DOMContentLoaded', function() {
            var name = document.querySelector('#name')

            var members = document.querySelector('#members')
            var membersSelect = document.getElementById('members');
            var selectedValues = []
            membersSelect.addEventListener('change', function() {
                selectedValues = Array.from(this.selectedOptions).map(option => option.value);
            });

            createGroup.addEventListener('click', function() {
                axios.post('/create-group', {
                    name: name.value,
                    id_leader: '{{ Auth::user()->id }}',
                    members: selectedValues
                }).then(res => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.data.message,
                        showConfirmButton: false,
                        timer: 500,
                    }).then(() => {
                        window.location.reload();
                    });
                })
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#action_menu_btn').click(function() {
                $('.action_menu').toggle();
            });
        });
    </script>


    <script type="module">
        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            let interval = Math.floor(seconds / 31536000);

            if (interval >= 1) {
                return interval + " year" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                return interval + " month" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                return interval + " day" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) {
                return interval + " hour" + (interval > 1 ? "s" : "") + " ago";
            }
            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
                return interval + " minute" + (interval > 1 ? "s" : "") + " ago";
            }
            return Math.floor(seconds) + " second" + (seconds > 1 ? "s" : "") + " ago";
        }

        function updateTimes() {
            var msgTimes = document.querySelectorAll('.msg_time');
            msgTimes.forEach(function(span) {
                const timestamp = span.getAttribute('data-timestamp');
                if (timestamp) {
                    const date = new Date(timestamp * 1000);
                    span.innerHTML = timeSince(date);
                } else {
                    console.error('Timestamp is null for span:', span);
                }
            });
        }

        updateTimes();

        setInterval(updateTimes, 1000);
        Echo.join('chat')
            .here(users => {

                users.forEach(user => {

                    // Find the user item by id
                    var userItem = document.querySelector(`#user${user.id}`);

                    if (userItem) {

                        // Find the img_cont inside the user item
                        var imgCont = userItem.querySelector('.img_cont');

                        // Check if the online_icon already exists to prevent duplicates

                        var status = document.createElement('span');
                        status.classList.add('online_icon');

                        // Append the span inside the correct img_cont
                        imgCont.appendChild(status);

                    }
                });
            })
            .joining(user => {
                var el = document.querySelector(`#user${user.id}`)
                if (el) {
                    var img_cont = el.querySelector('.img_cont')
                    if (img_cont) {
                        var el_status = document.createElement('span')
                        el_status.classList.add('online_icon')
                        img_cont.appendChild(el_status)
                    }
                }
            })
            .leaving(user => {
                var el = document.querySelector(`#user${user.id}`)
                if (el) {
                    var img_cont = el.querySelector('.img_cont')


                    var el_status = img_cont.querySelector('.online_icon')
                    if (el_status) {
                        img_cont.removeChild(el_status)
                    }
                }
            });


        var content_message = document.querySelector('#content_message')
        console.log(content_message.value);
        var groupChatId = document.querySelector('#group-chat-id')
        var fa_location_arrow = document.createElement('i')
        fa_location_arrow.classList.add('fas', 'fa-location-arrow');

        var send_btn = document.querySelector('.send_btn')

        content_message.addEventListener('input', function() {
            if (content_message.value.trim() == '') {
                if (send_btn.contains(fa_location_arrow)) {
                    send_btn.removeChild(fa_location_arrow);
                }
            } else {
                if (!send_btn.contains(fa_location_arrow)) {
                    send_btn.appendChild(fa_location_arrow);
                    fa_location_arrow.style.cursor = 'pointer'
                }
            }
        });


        fa_location_arrow.addEventListener('click', function() {
            let messageContent = content_message.value.trim();
            content_message.value = '';
            if (send_btn.contains(fa_location_arrow)) {
                send_btn.removeChild(fa_location_arrow);
            }
            axios.post('/send-message-group', {
                    message: messageContent,
                    groupChatId: groupChatId.value
                })
                .then(function(response) {
                    content_message.value = ''
                });
        });



        Echo.private('chat.group.{{ $groupChat->id }}')

            .listen('ChatGroup', event => {

                var ui = ''
                const currentTimestamp = Math.floor(Date.now() / 1000); // Current time in seconds
                if (event.userSend.id == '{{ Auth::user()->id }}') {
                    ui = `
       <div class="d-flex justify-content-end mb-4" id="full-leader">
        <div class="msg_cotainer_send">
                       ${event.message}
                    <span class="msg_time" data-timestamp="${currentTimestamp}"></span>
                        </div>
                       
                    <div class="img_cont_msg">
                        <img src="${event.userSend.image}"
                            class="rounded-circle user_img_msg">
                    </div>
                </div>
  `
                } else {
                    ui =
                        `
         <div class="d-flex justify-content-start mb-4">
                    <div class="img_cont_msg">
                        <img src="${event.userSend.image}" class="rounded-circle user_img_msg">
                    </div>
                    <div class="msg_cotainer">
                       ${event.message}
                      <span class="msg_time" data-timestamp="${currentTimestamp}"></span>
                    </div>
                </div> 
         
         `
                }

                var msg_card_body = document.querySelector('.msg_card_body');
                if (event.group.id_leader == event.userSend.id) {
                    var mb_4 = document.querySelector('#full-leader')

                }
                msg_card_body.insertAdjacentHTML('beforeend', ui)
                updateTimes();
            });
    </script>
    <script>
        var search_text = document.querySelector('.search-text')
        var contacts = document.querySelector('.contacts');
        var groupChatId = document.querySelector('#group-chat-id')
        search_text.addEventListener('input', function() {
            var query = search_text.value.trim();


            axios.post('search', {
                    search_text: query,
                    groupChatId: groupChatId.value
                })
                .then(function(response) {

                    var ui = '';
                    if (response.data && response.data.data) {
                        response.data.data.forEach(function(user) {
                            ui += `
            <li>
                <a href="chat-private/${user.id}" id="user${user.id}">
                    <div class="d-flex bd-highlight">
                        <div class="img_cont">
                            <img src="${user.image}" class="rounded-circle user_img">
                        </div>
                        <div class="user_info">
                            <span>${user.name}</span>
                        </div>
                    </div>
                </a>
            </li>
        `;
                        });
                    }
                    contacts.innerHTML = ui;
                })




        })
    </script>
@endsection
