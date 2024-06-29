<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    activeBotton: {
        type: Number,
    },
    left: {
        type: Object,
        required: true,
    },
    right: {
        type: Object,
        required: true,
    },
    optionFlg: {
        type: Number,
        default: 0,
    }
});

</script>

<template>
        <div class="regularBtn">
            <Link :href="route(props.left.url)" :class="{'active': props.activeBotton === 1}" class="left">
                {{ props.left.name }}
            </Link>
            <Link :href="route(props.right.url)" :class="{'active' : props.activeBotton === 2}" class="right">
                {{ props.right.name }}
            </Link>
        </div>
</template>

<style scoped>

    /* regularBtn */
    .regularBtn { display: flex; widows: 100%; height: 50px; gap: 5px; padding: 5px; margin-bottom: 5px; background: #939fb2; border-radius: 10px; }
    .left, .right { color: #fff; background: #939fb2; width: calc(100% / 2); height: 100%; display: flex; justify-content: center; align-items: center; border-radius: 10px; cursor: pointer; }
    .left.active, .right.active { background: #324a6c; color: #fff; }

</style>