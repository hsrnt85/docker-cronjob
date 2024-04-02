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
            <table width="100%" cellspacing="0" cellpadding="5">
                <!-- Title Header -->
                <tr class="title_header center title_header_padding">
                    <td colspan="8">LAPORAN KEBENARAN KHAS KUARTERS KERAJAAN</td>
                </tr>
                <!-- Report Title -->
                <tr class="title_header center title_padding">
                    <td colspan="8">{{ $title }}</td>
                </tr>
            </table>
        </header>

        {{-- List report --}}
        <table width="100%" cellpadding="5" cellspacing="0" >
            <thead>
                <!-- Table Header -->
                <tr role="row" class="border bold">
                    <th class="text-center">Bil.</th>
                    <th width="15%" class="text-center">Nama</th>
                    <th width="10%" class="text-center">No. Kad Pengenalan</th>
                    <th width="20%" class="text-center">Jawatan</th>
                    <th width="10%" class="text-center">Taraf Jawatan</th>
                    <th width="15%" class="text-center">Jenis Perkhidmatan</th>
                    <th width="25%" class="text-center">Agensi</th>
                </tr>
            </thead>

            <tbody>
                @if ($users->isEmpty())
                    <tr class="info_content_border">
                        <td class="text-center" colspan="7">Tiada Rekod</td>
                    </tr>
                @else
                    @foreach ($users as $bil => $user)
                        @foreach ($user->addressOffice as $address)
                            <tr class="info_content_border">
                                <th class="text-center" scope="row">{{ strtoupper(++$bil) }}</th>
                                <td >{{ strtoupper($user->name) }}</td>
                                <td class="text-center">{{ strtoupper($user->new_ic) }}</td>
                                <td >
                                    @if($user->latest_user_info)
                                        {{ strtoupper($user->latest_user_info->position->position_name) }} - {{ strtoupper($user->latest_user_info->position_grade_code->grade_type ?? '') }}{{ strtoupper($user->latest_user_info->position_grade->grade_no) }}
                                    @else
                                        {{ strtoupper($user->position->position_name) }} - {{ strtoupper($user->position_grade_code->grade_type ?? '') }}{{ strtoupper($user->position_grade->grade_no) }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->latest_user_info)
                                        {{ strtoupper($user->latest_user_info->position_type->position_type) }}
                                    @else
                                        {{ strtoupper($user->position_type->position_type) }}
                                    @endif
                                </td>
                                <td >
                                    @if($user->latest_user_info)
                                        {{ strtoupper($user->latest_user_info->services_type->services_type) ?? '-' }}
                                    @else
                                        {{ strtoupper($user->services_type->services_type ?? '-') }}
                                    @endif
                                </td>
                                <td >
                                    @if ($address->organization)
                                        {{ strtoupper($address->organization->name) }}
                                    @else
                                        -
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
    </body>
</html>
