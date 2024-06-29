<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import Success from '@/Components/Parts/Success.vue';

const props = defineProps({
    applies: {
        type: Object,
    },
    shares: {
        type: Object,
    },
    success: {},
});

function deleteConfirm(e)
{
    const check = confirm('共有申請を拒否してよろしいですか？');

    if(!check) { e.preventDefault(); }
}

function cancellConfirm(e)
{
    const check = confirm('共有を解除してよろしいですか？');

    if(!check) { e.preventDefault(); }
}

</script>

<template>
    <Head title="共有" />

    <AuthenticatedLayout>

        <MakeHeader :addUrl="'share.make'">共有</MakeHeader>

        <!-- successメッセージ -->
        <Success v-if="props.success" :message="props.success" />

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">

                    <!-- 申請 -->
                    <ul v-if="props.applies.length !== 0">
                        <li v-for="(item, key) in props.applies" :key="key">
                            {{ item['name'] }}

                            <a class="ml-5" :href="route('share.judge', { 'id': item.id, 'ans': 1 })">
                                <i class="fa-solid fa-circle-check"></i>
                            </a>
                            <a class="ml-5" :href="route('share.judge', { 'id': item.id, 'ans': 0 })" @click="deleteConfirm($event)">
                                <i class="fa-solid fa-ban"></i>
                            </a>
                        </li>
                    </ul>
                    <p v-else class="text-center">申請はありません</p>

                    <!-- 共有中 -->
                    <ul v-if="props.shares.length !== 0">
                        <li v-for="(item, key) in props.shares" :key="key">
                            {{ item['name'] }}
                            <a class="ml-5" :href="route('share.cancell', { 'id': item.id })" @click="cancellConfirm($event)">
                                <i class="fa-solid fa-person-circle-minus"></i>
                            </a>
                        </li>
                    </ul>
                    <p v-else class="text-center">共有アカウントはありません</p>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>

    li { display: flex; margin: 10px 0; background: #fff; }

</style>