<html>
    <head>
        <link href="{{ getPathDocumentCss() .'report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        @include('report.footer')
        <!-- Define header and footer blocks before your content -->
        <header>
            <table width="100%" cellspacing="0">
                <tr class="title_header center " >
                    <td >LAPORAN PEMANTAUAN BERKALA (TEKNIKAL) KUARTERS KERAJAAN</td>
                </tr>
                <tr class="center bold title_padding ">
                    <td >{{$quarters_name}}</td>
                </tr>
                <tr class="title center title_padding bold">
                    <td >TARIKH DARI {{$search_date_from}} HINGGA {{$search_date_to}} </td>
                </tr>
            </table>
        </header>
        {{-- <div class="pb-4"></div> --}}
        {{-- List report --}}

        <!-- @if ($search_status)
            <p class="col-sm-12 bold" id="tahun-pdf">Status Aduan  : <span>{{ $complaint_status }}</span></p>
        @endif -->
        <table width="100%" cellpadding="5" cellspacing="0">
            <thead>
                <tr role="row" class="grid_header border bold text-center" >
                    <th width="4%" >Bil.</th>
                    <th >No. Aduan</th>
                    <th >Nama Pengadu</th>
                    <th>Tarikh Temujanji</th>
                    <th width="10%">Lokasi Kuarters</th>
                    <th width="15%">Aduan</th>
                    <th>Penolong Jurutera</th>
                    <th width="16%">Butiran Pemantauan Aduan</th>
                    <th width="15%" >Gambar Pemantauan Aduan</th>
                    <th width="10%" >Status Pemantauan Aduan</th>
                </tr>
            </thead>

            @php  $bil = 0; $bil_page = 1; @endphp
            <tbody>
                @if ($getMonitoringList->count() == 0)
                    <tr class="info_content_border">
                        <td class="text-center" colspan="10">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach ($getMonitoringList as $monitor)

                        @php
                                $address = $monitor->quarters?->unit_no.', '.$monitor->quarters?->address_1.' '.$monitor->quarters?->address_2.' '.$monitor->quarters?->address_3;

                                $monitor_damage_remarks = (isset($monitor->appointment_remarks)) ? $monitor->appointment_remarks : '';
                                $monitor_damage_attachment = (isset($monitor->appointment_attachment))  ?   $monitor -> appointment_attachment : '';

                                $monitor_awam_remarks_1 = (isset($monitor->monitoring_remarks)) ? $monitor->monitoring_remarks : '-';
                                $monitor_awam_remarks_2 = (isset($monitor->monitoring_remarks_repeat)) ? $monitor->monitoring_remarks_repeat : '';
                                $monitor_awam_remarks_3 = (isset($monitor->monitoring_remarks_final)) ? $monitor->monitoring_remarks_final : '';

                                $monitor_awam_attachment_1 = (isset($monitor->complaint_monitoring_attachment)) ? $monitor->complaint_monitoring_attachment : '';
                        @endphp
                        <tr class="info_content_border uppercase" >
                            <th class="text-center" scope="row">{{ ++$bil }}</th>
                            <td class="text-center">{{ $monitor->ref_no ??'' }}</td>
                            <td class="align-left" >{{ $monitor->user?->name ??'' }}</td>
                            <td class="text-center">{{ ($monitor->appointment_date) ? convertDateSys($monitor->appointment_date) : '-'}}</td>
                            <td class="align-left" >{{ $address }}</td>
                            <td class="align-left">  <!-- butiran aduan -->
                              {{-- Aduan Kerosakan --}}
                                @if($search_type == 1 && !$monitor->complaint_inventory->isEmpty())
                                    <u>Aduan Inventori</u><ul style="margin: 0; padding: 0; margin-left:5">
                                        @foreach($monitor->complaint_inventory as $damageinv)
                                            <li class="mb-1">
                                                <p class="mb-1 align-left">{!! $damageinv->inventory->name.' : '.'<br>'.$damageinv->description !!}</p>
                                            </li>
                                        @endforeach
                                    </ul><br>
                                @endif

                                @if($search_type == 1 && !$monitor->complaint_others->isEmpty())
                                    <u>Aduan Lain-lain</u>
                                    <ul style="margin: 0; padding: 0; margin-left:5">
                                        <p class="mb-1 align-left">{!! upperText(ArrayToDottedStringList($monitor->complaint_others?->pluck('description')->toArray())) !!}</p>
                                    </ul>
                                @endif

                                {{-- Aduan Awam --}}
                                @if($search_type == 2)
                                    {{ $monitor->complaint_description }}
                                @endif
                            </td>
                            <td >{{ $monitor->officer_Api?->name ?? '-' }}</td>

                            <td class="align-left ms-2 uppercase">  <!-- butiran pemantauan -->
                                @if (!in_array($search_status, [0, 1, 2]))  <!-- 0:baru 1:diterima & 2:ditolak = tiada pemantauan -->
                                    @if ($search_type == 1)
                                        {{ upperText($monitor->monitor_remarks_for_damage) }}
                                    @else
                                        @if ($monitor->monitoring_remarks)
                                            @foreach ([$monitor_awam_remarks_1, $monitor_awam_remarks_2, $monitor_awam_remarks_3] as $index => $remark)
                                                @if ($remark)
                                                    <u>Pemantauan
                                                        @if ($index == 0) Pertama
                                                        @elseif ($index == 1) Kedua
                                                        @elseif ($index == 2) Ketiga
                                                        @endif
                                                    </u>
                                                    <li class="ms-2">{{ $remark }}</li><div class="pb-2"></div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endif
                            </td>

                            <td class="text-center" width="150px">   <!-- gambar pemantauan -->

                                @if(!in_array($search_status, [1,2]))   <!-- 1:diterima & 2:ditolak = tiada pemantauan -->
                                @php
                                    $hasAttachment = null;
                                    $counter1_attachment = $counter2_attachment = $counter3_attachment = null;

                                    if($search_type == 1 ) { $hasAttachment = $monitor->current_complaint_appointment?->appointment_attachment; }
                                    if($search_type == 2)  { $counter1_attachment = $monitor->complaint_monitoring->monitoring_attachment_counter_1;
                                                             $counter2_attachment = $monitor->complaint_monitoring->monitoring_attachment_counter_2;
                                                             $counter3_attachment = $monitor->complaint_monitoring->monitoring_attachment_counter_3;
                                    }
                                @endphp

                                <!-- SERVER ICT-->   <!-- gambar pemantauan -->
                                @if (!in_array($search_status, [0, 1, 2]))  <!-- 0:baru 1:diterima & 2:ditolak = tiada pemantauan -->
                                    @if ($search_type == 1 && (!$hasAttachment))
                                        @foreach ($hasAttachment as $gambar)
                                            <img src="{{ pathAttachment().$gambar->path_document }}"  class="img-thumbnail" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                        @endforeach
                                    @endif

                                    @if ($search_type == 2)
                                        @foreach([$counter1_attachment, $counter2_attachment, $counter3_attachment] as $index => $attachments)
                                            @if (!$attachments->isEmpty())
                                                <u>Pemantauan
                                                    @if ($index == 0) Pertama
                                                    @elseif ($index == 1) Kedua
                                                    @elseif ($index == 2) Ketiga
                                                    @endif
                                                </u>
                                                @foreach ($attachments as $gambar)
                                                    <img src="{{ pathAttachment().$gambar->path_document }}" class="img-thumbnail" width="100%">
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endif

                                 <!-- TESTING USING DEV RN-->
                                {{-- @if ($search_type == 1 && (!$hasAttachment->isEmpty()))
                                    @foreach ( $hasAttachment as $gambar)
                                            <img src="{{ getCdn() . $gambar->path_document }}"  class="img-thumbnail" width="100%"  @if(!$loop->first) style="padding-top:5px;" @endif>
                                    @endforeach
                                @endif --}}

                                {{-- @if ($search_type == 2)
                                    @foreach([$counter1_attachment, $counter2_attachment, $counter3_attachment] as $index => $attachments)
                                        @if (!$attachments->isEmpty())
                                            <u>Pemantauan
                                                @if ($index == 0) Pertama
                                                @elseif ($index == 1) Kedua
                                                @elseif ($index == 2) Ketiga
                                                @endif
                                            </u>
                                            @foreach ($attachments as $gambar)
                                                <img src="{{ getCdn() . $gambar->path_document }}" class="img-thumbnail" width="100%">
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif  <!-- End search_type --> --}}
                            @endif   <!-- End search_status -->
                            </td>
                            <td class="text-center" >{{ $monitor->status?->complaint_status}}</td>
                        </tr>
                    @endforeach

                @endif
            </tbody>
            {{-- end of list--}}
        </table>

    </body>

</html>
