@php
    /** @var \App\Models\Product $product */
    $waDirectUrl = \App\Services\WhatsAppService::productDirectUrl($product, 'beli');
    $primarySpecs = collect([$product->processor, $product->gpu])->filter()->implode(' · ');
    $secondarySpecs = collect([$product->ram, $product->storage])->filter()->implode(' / ');
@endphp
<article class="group bg-white rounded-2xl border border-slate-200/90 hover:border-blue-600/60 hover:shadow-lg hover:shadow-slate-900/5 transition-all duration-200 overflow-hidden flex flex-col justify-between h-full">
    <div>
        <!-- Clean Product Image Linking Directly to Full Detail Page -->
        <a href="{{ route('product.show', $product->slug) }}"
           class="block relative aspect-[16/10] bg-slate-100 overflow-hidden border-b border-slate-100">
            <img src="{{ $product->thumbnailUrl() }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-500 ease-out">

            <div class="absolute top-3 left-3 flex items-center gap-1.5">
                @if($product->is_best_seller_week)
                    <span class="px-2.5 py-1 rounded-md bg-blue-600 text-white text-[11px] font-semibold shadow-xs">
                        Best Seller
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-md bg-slate-950/85 text-white text-[11px] font-medium backdrop-blur-xs">
                        {{ $product->condition === 'new' ? 'Unit Baru' : 'Second Grade A+' }}
                    </span>
                @endif
            </div>
        </a>

        <!-- Harmonious, Scannable Card Body -->
        <div class="p-5 space-y-3.5">
            <!-- Meta: Brand, Condition & Stock -->
            <div class="flex items-center justify-between gap-2 text-xs text-slate-500">
                <span class="font-medium text-slate-600">
                    {{ $product->brand }} &middot; {{ $product->condition === 'new' ? 'Baru Resmi' : 'Second Mulus' }}
                </span>
                <span class="text-emerald-700 font-medium">
                    Ready {{ $product->stock }} Unit
                </span>
            </div>

            <!-- Product Title (Direct Link to Full Detail) -->
            <h3 class="text-base font-bold text-slate-950 group-hover:text-blue-600 transition leading-snug">
                <a href="{{ route('product.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Clear & Minimalist Spec Summary -->
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs text-slate-600">
                @if($primarySpecs)
                    <p class="font-medium text-slate-800 truncate" title="{{ $primarySpecs }}">
                        {{ $primarySpecs }}
                    </p>
                @elseif($product->short_description)
                    <p class="font-medium text-slate-800 line-clamp-1">
                        {{ $product->short_description }}
                    </p>
                @endif

                <p class="text-slate-500 truncate">
                    @if($secondarySpecs)
                        {{ $secondarySpecs }}
                        @if($product->warranty)
                            &middot; {{ $product->warranty }}
                        @endif
                    @else
                        {{ $product->warranty ?: 'Garansi Toko & Lulus QC 35 Titik' }}
                    @endif
                </p>
            </div>

            <!-- Price Row -->
            <div class="pt-0.5 flex items-baseline justify-between gap-2">
                <div>
                    <span class="block text-[11px] text-slate-400">Harga Terbaik</span>
                    <div class="flex items-baseline gap-2 mt-0.5">
                        <span class="text-lg font-bold text-slate-950">{{ $product->formattedPrice() }}</span>
                        @if($product->formattedOriginalPrice())
                            <span class="text-xs text-slate-400 line-through">{{ $product->formattedOriginalPrice() }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Direct Action Buttons (No Pop-Up, Direct to Full Detail Page & WhatsApp) -->
    <div class="px-5 pb-5 pt-0 grid grid-cols-2 gap-2.5">
        <a href="{{ route('product.show', $product->slug) }}"
           class="py-2.5 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-800 font-semibold text-xs border border-slate-200 transition text-center">
            Lihat Detail
        </a>
        <a href="{{ $waDirectUrl }}"
           target="_blank" rel="noopener"
           class="py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs transition text-center">
            Beli via WA
        </a>
    </div>
</article>
