<script setup>
    import { ref, onMounted } from 'vue';

    const props = defineProps({
        message: {
            type: String,
            require: true,
        },
    });

    const hiddenClass = ref(false);

    onMounted(() => {
        setTimeout(() => {
            hiddenClass.value = true;
        }, 2000);
    });

</script>

<template>
    <div :class="{'hidden': hiddenClass}" class="success">
        <p>
            <i class="fa-solid fa-check"></i>
            {{ props.message }}
        </p>
    </div>
</template>

<style scoped>
    .success { position: fixed; top: 10px; width: 100%; height: 50px; display: flex; justify-content: center; transform: translateY(0); }
    .success p { width: 80%; height: 50px; line-height: 50px; background: #a6d155; color: #fff; text-align: center; font-size: 14px; font-weight: bold; border-radius: 30px; }
    .success i { color: #a6d155; padding: 5px; margin-right: 10px; background: #fff; border-radius: 50%; }
    .success.hidden { animation: slideUp 1s ease forwards; }

    @keyframes slideUp {
        from {
            transform: translateY(0);
        }
        to {
            transform: translateY(-100px);
        }
    }

</style>