
@if ($maintenanceTransactionAttachment->isEmpty() || $maintenanceTransactionAttachment == null )
    <p id="clickingText3">Tiada gambar dijumpai.</p>
@else
    <p id="clickingText3">Sila klik gambar di bawah:</p>
@endif

<div class="container">
    <img id="expandedImg3" class="mb-4">
</div>

<div style="display: flex; flex-wrap: wrap; justify-content:center; ">
    @if ($maintenanceTransactionAttachment->isEmpty() || $maintenanceTransactionAttachment == null)
        <img  src="{{ getNoImages() }}" alt="no_images" width="auto" height="100px" class="mt-3">
    @else
        @foreach($maintenanceTransactionAttachment as $attachment)
            <img src="{{ getCdn().'/'.$attachment->path_document }}" class="me-1 mb-1" width= "50px" height="50px" onclick="showImageMaintenance(this);"  style="border: 1px solid black">
        @endforeach
    @endif
</div>

<script>

    function showImageMaintenance(imgs) {

        var expandImg = document.getElementById("expandedImg3");
        var clickingText = document.getElementById("clickingText3");
            expandImg.src = imgs.src;
            expandImg.parentElement.style.display = "block";
            clickingText.style.display = "none";
            expandImg.style.width = '100%';
            expandImg.style.height = '300px';
            expandImg.style.border = "1px solid black";
    }

</script>
