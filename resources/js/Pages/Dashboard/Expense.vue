<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import BottomTabWrap from '@/Components/Parts/BottomTabWrap.vue';
import BottomTabBtn from '@/Components/Parts/BottomTabBtn.vue';
import BottomSignupBtn from '@/Components/Parts/signupBtn.vue';
import Success from '@/Components/Parts/Success.vue';
import searchParts from '@/Components/Parts/Search/top.vue';
import ImageModal from '@/Components/Parts/ImageModal.vue';

const props = defineProps({
    expenses:   { type: Object },
    searchAry:  { type: Object },
    success:    {},
    getAry:     { type: Object, default: [] },
    mineId:     { type: Number, required: true }
});

console.log(props.expenses);

const searchFlg = ref(null);
const selectedImg = ref(null);

function openSearch() { searchFlg.value = true; document.body.classList.add('stop'); }
function closeSearch() { searchFlg.value = false; document.body.classList.remove('stop'); }

function openModal(imgSrc)
{
  selectedImg.value = imgSrc;
  document.body.classList.add('stop');
}

</script>

<template>
    <Head title="支出管理" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <div class="dummy"></div>
                <h2 class="font-semibold text-xl leading-tight">支出管理</h2>
                <div>
                    <i @click="openSearch" class="fa-solid fa-magnifying-glass fa-xl"></i>
                </div>
            </div>
        </template>

        <!-- successメッセージ -->
        <Success v-if="props.success" :message="props.success" />

        <!-- 検索年月 コンポーネント -->
        <div class="pt-3 selectDateWrap">
            <Link :href="route('dashboard', 
                {
                    'year': props.getAry.searchDate.prev.year,
                    'month': props.getAry.searchDate.prev.month,
                })">
                <i class="fa-solid fa-caret-left fa-xl"></i>
            </Link>

            <h3 class="text-center">{{ (props.getAry.month) ? props.getAry.year + '年' + props.getAry.month : props.getAry.searchDate.now.year + '年' + props.getAry.searchDate.now.month }}月</h3>

            <Link :href="route('dashboard', 
                {
                    'year': props.getAry.searchDate.next.year,
                    'month': props.getAry.searchDate.next.month,
                })">
                <i class="fa-solid fa-caret-right fa-xl"></i>
            </Link>
        </div>

        <div class="pt-3 pb-28">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <table>
                        <thead>
                            <tr>
                                <th width="45"></th>
                                <th>カテゴリ</th>
                                <th>金額</th>
                                <th>写真</th>
                                <th width="90">アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="props.expenses.length !== 0" v-for="(item, key) in props.expenses" :key="key">
                                <td>
                                    <div class="date">
                                        <p>{{ item.date.day }}<span>日</span></p>
                                    </div>
                                </td>
                                <td class="cat">
                                    {{ item.cat }}
                                </td>
                                <td class="price">
                                    ¥{{ item.total_price }}
                                </td>
                                <td>
                                    <div v-if="item.img" class="img">
                                        <i class="fa-solid fa-image fa-xl" @click="openModal(item.img)"></i>
                                    </div>
                                </td>
                                <td>
                                    <a class="ml-5" :href="route('signup.make', { 'id': item.id, 'u_id': mineId })">
                                        <i class="fa-solid fa-gear fa-xl"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr v-else class="text-center">
                                <td class="noData" colspan="5">データはありません</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <BottomTabWrap>
            <BottomSignupBtn />
            <BottomTabBtn :left="{'name':'支出管理', 'url':'dashboard'}" :right="{'name':'賞味期限', 'url':'dashboard.expiration'}" :activeBotton="1" />
        </BottomTabWrap>


        <!-- smokeParts -->
        <div :class="{ 'smokeParts' : (searchFlg === true), '': (searchFlg === false) }"></div>
        <searchParts :status="searchFlg" :closeSearch="closeSearch" :searchAry="searchAry" :getAry="getAry" :url="'dashboard'" />

        <!-- ImageModal -->
        <ImageModal v-if="selectedImg" :selectedImg="selectedImg" @close-modal="selectedImg = null" />

    </AuthenticatedLayout>
</template>

<style>

    i { color: #324a6c; cursor: pointer; }

</style>

<style scoped>

    .dummy { width: 24px; }

    h3 { font-size: 20px; font-weight: bold; }

    td { margin: 0 0 10px; background: #fff; }
    td img { cursor: pointer; }

    td .month { background: inherit; margin: 10px 0 5px 10px; }
    /* TOP 賞味期限 ------------------------------------------------------*/
    td .date { width: 45px; height: 45px; line-height: 45px; text-align: center; background: #324a6c; color: #fff; font-weight: bold; font-size: 18px; border-radius: 8px; }
    td .date span { font-size: 12px; }

</style>