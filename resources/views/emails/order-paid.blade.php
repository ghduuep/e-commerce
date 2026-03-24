<h1>Pedido Confirmado 🎉</h1>

<p>Olá, {{ $order->user->name }}!</p>

<p>Seu pedido #{{ $order->id }} foi pago com sucesso.</p>

<p><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>

<h3>Itens:</h3>
<ul>
@foreach($order->items as $item)
    <li>
        {{ $item->product->name }} 
        (x{{ $item->quantity }})
    </li>
@endforeach
</ul>

<p>Obrigado pela compra 💙</p>