<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

import { Chart, PieController, ArcElement, Title, Tooltip, Legend } from 'chart.js';
Chart.register(PieController, ArcElement, Title, Tooltip, Legend);

import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import BottomTabWrap from '@/Components/Parts/BottomTabWrap.vue';
import BottomTabBtn from '@/Components/Parts/BottomTabBtn.vue';

const props = defineProps({
    parentCat:      { type: Object, default: [] },
    expirations:    { type: Object, default: [] },
    categorie:      { type: Object, default: [] },
    searchAry:      { type: Object, default: [] },
    getAry:         { type: Object, default: [] },
});

const chartPie = ref(null);

// グラフを描画するためのデータと設定
onMounted(() => {
    if (chartPie.value) {
        new Chart(chartPie.value.getContext('2d'),
        {
            type: 'pie',
            data: {
                labels: ['完了', '未完了'],
                datasets: [
                    {
                        label: '項目数',
                        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                        borderWidth: 1,
                        hoverBackgroundColor: ['rgba(75, 192, 192, 0.4)', 'rgba(255, 99, 132, 0.4)'],
                        hoverBorderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                        data: Object.values(props.expirations),
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '賞味期限'
                    }
                }
            },
        });
    }
});
</script>

<template>

    <Head title="グラフ" />

    <AuthenticatedLayout>
        <MakeHeader>グラフ</MakeHeader>

        <div class="pt-3 selectDateWrap">
            <Link :href="route('graph.expiration', 
                {
                    'year': props.getAry.searchDate.prev.year,
                    'month': props.getAry.searchDate.prev.month,
                })">
                <i class="fa-solid fa-caret-left fa-xl"></i>
            </Link>

            <h3 class="text-center">
                {{
                    (props.getAry.month) 
                        ? props.getAry.year + '年' + props.getAry.month 
                        : props.getAry.searchDate.now.year + '年' + props.getAry.searchDate.now.month 
                }}月
            </h3>

            <Link :href="route('graph.expiration', 
                {
                    'year': props.getAry.searchDate.next.year,
                    'month': props.getAry.searchDate.next.month,
                })">
                <i class="fa-solid fa-caret-right fa-xl"></i>
            </Link>
        </div>

        <div class="graph">
            <canvas ref="chartPie"></canvas>
        </div>

        <BottomTabWrap>
            <BottomTabBtn :left="{'name':'支出管理', 'url':'graph.expense'}" :right="{'name':'賞味期限', 'url':'graph.expiration'}" :activeBotton="2" />
        </BottomTabWrap>

    </AuthenticatedLayout>
</template>

<style scoped>
    .graph { width: 90%; padding: 20px 0 0; margin: 0 auto; }
</style>