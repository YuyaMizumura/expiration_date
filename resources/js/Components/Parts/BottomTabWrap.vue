<script setup></script>

<template>
    <div class="bottomBtn">
        <slot />
    </div>
</template>

<style scoped>

    /* common */
    .bottomBtn { position: fixed; bottom: 0; left: 0; right: 0; width: auto; margin: 0 24px; font-weight: bold; }

    /* regularBtn */
    .regularBtn { display: flex; widows: 100%; height: 50px; gap: 5px; padding: 5px; margin-bottom: 5px; background: #939fb2; border-radius: 10px; }
    .left, .right { color: #fff; background: #939fb2; width: calc(100% / 2); height: 100%; display: flex; justify-content: center; align-items: center; border-radius: 10px; cursor: pointer; }
    .left.active, .right.active { background: #324a6c; color: #fff; }

    @media (max-width: 640px)
    {
        .bottomBtn { margin: 0; }
    }

</style>