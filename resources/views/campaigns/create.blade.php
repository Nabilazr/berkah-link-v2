@extends('layouts.app')

@section('title', 'Buat Campaign Baru — Berkah-Link')

@section('content')
<div class="min-h-screen bg-stone-50 py-10 pb-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-stone-400 mb-6">
            <a href="{{ route('campaigns.index') }}" class="hover:text-emerald-700 transition-colors">Campaign</a>
            <span>›</span>
            <span class="text-stone-600">Buat Campaign Baru</span>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-br from-emerald-950 to-teal-800 px-6 py-6">
                <h1 class="text-white font-bold text-xl mb-1" style="font-family: 'Playfair Display', serif;">
                    🌿 Buat Campaign Baru
                </h1>
                <p class="text-emerald-300 text-sm">Ceritakan kisahmu dan mulai galang dana bersama komunitas.</p>
            </div>

            {{-- Form --}}
            <div class="px-6 py-6">

                {{-- Tip --}}
                <div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 mb-6 text-sm text-emerald-800">
                    <span class="text-base mt-0.5">💡</span>
                    <span>Judul yang jelas dan deskripsi yang menyentuh hati akan meningkatkan kepercayaan donatur.</span>
                </div>

                <form action="{{ route('campaigns.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Judul --}}
                    <div>
                        <label class="block text-sm font-semibold text-stone-700 mb-1.5">
                            Judul Campaign <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title"
                               value="{{ old('title') }}"
                               placeholder="Contoh: Bantu Pembangunan Masjid Desa Sukamaju"
                               maxlength="255"
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
                                  placeholder="Ceritakan latar belakang, tujuan, dan bagaimana dana akan digunakan..."
                                  class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition resize-y {{ $errors->has('description') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">{{ old('description') }}</textarea>
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
                                       value="{{ old('target_amount') }}"
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
                                    <option value="{{ $val }}" {{ old('status', 'draft') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                        <a href="{{ route('campaigns.index') }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-semibold text-stone-600 bg-stone-100 hover:bg-stone-200 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-700 hover:bg-emerald-800 text-white transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Buat Campaign
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection