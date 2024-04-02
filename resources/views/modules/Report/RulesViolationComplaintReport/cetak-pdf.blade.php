<html>
    <head>
        <link href="{{ getPathDocumentCss() .'complaint-report.css' }}" type="text/css" />
    </head>
    <body>

        <!-- Define header and footer blocks before your content -->
        @include('report.header')
        @include('report.footer')
        <!-- Define header and footer blocks before your content -->
        <header>
            <table width="100%" cellspacing="0">
                <tr class="title_header center title_header_padding">
                    <td >LAPORAN ADUAN AWAM KUARTERS KERAJAAN<br/>{{ $quarters_category_name }}</td>
                </tr>
                <tr class="title_header  center title_padding bold">
                    <td >TARIKH ADUAN DARI {{ $carian_tarikh_aduan_dari}} HINGGA {{ $carian_tarikh_aduan_hingga }} </td>
                </tr>
            </table>
        </header>

        <table width="100%" cellspacing="0" cellpadding="3" >

            <thead class="thead-dark">
                <tr class="grid_header center header_border border">
                    <th width="4%">Bil.</th>
                    <td width="8%" >No. Aduan</td>
                    <td width="15%" >Nama Pengadu</td>
                    <td width="10%" >Tarikh Aduan</td>
                    <td width="20%" >Alamat Kuarters</td>
                    <td width="20%" >Butiran Aduan</td>
                    <td width="13%" >Gambar Aduan</td>
                    <td width="10%" >Status Aduan</td>
                </tr>
            </thead>
            @if ($complaintListAll -> count() == 0)
                <tr class="info_content_border center">
                    <td colspan="8">Tiada Rekod</td>
                </tr>
            @else
                <tbody>
                    @foreach($complaintListAll as $bil => $complaintList)
                        @php
                            $unitno = $complaintList -> unit_no . ", " ??'';
                            $add1 = $complaintList -> address_1 ??'';
                            $add2 = ($complaintList -> address_2) ? ", ". $complaintList -> address_2 : '';
                            $add3 = ($complaintList -> address_3) ? ", ". $complaintList -> address_3 : '';
                            $full_address = $unitno.$add1.$add2.$add3;
                        @endphp
                        <tr class="info_content_border">
                            <th class="text-center p-2" scope="row">{{ ++$bil }}</th>
                            <td class="text-center p-2" >{{ $complaintList->ref_no ??'' }}</td>
                            <td class="align-left p-2" >{{ $complaintList->name ??'' }}</td>
                            <td class="text-center p-2" >{{ convertDateSys($complaintList->complaint_date) ??'' }}</td>
                            <td class="align-left p-2" >{{ $full_address }}</td>
                            <td class="align-left p-2">{{ upperText($complaintList->complaint_description)?? '-' }}</td>
                            <td class="text-center p-2" >
                                @if ($complaintList->attachment->count() != 0)
                                    @foreach ($complaintList->attachment as $attachment)
                                        @if(file_exists(pathAttachment().$attachment->path_document))
                                            <img src="{{ pathAttachment().$attachment->path_document }}" width="100%" @if(!$loop->first) style="padding-top:5px;" @endif>
                                        @endif
                                    @endforeach
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center" >{{ $complaintList->status->complaint_status??'' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @endif
        </table>
    </body>

</html>
