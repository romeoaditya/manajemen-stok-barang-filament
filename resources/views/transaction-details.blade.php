<!-- resources/views/transaction-details.blade.php -->
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="font-medium text-gray-900">Transaction Info</h3>
            <p>Transaction Name: {{ $transaction->name }}</p>
            <p>Date: {{ $transaction->created_at->format('d M Y ') }}</p>
            <p>Status: 
                @if($transaction->is_paid)
                    <span class="px-2 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                        Sukses
                    </span>
                @else
                    <span class="px-2 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                        Belum Dibayar
                    </span>
                @endif
            </p>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="font-medium text-gray-900 mb-2">Items</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($details as $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $detail->item->item_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ $detail->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ number_format($detail->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                    <td class="px-6 py-4 text-right font-medium">
                        {{ number_format($details->sum(function($detail) {
                            return $detail->quantity * $detail->price;
                        }), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>