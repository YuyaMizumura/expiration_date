<script setup>
import { ref, defineEmits } from 'vue';

const emit = defineEmits(['fileSelected']);

const file = ref(null);

// データ追加処理
function onImgSelected(e)
{
    const file = e.target.files[0];
    // ファイルを親コンポーネントにエミット
    emit('fileSelected', file);
}

</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        ref="file"
        @change="onImgSelected($event)"
    />
</template>