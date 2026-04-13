@extends('partials.app', ['title' => 'Edit User'])

@section('content')

    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Edit User</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Update the details for
                        <strong>{{ $user->name }}</strong></p>
                </div>
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to Users
                </a>
            </div>

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf

                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Card Header --}}
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">User Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update basic details for the user.</p>
                    </div>

                    {{-- Fields --}}
                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            {{-- Name --}}
                            <div class="sm:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Name <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        placeholder="e.g. John Doe"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has('name'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has('name'),
                                        ])>
                                    @if ($errors->has('name'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('name')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Email <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        placeholder="e.g. john@example.com"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has('email'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has('email'),
                                        ])>
                                    @if ($errors->has('email'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('email')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Role <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="role"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:text-white/90 appearance-none',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800' => $errors->has('role'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has('role'),
                                        ])>
                                        <option value="" disabled>Select a role</option>
                                        @foreach (['admin' => 'Admin', 'chairman' => 'Chairman', 'assistant' => 'Assistant', 'member' => 'Member'] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9" />
                                        </svg>
                                    </span>
                                </div>
                                @error('role')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New Password (optional on edit) --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    New Password
                                    <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                                </label>
                                <div class="relative">
                                    <input type="password" name="password" placeholder="••••••••"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has('password'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has('password'),
                                        ])>
                                    @if ($errors->has('password'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            @include('partials.error-icon')
                                        </span>
                                    @endif
                                </div>
                                @error('password')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" placeholder="••••••••"
                                        @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900',
                                        ])>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Update User
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection