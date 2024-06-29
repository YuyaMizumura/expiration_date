<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import ExpirForm from '@/Layouts/ExpirForm.vue';

const props = defineProps({
    parentCats: { type: Object },
    editData:   { type: Object },
});

const form = useForm({
    c_name: props.editData ? props.editData.name : '',
    c_parent: props.editData ? props.editData.parent : '',
});

const submit = () => {
    if(props.editData) { form.post(route('category.post.edit', {id : props.editData.id})); }
    else { form.post(route('category.post.create')); }
};

</script>

<template>
    <Head :title="`カテゴリー | ${props.editData ? '編集' : '登録'}`" />

    <AuthenticatedLayout>

        <MakeHeader :type="2" :backUrl="'category'">{{ props.editData ? 'カテゴリー｜編集' : 'カテゴリー｜登録' }}</MakeHeader>

        <ExpirForm>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="cat" value="大カテゴリー" :requireNum="1" />
                    <SelectInput
                        id="cat" 
                        :array="props.parentCats"
                        v-model="form.c_parent"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.c_parent" />
                </div>

                <div class="mt-4">
                    <InputLabel for="name" value="カテゴリ名" :requireNum="1" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.c_name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.c_name" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" >
                        {{ props.editData ? '変更' : '登録' }}
                    </PrimaryButton>
                </div>
            </form>
        </ExpirForm>

    </AuthenticatedLayout>
</template>

<style scoped>

    li { display: flex; margin: 10px 0; background: #fff; }

</style>