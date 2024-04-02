@if (!$complaintInventoryAttachment->isEmpty())
    <p id="clickingText">Sila klik gambar di bawah:</p>
@else
    <p id="clickingText">Tiada bukti aduan dijumpai.</p>
@endif

<div class="container" >
    <img id="expandedImg" class="mb-4" >
</div>

<div id="complaint_inventory_attachment" style="display: flex; flex-wrap: wrap; justify-content:center; ">

    @if (!$complaintInventoryAttachment->isEmpty())
        @foreach($complaintInventoryAttachment as $inventory_attachment)
            <img src="{{ getCdn().'/'.$inventory_attachment->path_document }}" class="me-1 mb-1" width= "50px" height="50px" onclick="showInventoryImage(this,{{ $inventory_attachment->id }});"  style="border: 1px solid black">
        @endforeach
    @else
        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
    @endif

</div>

<script>
        $(document).ready(function()
        {
            $('.container').hide();

            if ($("#flag").val() == 'view') $("#btn-delete-img-inventory").hide();
        });

        function showInventoryImage(imgs,inventory_attachment_id) {

            $('#inventory_attachment_id').val(inventory_attachment_id);
            var expandImg = document.getElementById("expandedImg");
            var clickingText = document.getElementById("clickingText");
                expandImg.src = imgs.src;
                expandImg.parentElement.style.display = "block";
                clickingText.style.display = "none";
                expandImg.style.width = '100%';
                expandImg.style.height = '300px';
                expandImg.style.border = "1px solid black";
        }

</script>
