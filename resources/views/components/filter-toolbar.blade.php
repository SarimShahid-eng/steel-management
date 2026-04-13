@props([
    'action'        => request()->url(),
    'dateRange'     => true,
    'selectName'    => null,
    'selectId'      => null,
    'selectOptions' => collect(),
    'selectDefault' => 'All',
    'placeholder'   => 'Search...',
    'search'        => true,
])

<form method="GET" action="{{ $action }}" class="flex flex-wrap items-center gap-2">

    @if($search)
        <x-search-input placeholder="{{ $placeholder }}" />
    @endif

    @if($dateRange)
        <x-datepicker-input name="date_range" value="{{ request('date_range', '') }}" />
    @endif

    @if($selectName)
        <select name="{{ $selectName }}" id="{{ $selectId ?? $selectName }}"
            class="shadow-theme-xs rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <option value="">{{ $selectDefault }}</option>
            @foreach($selectOptions as $option)
                <option value="{{ $option['value'] }}" @selected(request($selectName) == $option['value'])>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    @endif

    <button type="submit"
        class="inline-flex h-[42px] items-center gap-2 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
        <svg class="fill-white" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
        </svg>
        Search
    </button>

    @if(request()->anyFilled(array_filter([$selectName, 'date_range', 'search'])))
        <a href="{{ $action }}"
            class="shadow-theme-xs flex h-[42px] items-center rounded-lg border border-gray-300 bg-white px-3.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
            Clear
        </a>
    @endif

</form>
