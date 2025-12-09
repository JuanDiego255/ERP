<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="10"></td>
            </tr> <!-- Fila vacía 1 -->
            <tr>
                <td colspan="10"></td>
            </tr> <!-- Fila vacía 2 -->
            <tr>
                <td colspan="10"></td>
            </tr> <!-- Fila vacía 3 -->
            <tr>
                <td colspan="10"></td>
            </tr> <!-- Fila vacía 4 -->
            <tr>
                <td colspan="10"></td>
            </tr> <!-- Fila vacía 1 -->
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Total</th>
                <th>Cuota</th>
                <th>Deuda a pagar</th>
                <th>Última fecha de pago</th>
                <th>Fecha Interés</th>
                <th>Sumatoria</th> <!-- Columna vacía -->
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $row)
                <tr>
                    <td>{{ $row->contact_id }}</td>
                    <td>{{ htmlspecialchars($row->name ?? '', ENT_QUOTES, 'UTF-8') }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->mobile }}</td>
                    <td>{{ number_format($row->total_gen, 2) }}</td>
                    <td>{{ number_format($row->cuota, 2) }}</td>
                    <td>{{ number_format($row->total_debt, 2) }}</td>
                    <td>{{ $row->last_payment_date }}</td>
                    <td>{{ $row->last_int }}</td>
                    <td>{{ number_format($row->total_paid, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
