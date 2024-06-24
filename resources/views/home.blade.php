@extends('layouts.app')
@section('style')

@endsection
@section('content')
    <div class="container">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-4 col-xl-3 chat">
                    <div class="card mb-sm-3 mb-md-0 contacts_card">
                        <div class="alert alert-primary" role="alert">
                         <h5>Các nhóm của bạn</h5>
                        </div>
                        <div class="card-body contacts_body">
                           
                            <ui class="contacts">
                                <div class="group">
                                    @foreach ($group_my_chat as $item)
                                        <li>
                                            <a href="{{ url('chat-group/' . $item->groupchatId) }}">
                                                <div class="d-flex bd-highlight">
                                                    <div class="img_cont">
                                                        <img src="https://png.pngtree.com/element_our/png_detail/20180912/group-avatar-icon-design-vector-png_90987.jpg"
                                                            class="rounded-circle user_img">

                                                        {{-- <span class="online_icon"></span> --}}
                                                    </div>

                                                    <div class="user_info">
                                                        <span>{{ $item->name }}</span>
                                                        <p>Bạn là trưởng nhóm</p>
                                                    </div>
                                                </div>
                                            </a>

                                        </li>
                                    @endforeach
                                    @foreach ($group_not_leader as $item)
                                        <li>
                                            <a href="{{ url('chat-group/' . $item->groupchatId) }}">
                                                <div class="d-flex bd-highlight">
                                                    <div class="img_cont">
                                                        <img src="https://png.pngtree.com/element_our/png_detail/20180912/group-avatar-icon-design-vector-png_90987.jpg"
                                                            class="rounded-circle user_img">

                                                        {{-- <span class="online_icon"></span> --}}
                                                    </div>

                                                    <div class="user_info">
                                                        <span>{{ $item->name }}</span>
                                                        <p>Bạn là thành viên</p>
                                                    </div>
                                                </div>
                                            </a>

                                        </li>
                                    @endforeach

                                </div>




                            </ui>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>
                <div class="col-md-8 col-xl-6 chat">
                    <div class="card">

                        <div class="card-body msg_card_body">
                            <ul class="block-chat">
                                <button type="button"class="btn btn-primary" data-toggle="modal"
                                    data-target="#create_group">
                                    Tạo nhóm
                                </button>
                                <a href="{{ url('/chat') }}" class="btn btn-primary">Chat cộng đồng</a>
                            </ul>

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
                        <input type="text" id="id_leader" class="form-control" value="{{ Auth::user()->name }}" disabled
                            aria-describedby="emailHelp">

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
@endsection
