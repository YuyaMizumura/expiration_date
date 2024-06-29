<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Chart, BarController, PieController, ArcElement, BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend } from 'chart.js';
Chart.register(BarController, PieController, ArcElement, BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend);

import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import BottomTabWrap from '@/Components/Parts/BottomTabWrap.vue';
import BottomTabBtn from '@/Components/Parts/BottomTabBtn.vue';

const props = defineProps({
    parentCat: { type: Object, default: [] },
    expenses:  { type: Object, default: [] },
    searchAry: { type: Object, default: [] },
    getAry:    { type: Object, default: [] },
});

// 親カテゴリー
const parentCatTitle = Object.values(props.parentCat).map(item => item.name);

// グラフを描画するためのデータと設定
const chartPieParent = ref(null);
const chartBar = ref(null);

// カラーパレット
const CHART_COLORS = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)',
    pink: 'rgb(255, 192, 203)',
    teal: 'rgb(0, 128, 128)',
    navy: 'rgb(0, 0, 128)',
    lime: 'rgb(0, 255, 0)',
};

onMounted(() => {
    if (chartPieParent.value && chartPieParent.value.getContext) {
        // 支出親円グラフ
        new Chart(chartPieParent.value.getContext('2d'), {
            type: 'pie',
            data: {
                labels: parentCatTitle,
                datasets: [{
                    label: '金額',
                    data: Object.values(props.expenses),
                    backgroundColor: Object.values(CHART_COLORS),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '支出グラフ（カテゴリ別）'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `金額: ${tooltipItem.raw.toLocaleString()}円`;
                            }
                        }
                    }
                },
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
            <Link :href="route('graph.expense', 
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

            <Link :href="route('graph.expense', 
                {
                    'year': props.getAry.searchDate.next.year,
                    'month': props.getAry.searchDate.next.month,
                })">
                <i class="fa-solid fa-caret-right fa-xl"></i>
            </Link>
        </div>

        <div class="pb-20">
            <div class="graph_fir">
                <div class="graph1">
                    <canvas ref="chartPieParent"></canvas>
                </div>
            </div>
        </div>

        <BottomTabWrap>
            <BottomTabBtn :left="{'name':'支出管理', 'url':'graph.expense'}" :right="{'name':'賞味期限', 'url':'graph.expiration'}" :activeBotton="1" />
        </BottomTabWrap>

    </AuthenticatedLayout>
</template>

<style scoped>
    .graph1 { width: 50%; padding: 20px 0 0; margin: 0 auto; }
    .graph_sec { width: 90%; padding: 20px 0 0; margin: 0 auto; }

    @media (max-width: 640px)
    {
        .graph1 { width: 80%; }
    }

</style>