<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import ExpirForm from '@/Layouts/ExpirForm.vue';

const props = defineProps({

});

const form = useForm({
    s_email: '',
});

const submit = () => {
    form.post(route('share.post.appli'));
};

</script>

<template>
    <Head :title="`共有｜申請`" />

    <AuthenticatedLayout>

        <MakeHeader :type="2" :backUrl="'share'">共有｜申請</MakeHeader>

        <ExpirForm>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="シェアする方のEメール" :requireNum="1" />

                    <TextInput
                        id="email"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.s_email"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.s_email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" >
                        申請
                    </PrimaryButton>
                </div>
            </form>
        </ExpirForm>

    </AuthenticatedLayout>
</template>

<style scoped>

    li { display: flex; margin: 10px 0; background: #fff; }

</style>