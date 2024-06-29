<script setup>
defineProps({
    value: {
        type: String,
    },
    requireNum: {
        type: Number,
        default: 0,
    },
});
</script>

<template>
    <label class="block font-medium text-sm text-gray-700 mb-1">
        <i v-if="(requireNum === 1)" class="require fa-solid fa-asterisk"></i>
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>

    </label>
</template>

<style scoped>
    .require { color: rgb(230 25 25); padding: 3px 4px; border-radius: 5px; }
</style>