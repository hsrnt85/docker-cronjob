$(function() {

    let url = $('#page-header-notifications-dropdown').data('url');
    $.ajax({
        url: url,
        type:"GET",
    }).done(function(response, textStatus, jqXHR){

        let html = '';

        if(response.total > 0) $('#unread-notification-total').html(response.total);

        $.each(response.data, function( index, value ) {
            // dd(value.data['flag_emergency']);
            let is_read = (!value.read_at) ? 'bg-danger' : 'bg-success';
            let created_at = moment(value.created_at).format('DD/MM/YYYY, h:mm A');
            let icon = (value.data['flag_emergency'] == 1) ? 'mdi-alert' : 'mdi-information-variant';

            html += `<a href="${value.data['url']}" class="text-reset notification-item" data-id=${value.id}>
                        <div class="d-flex border-bottom">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title ${is_read} rounded-circle p-2 font-size-16">
                                    <i class="mdi ${icon}"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mt-0 mb-1">${value.data['title']}</h6>
                                <div class="font-size-12 text-muted">
                                    <p class="mb-1" key="t-simplified">${value.data['body']}</p>
                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">${ created_at }</span></p>
                                </div>
                            </div>
                        </div>
                    </a>`;
        });

        $('#notification-container').find('div.simplebar-content').append(html);

    });

    $(document).on('click', 'a.notification-item', function(){
        let url = $('#notification-container').data('mark-url');
        let id = $(this).data('id');

        $.ajax({
            url: url,
            type:"GET",
            id: id
        })
    });
});

