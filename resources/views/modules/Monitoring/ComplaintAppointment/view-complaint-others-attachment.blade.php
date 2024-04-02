{{-- edit page --}}
{{-- controller - ajaxGetComplaintOthersAttachmentList --}}

@if (!$complaintAttachment->isEmpty())
    <p id ="clickingText2">Sila klik gambar di bawah:</p>
@else
    <p id="clickingText2">Tiada bukti aduan dijumpai.</p>
@endif

<div class="container">
    <img id="expandedImg2" class="mb-4">
</div>

<div style="display: flex; flex-wrap: wrap; justify-content:center; ">
    @if (!$complaintAttachment->isEmpty())
        @foreach($complaintAttachment as $attachment)
            <img src="{{ getCdn().'/'.$attachment->path_document }}" class="me-1 mb-1" width= "50px" height="50px"  onclick="showOtherComplaintImage(this,{{ $attachment->id }});" style="border: 1px solid black" >
        @endforeach
    @else
        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
    @endif
</div>

<script>
    $(document).ready(function()
    {
        $('.container').hide();

        if ($("#flag").val() == 'view') $("#btn-delete-img").hide();
    });

    function showOtherComplaintImage(imgs,attachment_id) {

    $('#attachment_id').val(attachment_id);
    var expandImg = document.getElementById("expandedImg2");
    var clickingText = document.getElementById("clickingText2");
        expandImg.src = imgs.src;
        expandImg.parentElement.style.display = "block";
        clickingText.style.display = "none";
        expandImg.style.width = '100%';
        expandImg.style.height = '300px';
        expandImg.style.border = "1px solid black";
    }
</script>
