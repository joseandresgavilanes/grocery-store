<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>{{ $filterType === 'amount' ? 'Monto total ($)' : 'Cantidad vendida' }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->date }}</td>
                <td>{{ $row->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
