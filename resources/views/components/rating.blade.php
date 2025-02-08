<div class="flex items-center">
    <span class="text-yellow-500 text-sm">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= round($rating))
                ★
            @else
                ☆
            @endif
        @endfor
    </span>
    <span class="text-gray-600 ml-1 text-xs">({{ $reviews_count }} avis)</span>
</div>
