<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="plusBtn">
        <Link :href="route('signup.make')">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> 登録
        </Link>
    </div>
</template>

<style scoped>
    .plusBtn { display: flex; justify-content: end; align-items: end; width: 100%; height: 40px; margin-bottom: 5px; }
    .plusBtn a { width: 100px; height: 40px; line-height: 40px; text-align: center; border-radius: 10px; background-color: #324a6c; color: #fff; }
    .plusBtn i { color: inherit; padding-right: 5px; }
</style>