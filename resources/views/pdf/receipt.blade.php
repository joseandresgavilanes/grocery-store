<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f5f5f5; }
  </style>
</head>
<body>
  <h1>Recibo de Pedido #{{ $order->id }}</h1>
  <p>Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</p>
  <p>Total: <strong>{{ number_format($order->total, 2, ',', '.') }} €</strong></p>

  <h3>Detalles del pedido</h3>
  <table>
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unitario</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($order->items as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ number_format($item->unit_price, 2, ',', '.') }} €</td>
          <td>{{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }} €</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
