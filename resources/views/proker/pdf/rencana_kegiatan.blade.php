<table class="table table-bordered">
    <thead>
        <tr>
            <th>
                No
            </th>
            <th>
                Kegiatan/Program
            </th>
            <th>
                Alokasi
            </th>
            <th>
                Sumber Pembiayaan
            </th>

        </tr>

    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @foreach ($programs as $program)
            <tr>
                <td class="text-center">
                    {{ $i++ }}
                </td>
                <td>
                    {{ $program->kegiatan }}
                </td>
                <td>
                    {{ $program->alokasi }}
                </td>
                <td>
                    {{ $program->sumber }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
