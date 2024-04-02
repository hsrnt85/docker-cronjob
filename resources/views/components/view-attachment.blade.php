
<!--  Large modal example -->
<div class="modal fade modal-attachment" tabindex="-1" role="dialog" aria-labelledby="modal-title-attachment" aria-hidden="true" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title-attachment"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" >

                <img id="iframe-attachment-image" style='height: 100%; width: 100%; object-fit: contain' data-cdn-src="{{ getCdn() }}" src="">
                <embed id="iframe-attachment-pdf" frameborder="0" width="100%" height="600px" data-cdn-src="{{ getCdn() }}" src="">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
