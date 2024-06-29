<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import BottomTabWrap from '@/Components/Parts/BottomTabWrap.vue';
import BottomTabBtn from '@/Components/Parts/BottomTabBtn.vue';
import Success from '@/Components/Parts/Success.vue';

const props = defineProps({
    categories: { type: Object },
    success: {},
    mineId: { type: Number, require: true }
});

// 自分のid
const mineId = props.mineId;

function deleteConfirm(e)
{
    const check = confirm('削除してよろしいですか？');

    if(!check) { e.preventDefault(); }
}

const activeParentCatId = ref(null);
function accordionParentFunc(parentCatId)
{
    activeParentCatId.value = (activeParentCatId.value === parentCatId) ? null : parentCatId;
    activeCatId.value = null;
}

</script>

<template>
    <Head title="カテゴリー" />

    <AuthenticatedLayout>

        <MakeHeader :addUrl="'category.make'">カテゴリー</MakeHeader>

        <!-- successメッセージ -->
        <Success v-if="props.success" :message="props.success" />

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <ul v-if="props.categories && props.categories.length !== 0">

                        <li v-for="(parentCatData, parentCatIndex) in props.categories" :key="parentCatIndex" :class="['titleCat', {true: parentCatData.cat}]">

                            <p class="parentCatTitle" @click="accordionParentFunc(parentCatIndex)">
                                <i v-if="parentCatData.cat" :class="['fa-solid', 'fa-caret-down', { active: activeParentCatId === parentCatIndex }]"></i>
                                {{ parentCatData.name }}
                            </p>
                            <ul :class="['contentCat', { active: activeParentCatId === parentCatIndex }]">
                                <li v-for="(catData, catIndex) in parentCatData.cat" :key="catIndex" :class="['titleItem', {true: parentCatData.cat}]">
                                    <p class="catTitle" @click="accordionCatFunc(parentCatIndex, catIndex)">
                                        {{ catData.name }}
                                    </p>
                                    <div class="catContent" v-if="(mineId == catData.u_id)">
                                        <a class="ml-5" :href="route('category.edit', { 'id': catData.id, 'u_id': catData.u_id })">
                                            <i class="fa-solid fa-gear"></i>
                                        </a>
                                        <a class="ml-5" :href="route('category.delete', { 'id': catData.id, 'u_id': catData.u_id })" @click="deleteConfirm($event)">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <p v-else class="text-center">データはありません</p>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>

    li { display: flex; margin: 0 0 10px; border-radius: 0 0 10px 10px; background: #fff; }
    li img { width: 100px; }

    /* アコーディオン */
    li.titleCat, li.titleItem { display: flex; flex-direction: column; }
    li.titleCat.true, li.titleItem.true { cursor: pointer; }

    p.parentCatTitle { background: #324a6c; color: #fff; padding: 10px; border-radius: 10px; }
    p.parentCatTitle i { color: #fff; }

    ul li .titleItem:first-child { margin: 10px 0 0; }
    ul li p.catTitle { padding: 5px 10px; }

    li.titleItem { flex-direction: row; }

    li .catContent { display: flex; align-items: center; justify-content: center; }

    ul.contentCat, ul.contentItem {
        margin-left: 20px;
        max-height: 0; 
        overflow: hidden; 
        transition: max-height 0.5s ease, opacity 0.7s ease;
        opacity: 0;
    }
    ul.contentCat.active, ul.contentItem.active {
        max-height: 1000px;
        opacity: 1;
    }

    ul li i { transition: transform .5s; }
    ul li i.active { transform: rotate(-180deg); }

</style>