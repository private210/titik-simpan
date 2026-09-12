@extends('layouts.app')

@section('title', __('messages.recurring.edit_title') . ' - ' . __('messages.app_name'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-3 mb-5">
        <a href="{{ route('recurring.index') }}" class="p-2 -m-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 active:bg-gray-200 dark:active:bg-gray-600 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ __('messages.recurring.edit_title') }}</h1>
    </div>

    <div class="fade-in-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 md:p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('recurring.update', $recurringExpense, false) }}" method="POST" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.recurring.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $recurringExpense->name) }}" required
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
                @error('name')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.recurring.category') }}</label>
                <select name="category_id" required class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
                    <option value="">{{ __('messages.recurring.select_category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $recurringExpense->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.recurring.amount') }}</label>
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-[#1BA37A] focus-within:border-[#1BA37A] transition-all bg-white dark:bg-gray-700">
                    <span class="pl-4 pr-3 py-2.5 md:py-3 text-black dark:text-white text-sm md:text-md font-medium border-r border-gray-300 dark:border-gray-600 shrink-0">Rp</span>
                    <input type="text" name="amount_display" inputmode="numeric"
                        value="{{ old('amount_display', number_format($recurringExpense->amount, 0, ',', '.')) }}" required
                        class="w-full min-w-0 border-0 px-3 py-2.5 md:py-3 bg-transparent text-gray-900 dark:text-white text-sm md:text-base focus:outline-none focus:ring-0"
                        placeholder="0" oninput="formatRupiah(this)" onfocus="this.select()">
                    <input type="hidden" name="amount" value="{{ old('amount', $recurringExpense->amount) }}">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.recurring.frequency') }}</label>
                <select name="frequency" required class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
                    <option value="daily" {{ old('frequency', $recurringExpense->frequency) === 'daily' ? 'selected' : '' }}>{{ __('messages.recurring.daily') }}</option>
                    <option value="monthly" {{ old('frequency', $recurringExpense->frequency) === 'monthly' ? 'selected' : '' }}>{{ __('messages.recurring.monthly') }}</option>
                    <option value="weekly" {{ old('frequency', $recurringExpense->frequency) === 'weekly' ? 'selected' : '' }}>{{ __('messages.recurring.weekly') }}</option>
                    <option value="yearly" {{ old('frequency', $recurringExpense->frequency) === 'yearly' ? 'selected' : '' }}>{{ __('messages.recurring.yearly') }}</option>
                </select>
                @error('frequency')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.recurring.next_due_date') }}</label>
                <input type="date" name="next_due_date" value="{{ old('next_due_date', $recurringExpense->next_due_date->format('Y-m-d')) }}" required
                    class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1BA37A] focus:border-[#1BA37A] text-sm md:text-base px-4 py-2.5 transition-all">
                @error('next_due_date')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" class="flex-1 bg-[#1BA37A] text-white py-3 rounded-2xl hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press font-medium text-sm md:text-base shadow-sm">
                    {{ __('messages.recurring.update') }}
                </button>
                <button type="button" onclick="confirmCancel('{{ route('recurring.index', [], false) }}')" class="flex-1 text-center bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 py-3 rounded-2xl hover:bg-gray-300 dark:hover:bg-gray-500 active:bg-gray-400 dark:active:bg-gray-500 transition-all btn-press font-medium text-sm md:text-base">
                    {{ __('messages.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(el) {
        var raw = el.value.replace(/[^0-9]/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        var hidden = el.parentElement.querySelector('input[type="hidden"]');
        if (hidden) hidden.value = raw || '0';
    }

    function confirmCancel(url) {
        showConfirm({
            type: 'warning',
            title: '{{ __('messages.recurring.cancel_confirm_title') }}',
            message: '{{ __('messages.recurring.cancel_confirm_message') }}',
            confirmText: '{{ __('messages.confirm_back') }}',
            onConfirm: function() { showLoading(); window.location.href = url; }
        });
    }
</script>
@endpush
