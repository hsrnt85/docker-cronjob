<html>
    <head><link href="{{ getPathDocumentCss() .'complaint-report.css' }}" type="text/css" /></head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        @include('report.footer')
        <!-- Define header and footer blocks before your content -->

        <header>
            <table width="100%" cellspacing="0" cellpadding="5" >

                <tr class="title_header center title_header_padding">
                    <td colspan="11">LAPORAN ADUAN KEROSAKAN KUARTERS KERAJAAN<br/>{{ $quarters_category_name }}</td>
                </tr>

                <tr class="title center title_padding bold">
                    <td colspan="11">TARIKH ADUAN DARI {{ $carian_tarikh_aduan_dari_convert }} HINGGA {{ $carian_tarikh_aduan_hingga_convert }} </td>
                </tr>
            </table>
        </header>

        <table width="100%" cellspacing="0" cellpadding="3">
            <thead class="thead-dark">
                <tr class="grid_header center header_border border">
                    <th width="3%">Bil.</th>
                    <th width="8%">No. Aduan</th>
                    <th width="10%">Nama Pengadu</th>
                    <th width="5%">Tarikh Aduan</th>
                    <th width="5%">Tarikh Temujanji</th>
                    <th width="12%">Alamat</th>
                    <th >Butiran <br> Kerosakan</th>
                    <th >Gambar Aduan</th>
                    <th >Butiran <br>(lain-lain)</th>
                    <th >Gambar Aduan <br>(lain-lain)</th>
                    <th >Status Aduan</th>
                </tr>
            </thead>

            <tbody>
                @if($complaintListAll->count() == 0)
                    <tr class="border center"><td colspan="11">Tiada Rekod</td></tr>
                @else
                    @foreach($complaintListAll as $bil => $complaint)
                    @php
                        $unitno = $complaint->quarters?->unit_no . ", " ??'';
                        $add1 = $complaint->quarters?->address_1 ??'';
                        $add2 = $complaint->quarters?->address_2 ??'';
                        $add3 = $complaint->quarters?->address_3 ??'';
                        $full_address = $unitno.$add1.$add2.$add3;
                        $inventoryChunks = $complaint->complaint_inventory->chunk(3);
                    @endphp

                        @foreach ($inventoryChunks as $inventoryChunk)
                            @if($loop->first)
                                <tr class="grid_content border baseline pt-2">
                                    <td class="center p-2" scope="row">{{ ++$bil }}</td>
                                    <td class="center p-2">{{ $complaint->ref_no ??'' }}</td>
                                    <td class="left p-2" >{{ $complaint->user->name ??'' }}</a></td>
                                    <td class="center p-2">@if(isset($complaint?->complaint_date)) {{ convertDateSys($complaint->complaint_date) }} @else - @endif</td>
                                    <td class="center p-2">@if(isset($complaint?->appointment_date)) {{ convertDateSys($complaint->appointment_date) }} @else - @endif</td>
                                    <td class="left p-2" >{{ $full_address }}</a></td>
                                    <td class="left p-2">
                                        @if($complaint->complaint_inventory)
                                            <ol>
                                                @foreach($complaint->complaint_inventory as $damageinv)
                                                    <li>{{$damageinv->inventory->name}} - {{$damageinv->description}}</li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </td>
                                    <td class="text-center" width="140px" >
                                        @if ($complaint->complaint_inventory->count() != 0)
                                            @foreach ($inventoryChunk as $inventory)
                                                @if(file_exists(pathAttachment().$inventory->attachment?->path_document))
                                                    <img src="{{ pathAttachment().$inventory->attachment?->path_document }}" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="left p-2">
                                        @if($complaint->complaint_others ->count() != 0)
                                            <ol>
                                                @foreach($complaint->complaint_others as $others)
                                                    <li> {{$others->description}}</li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </td>
                                    <td class="justify left" width="140px">
                                        @if ($complaint->complaint_others->count() != 0)
                                            @foreach ($complaint->complaint_others as $complaint_other)
                                                @if ($complaint_other->attachments->count() != 0)
                                                    @foreach ($complaint_other->attachments as $attachment)
                                                        @if(file_exists(pathAttachment().$attachment->path_document))
                                                            <img src="{{ pathAttachment().$attachment->path_document }}" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="center p-2"> {{ $complaint->status->complaint_status }} </tr>
                            @endif
                        @endforeach

                    @endforeach
                @endif
            </tbody>
        </table>

    </body>

</html>
