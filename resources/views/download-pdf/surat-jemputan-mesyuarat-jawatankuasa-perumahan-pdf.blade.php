<link href="{{ getPathDocumentCss() .'document-pdf.css' }}" type="text/css" />

<div class ="body">

    {{-- PAGE 1 - SURAT --}}
    <table width="100%">
        <tr>
            <td width="48%"></td>
            <td class="align-top">Ruj Kami </td>
            <td width="35%" class="align-top">: <span class="ml-20px">SUKJ.BKP {{$meeting->letter_ref_no}}</span></td>
        </tr>
        <tr>
            <td ></td>
            <td class="align-top">Tarikh</td>
            <td class="align-top">: <span class="ml-20px">{{ convertDateLetter($meeting->letter_date) }}</span></td>
        </tr>
    </table>

    <p class="pt-5">Senarai Edaran Sebagaimana di <strong>Lampiran</strong></p>

    <p class="mb-20px">Tuan/Puan,</p>
    <p class="mb-20px"><strong>MESYUARAT JAWANTANKUASA PENGURUSAN KUARTERS {{ $meeting->bil_no }}</strong></p>
    {{-- {{ $meeting->purpose }} DAERAH {{ $meeting->district->district_name }} --}}
    <p class="mb-20px">Dengan segala hormatnya saya merujuk perkara di atas.</p>
    <table>
        <tr>
            <td class="align-top"><span class="ml-20px mr-20px">Tarikh</span></td>
            <td class="align-top">:<span class="ml-20px">{{ convertDateLetter($meeting->date) }}</span></td>
        </tr>
        <tr>
            <td class="align-top"><span class="ml-20px mr-20px">Masa</span></td>
            <td class="align-top">:<span class="ml-20px">{{ convertTime($meeting->time) }}</span></td>
        </tr>
        <tr>
            <td class="align-top"><span class="ml-20px mr-20px">Tempat</span></td>
            <td class="align-top">:<span class="ml-20px">{{ capitalizeText($meeting->venue) }}</span></td>
        </tr>
        <tr>
            <td class="align-top"><span class="ml-20px mr-20px">Pengerusi</span></td>
            <td class="align-top">:<span class="ml-20px">{{ capitalizeText($chairmain['name']) }}</span></td>
        </tr>
        <tr>
            <td class="align-top"><span class="ml-20px">&nbsp;</span></td>
            <td class="align-top"><span class="ml-23px">{{ capitalizeText($chairmain['position']) }}</span></td>
        </tr>
    </table>

    <p class="mb-20px text-justify">3. Sehubungan dengan itu, tuan/puan dipersilakan hadir di mesyuarat tersebut.</p>
    <p class="mb-20px">Sekian, terima kasih.</p>
    <p><strong>“BERKHIDMAT UNTUK NEGARA”</strong></p>
    <p class="mb-70px">Saya yang menjalankan amanah,</p>
    <p><strong>( {{ capitalizeText($meeting->officer?->user?->name) }} )</strong></p>
    <p>{{ capitalizeText($meeting->officer?->user?->position?->position_name) }}</p>
    <p>Bahagian Khidmat Pengurusan</p>
    <p>Pejabat Setiausaha Kerajaan Johor</p>

    {{-- PAGE 2 - LAMPIRAN SURAT --}}

    <div class="page-break">
        <p class="pt-5"><strong>Lampiran</strong></p>

        <table>
            @foreach ($meeting_panel_arr as $i => $data)
                <tr >
                    <td class="align-top"><span >{{ $loop->iteration }}.</span></td>
                    <td class="align-top"><span class="ml-20px">{{capitalizeText($data['name'])}}</br></span><span class="ml-20px">{{$data['position'] ? capitalizeText($data['position']) : capitalizeText($data['name']) }} </span></td>
                </tr>
                <tr >
                    <td class="pb-3 align-top"><span class="ml-20px"></span></td>
                    <td class="pb-3 align-top"><span class="ml-20px">{{ capitalizeText($data['department']) }}</span></td>
                </tr>
            @endforeach
        </table>

    </div>

</div>
</html>
