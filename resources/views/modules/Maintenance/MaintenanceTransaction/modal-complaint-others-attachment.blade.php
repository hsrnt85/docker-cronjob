{{-- edit page & view page --}}
{{-- controller - ajaxGetComplaintOthersAttachmentList --}}

@if ($complaintAttachment->isEmpty() || $complaintAttachment == null )
    <p id="clickingText2">Tiada bukti aduan dijumpai</p>
@else
    <p id="clickingText2">Sila klik gambar di bawah:</p>
@endif

<div class="container">
    <img id="expandedImg2" class="mb-4">
</div>

<div  style="display: flex; flex-wrap: wrap; justify-content:center; ">
    @if ($complaintAttachment->isEmpty() || $complaintAttachment == null)
        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
    @else
        @foreach($complaintAttachment as $attachment)
            @if ( $attachment)
                 <input type="hidden" value="{{$attachment->id}}" name="attachment_id" id="attachment_id" >
                    <img src="{{ getCdn().'/'.$attachment->path_document }}" class="me-1 mb-1" width= "50px" height="50px"  onclick="showOtherComplaintImage(this,{{ $attachment->id}});" style="border: 1px solid black" >
            @endif
        @endforeach
    @endif
</div>

<script>
    $(document).ready(function()
    {
        $('.container').hide();

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
