@extends('partials.app', ['title' => 'User Details'])

@section('content')

    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">User Details</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Viewing profile of
                        <strong>{{ $user->name }}</strong></p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Edit User
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                        Back to Users
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                {{-- Card Header --}}
                <div class="px-5 py-4 sm:px-6 sm:py-5 flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-100 text-brand-600 text-xl font-semibold dark:bg-brand-900/30 dark:text-brand-400">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Details --}}
                <div class="border-t border-gray-100 dark:border-gray-800">
                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">

                        <div class="px-5 py-4 sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-white/90 sm:col-span-2 sm:mt-0">
                                {{ $user->name }}
                            </dd>
                        </div>

                        <div class="px-5 py-4 sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-white/90 sm:col-span-2 sm:mt-0">
                                {{ $user->email }}
                            </dd>
                        </div>

                        <div class="px-5 py-4 sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</dt>
                            <dd class="mt-1 sm:col-span-2 sm:mt-0">
                                @php
                                    $roleColors = [
                                        'admin'     => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        'chairman'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'assistant' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'member'    => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $user->role }}
                                </span>
                            </dd>
                        </div>

                        <div class="px-5 py-4 sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                            <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                @if ($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1.5 text-success-600 dark:text-success-400">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        Verified on {{ $user->email_verified_at->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-error-500">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                        Not Verified
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div class="px-5 py-4 sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-white/90 sm:col-span-2 sm:mt-0">
                                {{ $user->created_at->format('d M Y, h:i A') }}
                            </dd>
                        </div>

                        <div class="px-5 py-4 sm:px-6 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-white/90 sm:col-span-2 sm:mt-0">
                                {{ $user->updated_at->format('d M Y, h:i A') }}
                            </dd>
                        </div>

                    </dl>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                        onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-error-300 bg-white px-4 py-2.5 text-sm font-medium text-error-600 shadow-theme-xs hover:bg-error-50 transition-colors dark:border-error-700 dark:bg-transparent dark:text-error-400 dark:hover:bg-error-900/20">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                <path d="M10 11v6M14 11v6" />
                            </svg>
                            Delete User
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection