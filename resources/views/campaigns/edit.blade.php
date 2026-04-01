@extends('layouts.app')

@section('title', 'Edit Campaign — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-50 py-10 pb-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-stone-400 mb-6">
            <a href="{{ route('campaigns.index') }}" class="hover:text-emerald-700 transition-colors">Campaign</a>
            <span>›</span>
            <a href="{{ route('campaigns.show', $campaign) }}" class="hover:text-emerald-700 transition-colors">
                {{ Str::limit($campaign->title, 30) }}
            </a>
            <span>›</span>
            <span class="text-stone-600">Edit</span>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-700 px-6 py-6">
                <h1 class="text-white font-bold text-xl mb-1" style="font-family: 'Playfair Display', serif;">
                    ✏️ Edit Campaign
                </h1>
                <p class="text-slate-300 text-sm">Perbarui informasi campaign kamu di bawah ini.</p>
                <div class="mt-3 inline-flex items-center gap-1.5 bg-white/10 rounded-full px-3 py-1">
                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span class="text-slate-300 text-[11px] font-mono">/campaigns/{{ $campaign->slug }}</span>
                </div>
            </div>

            <div class="px-6 py-6">

                {{-- Warning slug --}}
                <div class="flex items-start gap-2.5 bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 mb-6 text-sm text-amber-800">
                    <span class="text-base mt-0.5">⚠️</span>
                    <span>Jika kamu mengubah <strong>judul</strong>, link (slug) campaign akan otomatis diperbarui.</span>
                </div>

                {{-- ════════════════════════════════════════
                     FORM UPDATE — id="form-update"
                ════════════════════════════════════════ --}}
                <form id="form-update"
                      action="{{ route('campaigns.update', $campaign) }}"
                      method="POST"
                      class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Judul --}}
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                            Judul Campaign <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title"
                               value="{{ old('title', $campaign->title) }}"
                               placeholder="Judul campaign..." maxlength="255"
                               class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                            Deskripsi Campaign <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5"
                                  placeholder="Ceritakan tujuan campaign..."
                                  class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition resize-y {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">{{ old('description', $campaign->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Target & Status --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Target --}}
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Target Donasi (Rp) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-stone-400 text-sm font-medium">Rp</span>
                                <input type="number" name="target_amount"
                                       value="{{ old('target_amount', $campaign->target_amount) }}"
                                       placeholder="5000000" min="1"
                                       class="w-full border rounded-xl pl-10 pr-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('target_amount') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                            </div>
                            @error('target_amount')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                                Status Campaign <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                                @foreach(['draft' => 'Draft', 'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('status', $campaign->status) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </form>
                {{-- ════ END FORM UPDATE ════ --}}

                {{-- ════════════════════════════════════════
                     FORM DELETE — di LUAR form update!
                     Kosong, tombolnya ada di bawah
                ════════════════════════════════════════ --}}
                <form id="form-delete"
                      action="{{ route('campaigns.destroy', $campaign) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus campaign ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                </form>
                {{-- ════ END FORM DELETE ════ --}}

                {{-- Action Buttons — pakai atribut form="" untuk tahu submit ke form mana --}}
                <div class="flex items-center justify-between pt-5 mt-5 border-t border-stone-100">

                    {{-- Tombol Hapus → submit form-delete --}}
                    <button type="submit"
                            form="form-delete"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Campaign
                    </button>

                    <div class="flex items-center gap-3">
                        {{-- Tombol Batal --}}
                        <a href="{{ route('campaigns.show', $campaign) }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-semibold text-stone-600 bg-stone-100 hover:bg-stone-200 transition-colors">
                            Batal
                        </a>

                        {{-- Tombol Simpan → submit form-update --}}
                        <button type="submit"
                                form="form-update"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-700 hover:bg-emerald-800 text-white transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
@endsection