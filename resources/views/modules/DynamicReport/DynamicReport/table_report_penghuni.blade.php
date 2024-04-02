<table id="my-table" class="table table-bordered text-nowrap" role="grid" cellspacing="0" cellpadding="4" width="100%">
    <thead class="bg-primary bg-gradient text-white">
        <tr role="row" class="info_content_border">
            <th class="text-center">Bil.</th>
            <th class="text-center">Nama </th>
            <th class="text-center">No. Kad Pengenalan</th>
            <th class="text-center">Alamat Kuarters</th>
            <th class="text-center">Jabatan /Jawatan</th>
            <th class="text-center">Perkhidmatan / <br>Taraf Jawatan</th>
            <th class="text-center">Tarikh Masuk</th>
            <th class="text-center">Tarikh Keluar</th>
            <th class="text-center">No Telefon</th>
        </tr>
    </thead>
    <tbody>
        @if (count($reportData))
            @foreach ($reportData as $tenant)
                <tr class="text-center info_content_border uppercase">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->new_ic }}</td>
                    <td>{{ $tenant->quarters->unit_no }} {{ $tenant->quarters->address_1 }} {{ $tenant->quarters->address_2 }} {{ $tenant->quarters->address_3 }}</td>
                    <td>
                        {{ $tenant->position }} {{ $tenant->position_grade_type }}
                        {{ $tenant->position_grade }} <br> {{ $tenant->organization_name }}
                    </td>
                    <td>{{ $tenant->services_type }} <br> {{ $tenant->position_type }}</td>
                    <td>{{ convertDateSys($tenant->quarters_acceptance_date) }}</td>
                    <td>{{ $tenant->leave_date ? convertDateSys($tenant->leave_date) : '' }}</td>
                    <td>{{ $tenant->phone_no_hp }}</td>
                </tr>
            @endforeach
        @else
            <tr class="text-center info_content_border">
                <td colspan="9">Tiada Rekod</td>
            </tr>
        @endif
    </tbody>
</table>
