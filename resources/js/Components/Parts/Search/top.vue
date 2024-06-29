<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SelectInput from '@/Components/SelectInput.vue';

const props = defineProps({
    status: {
        type: Boolean,
    },
    closeSearch: {
        type: Function,
        required: true,
    },
    searchAry: {
        type: Object,
        default: [],
    },
    getAry: {
        type: Object,
        default: [],
    },
    url: {
        type: String,
        required: true,
    }
});

const form = useForm({
    year:  (props.getAry.year) ? props.getAry.year : props.searchAry.date.year[0].id,
    month: props.getAry.month ? props.getAry.month : props.getAry.searchDate.now.month,
    cat: props.getAry.cat ? props.getAry.cat : '',
});

const submit = () => {
    form.get(route(props.url));
};

</script>

<template>
    <div :class="{ 'active' : (status === true), 'close': (status === false) }" class="showSearchWrap">
        <div class="searchTitleWrap">
            <i @click="closeSearch" class="fa-solid fa-xmark fa-xl"></i>
            <p>絞り込み</p>
            <Link :href="route('dashboard')" class="resetBtn">リセット</Link>
        </div>
        <form @submit.prevent="submit" class="searchGroup">
            <div class="searchChild">
                <InputLabel for="year" value="期限（年月）" />
                <div class="date">
                    <SelectInput
                        id="year"
                        v-model="form.year"
                        :array="searchAry['date']['year']"
                        :noneSelect="0"
                    />
                    <SelectInput
                        v-model="form.month"
                        :array="searchAry['date']['month']"
                    />
                    <InputError class="mt-2" :message="form.errors.year" />
                    <InputError class="mt-2" :message="form.errors.month" />
                </div>
            </div>
            <div class="searchChild">
                <InputLabel for="cat" value="カテゴリー" />
                <div class="cat">
                    <SelectInput
                        id="cat"
                        v-model="form.cat"
                        :array="searchAry['category']"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.cat" />
            </div>
            <div class="searchSubmitWrap">
                <button class="searchSubmit">検索する</button>
            </div>
        </form>
    </div>
</template>

<style scoped>
    .showSearchWrap { position: fixed; z-index: 10; bottom: -320px; width: 100%; padding: 0 20px; background: #fff; border: 5px solid #324a6c; border-radius: 30px 30px 0 0; font-weight: bold; }
    .showSearchWrap.active { animation: slideUp .7s ease forwards; }
    .showSearchWrap.close { animation: slideDown .7s ease forwards; }
    @keyframes slideUp {
        from { bottom: -320px; }
        to { bottom: 0; }
    }

    @keyframes slideDown {
        from { bottom: 0; }
        to { bottom: -320px; }
    }

    .searchTitleWrap { padding: 15px 0 20px; text-align: center; display: flex; justify-content: space-between; align-items: center; }
    .fa-xmark { width: 78px; text-align: left; }
    .resetBtn { border: 1px solid #324a6c; color: #324a6c; padding: 2px 10px; font-size: 14px; border-radius: 5px; }

    .searchChild { padding-bottom: 15px; }
    .searchChild p { padding: 0 0 0 5px; font-size: 14px; }
    .searchChild .date { display: flex; justify-content: space-between; }
    .searchChild .date select { width: 49%; font-size: 14px;  }
    .searchChild .cat select { width: 100%; font-size: 14px; }

    .searchSubmitWrap { display: flex; justify-content: center;  padding: 10px 0 20px; }
    .searchSubmit { display: block; width: 60%; height: 40px; line-height: 40px; text-align: center; background: #324a6c; color: #fff; border-radius: 10px; cursor: pointer; }
</style>