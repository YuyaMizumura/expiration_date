<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import ExpirForm from '@/Layouts/ExpirForm.vue';

const props = defineProps({
    categories: { type: Array },
    editData: { type: Object, default: null },
});

const form = useForm({
    name: props.editData ? props.editData.name : '',
    cat: props.editData ? props.editData.cat : '',
    price: props.editData ? props.editData.price : '',
    ex_date: props.editData ? props.editData.ex_date_flg : 0,
    action: 'temp',
});

const submit = () => {
    if(props.editData) { form.post(route('template.post.edit', {id : props.editData.id})); }
    else { form.post(route('template.post.create')); }
};

// datePicker用
const getDayClass = (date) => {
    const weekDay = new Date(date).getDay();
    if (weekDay == 6) { return 'saturday'; } // 土曜日の場合
    if (weekDay == 0) { return 'sunday'; } // 日曜日の場合
};

</script>

<template>
    <Head :title="`項目 | ${props.editData ? '編集' : '登録'}`" />

    <AuthenticatedLayout>

        <MakeHeader :type="2" :backUrl="'template'">{{ props.editData ? '項目｜編集' : '項目｜登録' }}</MakeHeader>

        <ExpirForm>
            <form @submit.prevent="submit">

                <div>
                    <InputLabel for="cat" value="カテゴリー" :requireNum="1" />
                    <SelectInput
                        id="cat" 
                        :array="props.categories"
                        v-model="form.cat"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.cat" />
                </div>

                <div class="mt-4">
                    <InputLabel for="name" value="項目名" :requireNum="1" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="price" value="価格" :requireNum="1" />
                    <div class="flex items-end">
                        <TextInput
                            id="price"
                            type="number"
                            class="mt-1 block rounded-md mr-1"
                            v-model="form.price"
                            required
                        />円
                    </div>
                    <InputError class="mt-2" :message="form.errors.price" />
                </div>

                <div class="mt-4">
                    <InputLabel value="賞味期限（有無）" :requireNum="1" />
                    <div class="flex items-center">
                        <input type="radio" required id="ex_date_yes" value="1" v-model="form.ex_date" />
                        <label for="ex_date_yes" class="ml-2">必要</label>
                        <input type="radio" required id="ex_date_no" value="0" v-model="form.ex_date" class="ml-4" />
                        <label for="ex_date_no" class="ml-2">不要</label>
                    </div>
                    <InputError class="mt-2" :message="form.errors.ex_date" />
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