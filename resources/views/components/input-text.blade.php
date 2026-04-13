@props(['name', 'label' => null, 'type' => 'text', 'placeholder' => null, 'required' => false, 'disabled' => false])

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
            @if ($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            value="{{ old($name, $attributes->get('value')) }}" @if ($type === 'number') step="1" @endif
            {{ $disabled ? 'disabled' : '' }}  placeholder="{{ $placeholder }}"
            {{ $attributes->class([
                'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                    $name,
                ),
                'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                    $name,
                ),
            ]) }}>

        @if ($errors->has($name))
            <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                @include('partials.error-icon')
            </span>
        @endif
    </div>

    @error($name)
        <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
    @enderror
</div>
