<header>
    <table width="100%" cellspacing="0">
        <thead>
            <tr class="bold">
                <td colspan="2" class="left">TARIKH : {{ date('d/m/Y') }}</td>
                <td  class="right">MUKA SURAT : <span class="pagenum"></span> /  {{$totalPages ?? ''}}</td>
            </tr>
            <tr class="bold">
                <td colspan="3" class="left" >MASA : {{ date('h:i:s A') }}</td>
            </tr>
        </thead>
    </table>
</header>