<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    required: true,
});

const props = defineProps({
    array: {
        type: Object,
        required: true,
    },
    handleChange:{
        type:Function,
    },
    noneSelect: {
        type: Number,
        default: 1,
    },
});

const select = ref(null);

</script>

<template>
    <select 
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        ref="select">
        <option v-if="props.noneSelect" value="">選択なし</option>
        <option v-for="item in props.array" :key="item.id" :value="item.id">{{ item.name }}</option>
    </select>
</template>