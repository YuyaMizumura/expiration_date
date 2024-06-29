<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import BottomTabWrap from '@/Components/Parts/BottomTabWrap.vue';

defineProps({
    templates: {
        type: Object,
    },
});

</script>

<template>
    <Head title="登録" />

    <AuthenticatedLayout>

        <MakeHeader :addUrl="'signup.make'" :backUrl="'dashboard'">登録</MakeHeader>

        <!-- 登録 コンポーネント -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <ul v-for="(item, key) in templates" v-if="templates.length !== 0" :key="key">
                        <li class="register">
                            <div class="img">
                                <img v-if="item.img" :src="item.img" :alt="item.name">
                            </div>
                            <div class="name">
                                {{ item.name }}
                            </div>
                            <div>
                                <Link :href="route('signup.make.id', { id: item.id })">
                                    <i class="fa-solid fa-share-from-square"></i>
                                </Link>
                            </div>
                        </li>
                    </ul>
                    <p v-else class="text-center">データはありません</p>
                </div>
            </div>
        </div>

        <BottomTabWrap>
            <div class="registerBtn">
                <Link :href="route('template')" class="temp">項目登録</Link>
                <Link :href="route('category')" class="cat">カテゴリ登録</Link>
            </div>
        </BottomTabWrap>

    </AuthenticatedLayout>
</template>

<style scoped>

    li { display: flex; margin: 10px 0; background: #fff; }
    li img { width: 100px; }

    /* registerBtn */
    .registerBtn { padding-bottom: 5px; display: flex; gap: 5px; }
    .registerBtn a { width: calc(100% / 2); display: inline-block; text-align: center; height: 40px; line-height: 40px; border-radius: 10px; }
    .temp { background: greenyellow; }
    .signUp { background: orange; }
    .cat { background: pink; }

</style>