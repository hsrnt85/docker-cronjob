
<html>
    <head>
        <link href="{{ getPathDocumentCss() .'dynamic-report.css' }}" type="text/css" />
    </head>
    <body>

        @include('report.header')

        <header >
            {{-- <div class="pb-1"></div> --}}
            <table width="100%" cellspacing="0" cellpadding="3" class="bold" style="font-size: 12px;">
                <tr class="center">

                    <td> <img src="{{getJataJohor()}}" width="8%" class="img-thumbnail"  style="padding-bottom:10px;"><br>
                         Sistem Pengurusan Kuarters Kerajaan Negeri Johor</td>
                </tr>
                <tr class="center title_padding">
                    <td>{{$title}}</td>
                </tr>
            </table>
        </header>

        @if (in_array($reportId, [1]))
            @include('modules.DynamicReport.DynamicReport.table_report_kuarters')
        @endif
        @if (in_array($reportId, [4, 6]))
            @include('modules.DynamicReport.DynamicReport.table_report_pemohon')
        @endif
        @if (in_array($reportId, [5]))
            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_ditawarkan')
        @endif
        @if (in_array($reportId, [10]))
            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_skor')
        @endif
        @if (in_array($reportId, [11]))
            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_sejarah')
        @endif
        @if (in_array($reportId, [8]))
            @include('modules.DynamicReport.DynamicReport.table_report_penghuni')
        @endif
        @if (in_array($reportId, [12]))
            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_sejarah')
        @endif
        @if (in_array($reportId, [13]))
            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_bayaran')
        @endif
        @if (in_array($reportId, [14]))
            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_hilang_kelayakan')
        @endif
        @if (in_array($reportId, [15]))
            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_dijangka_bersara')
        @endif
        @if (in_array($reportId, [16]))
            @include('modules.DynamicReport.DynamicReport.table_report_pemohon_mesyuarat')
        @endif
        @if (in_array($reportId, [17]))
            @include('modules.DynamicReport.DynamicReport.table_report_penghuni_bayaran_terperinci')
        @endif

    </body>


</html>
