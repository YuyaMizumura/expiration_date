<script setup>

import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    selectedImg: {
        type: String,
        required: true,
    }
});

const emits = defineEmits(['close-modal']);

function closeModal() {
    emits('close-modal'); // close-modalイベントを発行
    document.body.classList.remove('stop');
}

</script>

<template>
    <div v-if="selectedImg" class="modal">
        <div class="modal-content">
            <i @click="closeModal" class="fa-solid fa-xmark fa-xl close"></i>
            <img :src="selectedImg" alt="画像" />
        </div>
    </div>
</template>
  
<style scoped>
.modal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
}

.modal-content {
    position: relative;
    padding: 20px;
    margin: 20px;
    background: white;
    border-radius: 10px;
}

.close {
    position: absolute;
    top: -30px;
    left: 0;
    cursor: pointer;
    background: #fff;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    border-radius: 10px 10px 0 0;
}

img {
    max-width: 100%;
    max-height: 80vh;
}
</style>
