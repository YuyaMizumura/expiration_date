<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const smokeFlg = ref(false);

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard') || route().current('dashboard.expiration')">
                                    トップ
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                                <NavLink :href="route('signup.make')" :active="route().current('signup.make')">
                                    登録
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                                <NavLink :href="route('template')" :active="route().current('template') || route().current('template.make')">
                                    項目
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                                <NavLink :href="route('category')" :active="route().current('category') || route().current('category.make')">
                                    カテゴリー
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                                <NavLink :href="route('graph.expense')" :active="route().current('graph.expense') || route().current('graph.expiration')">
                                    グラフ
                                </NavLink>
                            </div>
                            <!-- <div class="hidden space-x-8 sm:-my-px sm:ms-6 sm:flex">
                                <NavLink :href="route('share')" :active="route().current('share') || route().current('share.make')">
                                    共有
                                </NavLink>
                            </div> -->
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> プロフィール </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            ログアウト
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard') || route().current('dashboard.expiration')">
                            トップ
                        </ResponsiveNavLink>
                    </div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('signup.make')" :active="route().current('signup.make')">
                            登録
                        </ResponsiveNavLink>
                    </div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('template')" :active="route().current('template') || route().current('template.make')">
                            項目
                        </ResponsiveNavLink>
                    </div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('category')" :active="route().current('category') || route().current('category.make')">
                            カテゴリー
                        </ResponsiveNavLink>
                    </div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('graph.expense')" :active="route().current('graph.expense') || route().current('graph.expiration')">
                            グラフ
                        </ResponsiveNavLink>
                    </div>
                    <!-- <div class="space-y-1">
                        <ResponsiveNavLink :href="route('share')" :active="route().current('share') || route().current('share.make')">
                            共有
                        </ResponsiveNavLink>
                    </div> -->

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> プロフィール </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                ログアウト
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style>

    i { font-size: 18px; }

    /* datePicker用 */
    .saturday { color: #0000ff; }
    .sunday { color: #ff0000; }

    /* 別パーツがフェードインした場合 */
    body.stop { overflow: hidden; }
    .smokeParts { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.6); z-index: 2; }

    /* search date */
    .selectDateWrap { display: flex; justify-content: center; align-items: center; gap: 40px; }

    /* table list */
    table { width: 100%; }
    table tr th { padding: 10px; font-size: 14px; text-align: left; background: #324a6c; color: #fff; }
    table tr td { padding: 10px; }

    table tr td.noData { padding: 15px 10px; font-size: 14px; }

</style>