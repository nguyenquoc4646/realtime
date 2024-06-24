listen('StatusUserEvent', event => {
    var msg_card_body = document.querySelector('.msg_card_body');
    var ui = '';

    const currentTimestamp = Math.floor(Date.now() / 1000); // Current time in seconds
    if (event.user.id == '{{ Auth::user()->id }}') {
        ui = `
            <div class="d-flex justify-content-end mb-4">
                <div class="msg_cotainer_send">
                   ${event.message}
                   <span class="msg_time" data-timestamp="${currentTimestamp}"></span>
                </div>
                <div class="img_cont_msg">
                    <img src="${event.user.image}" class="rounded-circle user_img_msg">
                </div>
            </div>
        `;
    } else {
        var chatPrivateUrl = `{{ url('chat-private') }}/${event.user.id}`;
        ui = `
            <div class="d-flex justify-content-start mb-4">
                <div class="img_cont_msg">
                    <a href="${chatPrivateUrl}">
                        <img src="${event.user.image}" class="rounded-circle user_img_msg">
                    </a>
                </div>
                <div class="msg_cotainer">
                   ${event.message}
                   <span class="msg_time" data-timestamp="${currentTimestamp}"></span>
                </div>
            </div>
        `;
    }

    msg_card_body.insertAdjacentHTML('beforeend', ui);
    updateTimes();
});