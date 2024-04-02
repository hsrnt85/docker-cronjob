
<link href="{{ getPathDocumentCss() .'collector-statement.css' }}" type="text/css" />

<div class="body_penyata_pemungut ">

   {{------------------------------------------PAGE 1-----------------------------------------------}}
   @php $bil_page = 1; @endphp
    @include('download-pdf.penyata-pemungut.page1-slipbank')

    <div class="pb-5"></div>
    <div class="pb-5"></div>
    <div class="pb-5"></div>

    <table class="info_content_border"  cellpadding="3" cellspacing="0" >
        <tr class="info_content_border" > 
            <td > NO AKAUN : {{ $bank_account_main->account_no ?? '' }} </td>
            <td > NAMA :  {{ $bank_account_main->bank_name ?? '' }}</td>
        </tr>
    </table>

    @include('download-pdf.penyata-pemungut.page-header')

    <div class="pb-3"></div>

    @include('download-pdf.penyata-pemungut.page-footer')

    <div style="page-break-inside: avoid">

    {{------------------------------------------PAGE 2 ++ ----------------------------------------------}}
    {{-- @php $bil_page = 1; @endphp --}}
    @foreach($chunk_notice_by_10 as $chunk_per_page)

        @php $bil_page++ @endphp

        <div class="page-break">

        @include('download-pdf.penyata-pemungut.page-header')

        <div class="pb-2"></div>

        @include('download-pdf.penyata-pemungut.page2-vot')

        <div class="pb-2"></div>

        @include('download-pdf.penyata-pemungut.page2-receipt')

        <div class="pb-1"></div>

        {{-----------------------GET TOTAL AMOUNT BY CHUNK/PAGE---------------------------}}
        @php  $total_amount = 0.00;  @endphp

        @foreach($chunk_per_page as $amount)
            @php  $total_amount += $amount->total_amount;  @endphp
        @endforeach

        <table class="info_content_border" style="border-block-color: black" cellpadding="3" cellspacing="0" >
            <tr class="info_content_border" >
                <td colspan="2" width="30%" style="border-right:none"> </td>
                <td width="20%" style="border-right:none; border-left:none" > JUMLAH </td>
                <td width="20%" style="border-left:none; border-right:none; text-align:right;"> {{numberFormatComma($total_amount)}}</td>
                <td width="30%" style="border-left:none;"> </td>
            </tr>
        </table>
        {{---------------------------------------------------------------------------------}}

        <div class="pb-1"></div>

        @include('download-pdf.penyata-pemungut.page-footer')

        <div style="page-break-inside: avoid">

    @endforeach

</div>
