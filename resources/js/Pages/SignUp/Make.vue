<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import FileInput from '@/Components/FileInput.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import MakeHeader from '@/Components/Parts/MakeHeader.vue';
import ExpirForm from '@/Layouts/ExpirForm.vue';

// Vue Datepickerに関するimport
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const props = defineProps({
    categories:     { type: Object, default: null },
    editData:       { type: Object, default: null },
    items:          { type: Object, default: null },
    searchItems:    { type: Object, default: null },
    tempImgPath:    { type: String, default: '' },
    defData:        { type: Object },
    newItemId:      {},
});

const itemsAry = props.items;
const searchItemAry = props.searchItems;

const form = useForm({
    s_date:         props.editData ? props.editData.expense.date : props.defData['date'],
    s_name:         props.editData ? props.editData.expense.name : '',
    s_cat:          props.editData ? props.editData.expense.cat : '',
    s_tax:          props.editData ? props.editData.expense.tax : props.defData['tax'],
    s_taxCheck:     props.editData ? (props.editData.expense.tax) ? false : true : false,
    s_description:  props.editData ? props.editData.expense.description : '',
    s_file:         null,
    s_filePath:     props.editData ? props.editData.expense.img : '',
    s_items:        [],

    itemAry:        props.editData ? props.editData.itemAry : [],
    expirationAry:  props.editData ? props.editData.expirationAry : [],
});

// 編集時用
if(props.editData)
{
    props.editData.items.forEach((item, index) => {
        if(item.ex_date)
        {
            form.s_items[index] = {
                id: item.i_id,
                date: item.ex_date,
                stock: item.stock,
            }
        }
        else
        {
            form.s_items[index] = {
                id: item.i_id,
                stock: item.stock,
            }
        }
    });
}

// post処理 //////////////////////////////////////////////////////////
const submit = () => {

    // 項目の重複確認
    const postCheckItems = [];
    form.s_items.forEach((secItem, secIndex) => { postCheckItems.push(secItem.id)});
    const postCheck = (new Set(postCheckItems)).size !== postCheckItems.length;

    if(postCheck === false)
    {
        if(props.editData)  { form.post(route('signup.post.edit', {id: props.editData.id})); }
        else                { form.post(route('signup.post.create')); }
    }
};

// datePicker
const getDayClass = (date) => {
    const weekDay = new Date(date).getDay();
    if (weekDay == 6) { return 'saturday'; } // 土曜日の場合
    if (weekDay == 0) { return 'sunday'; } // 日曜日の場合
};

// 項目入力セクションでのカテゴリーに属する項目のリストをフィルタリングする
const filteredItems = computed(() => {
    if (!form.s_cat) { return []; }
    return itemsAry[form.s_cat] || [];
});

// カテゴリーが変更されたときにform.s_itemsを初期化する
watch(() => form.s_cat, () => { 
    form.s_items = [];
});

function addItem()         { form.s_items.push({ id: '', stock: 1}); }
function removeItem(index) { form.s_items.splice(index, 1); }

// img //////////////////////////////////////////////////////////
const viewImg = (props.tempImgPath) ? ref(1) : ref(0);

function onFileSelected(file)
{
    form.s_file = file;

    // テンプレートを使用し、かつ新しくファイルを選択した場合
    if(props.tempImgPath)
    {
        viewImg.value = 0;
        form.s_filePath = '';
    }
}

// 合計金額計算 //////////////////////////////////////////////////////////

const totalPrice = (props.editData) ? ref(props.editData.expense.total_price) : ref(0);
watch(() => form.s_items, () => { calculateTotalPrice(); }, { deep: true }); // 項目入力
watch(() => form.s_tax, () => { calculateTotalPrice(); });  // 消費税
watch(() => form.s_taxCheck, (newValue) => { if(newValue) { form.s_tax = 0; } }); // taxがありかなしかのチェック

function calculateTotalPrice()
{
    totalPrice.value = 0;

    // 項目入力
    form.s_items.forEach((item) => {
        if(item['id'])
        {
            const itemData = searchItemAry[item['id']];
            if(itemData) { totalPrice.value += itemData.price * item['stock']; }
        }
    });

    const taxRate = form.s_tax / 100;
    totalPrice.value += (totalPrice.value * taxRate);
}

// 項目入力 //////////////////////////////////////////////////////////

watch(() => form.s_items, () => {

    form.s_items.forEach((item, index) => {
        watch(() => item.id, (newId, oldId) => {

            // 重複する項目を削除
            let tmpCheckItems = [];
            form.s_items.forEach((secItem, secIndex) => {
                if(tmpCheckItems.includes(secItem.id))  { form.s_items.splice(secIndex, 1); }
                else                                    { tmpCheckItems.push(secItem.id) }
            });

            // 賞味期限の有無
            if (newId && searchItemAry[newId] && searchItemAry[newId].ex_date)  { item.date = props.defData.date; }
            else                                                                { if('date' in item) { delete item.date; } }
        });
    });
}, { deep: true });

// 項目入力（手動）form //////////////////////////////////////////////////////////

// manuParts 
const manuPartsFlg = ref();
function openManuParts()  { manuPartsFlg.value = true; document.body.classList.add('stop'); }
function closeManuParts() { manuPartsFlg.value = false; document.body.classList.remove('stop'); }

const manuForm = useForm({
    name:     '',
    cat:      '',
    price:    '',
    ex_date:  0,
    action:   'manu',
});

// カテゴリーが変更された時
let manuCat = '';
watch(() => form.s_cat, () => { changeCat(manuForm); });

// 編集時の初期カテゴリデータ設定
if(props.editData) { changeCat(manuForm); }

// カテゴリの変更があったら
function changeCat(manuForm) {
    manuForm.cat = form.s_cat;

    // 項目入力（手動）form カテゴリvalue
    props.categories.forEach(function(cat) {
        if(cat.id == form.s_cat) { manuCat = cat.name; }
    });
}

let manuSubmit = async () => {
    try
    {
        if(!manuForm.ex_date) { manuForm.ex_date = 0; }

        // 送信・レスポンスデータ取得
        let response = await axios.post(route('template.post.manuCreate'), manuForm);

        if(response.status === 200)
        {
            let addItem   = response.data.addItem;
            let catId     = response.data.catId;

            // 追加した項目を追加
            if(!itemsAry[catId]) { itemsAry[catId] = []; }
            itemsAry[catId].push(addItem);

            // 1 追加したcalcItemを追加
            // 2 送信処理した場合
            if(addItem.ex_date)
            {
                searchItemAry[addItem.id] = {
                    'price': addItem.price, 
                    'ex_date': addItem.ex_date
                };
                form.s_items.push({
                    id: addItem.id,
                    stock: 1,
                    date: addItem.ex_date,
                });
            }
            else
            {
                searchItemAry[addItem.id] = {'price': addItem.price};
                form.s_items.push({
                    id: addItem.id,
                    stock: 1,
                });
            }

            // 初期化 ///////////////////////////////////////////////////////

            // manuFormを初期化
            manuForm.name = '';
            manuForm.price = '';
            manuForm.ex_date = '';

            // 項目入力（手動）form　閉じる
            manuPartsFlg.value = false;

            // モーダルの閉じる処理
            document.body.classList.remove('stop');
        }
        else
        {
            // console.error('フォームの投稿に失敗しました。');
        }
    }
    catch(error)
    {
        // if (error.response && error.response.status === 422) { console.error('バリデーションエラー:', error.response.data.errors); }
        // else                                                 { console.error('フォームの投稿に失敗しました:', error); }
    }
};

</script>

<template>
    <Head title="登録" />

    <AuthenticatedLayout>

        <MakeHeader :type="2" :backUrl="'dashboard'">登録</MakeHeader>

        <ExpirForm>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="date" value="登録日" :requireNum="1" />
                    <VueDatePicker
                        id="date"
                        v-model="form.s_date"
                        format="yyyy年M月d日"
                        locale="ja"
                        model-type="yyyy-MM-dd"
                        :enable-time-picker="false"
                        auto-apply
                        week-start="0"
                        :day-class="getDayClass"
                        required
                        autocomplate="date"
                    />
                    <InputError class="mt-2" :message="form.errors.s_date" />
                </div>

                <div class="mt-4">
                    <InputLabel for="cat" value="カテゴリー" :requireNum="1" />
                    <SelectInput
                        id="cat" 
                        :array="props.categories"
                        v-model="form.s_cat"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.s_cat" />
                </div>

                <div v-if="(itemsAry.length !== 0)" class="mt-4">
                    <InputLabel value="項目入力" />
                    <p v-if="!form.s_cat" class="note">※カテゴリーを選択して下さい</p>

                    <div v-if="form.s_cat" v-for="(item, index) in form.s_items" :key="index" class="mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-4/12">
                                <SelectInput
                                    :id="'itemCat'+index"
                                    :array="filteredItems"
                                    v-model="form.s_items[index]['id']"
                                    required
                                    class="mr-2 w-full"
                                />
                            </div>
                            <div v-if="item.date" class="w-4/12">
                                <VueDatePicker
                                    :id="'itemDate'+index"
                                    v-model="form.s_items[index]['date']"
                                    format="yyyy年M月d日"
                                    locale="ja"
                                    model-type="yyyy-MM-dd"
                                    :enable-time-picker="false"
                                    auto-apply
                                    week-start="0"
                                    :day-class="getDayClass"
                                    required
                                    autocomplate="date"
                                    :clearable="false"
                                />
                            </div>
                            <div class="w-3/12">
                                <div class="flex items-end">
                                    <TextInput
                                        :id="'itemStock'+index"
                                        type="number"
                                        step="1"
                                        class="block mr-1 w-full"
                                        v-model="form.s_items[index]['stock']"
                                        placeholder="個数"
                                    />
                                    <span class="text-sm">個</span>
                                </div>
                            </div>
                            <i class="removeBtn fa-solid fa-delete-left fa-xl" @click="removeItem(index)"></i>
                        </div>
                    </div>

                    <div v-if="form.s_cat" class="addBtn" @click="addItem">追加</div>
                </div>

                <div class="mt-4">
                    <InputLabel value="項目入力（手動）" />
                    <p class="note" v-if="!form.s_cat">※カテゴリーを選択して下さい</p>
                    <div class="addBtn" v-if="form.s_cat" @click="openManuParts">新規追加</div>
                </div>

                <div class="mt-4">
                    <InputLabel for="tax" value="消費税" />
                    <div class="flex items-end">
                        <div v-if="!form.s_taxCheck" class="flex items-end mr-5">
                            <TextInput
                                id="tax"
                                type="number"
                                class="block w-20 rounded-md mr-1"
                                v-model="form.s_tax"
                            />
                            <span class="text-sm">%</span>
                        </div>
                        <div>
                            <label for="taxCheck">
                                <input id="taxCheck" type="checkbox" v-model="form.s_taxCheck">
                                消費税なし
                            </label>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.s_tax" />
                </div>

                <div class="mt-5 totalPrice">
                    合計金額： {{ totalPrice }}円
                </div>

                <div class="mt-4">
                    <InputLabel for="s_file" value="写真" />

                    <div v-if="props.tempImgPath && viewImg" class="tempImg">
                        <img :src="props.tempImgPath" alt="">
                    </div>

                    <FileInput
                        type="file"
                        id="s_file"
                        class="file_input mt-1"
                        @fileSelected="onFileSelected"
                    />
                    <InputError class="mt-2" :message="form.errors.s_file" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="備考" />
                    <!-- <textarea class="mt-1 block w-full" v-model="form.s_description" id="description" rows="3"></textarea> -->
                    <TextInput
                        id="description"
                        type="text"
                        class="block w-full"
                        v-model="form.s_description"
                    />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" >
                        {{ props.editData ? '変更' : '登録' }}
                    </PrimaryButton>
                </div>
            </form>
        </ExpirForm>

        <!-- 項目入力（手動) ---------------------------------------------------------------------------------------------->

        <!-- smokeParts -->
        <div :class="{ 'smokeParts' : (manuPartsFlg === true), '': (manuPartsFlg === false) }"></div>

        <div :class="{ 'active' : (manuPartsFlg === true), 'close': (manuPartsFlg === false) }" class="showSearchWrap">
            <div class="searchTitleWrap">
                <i @click="closeManuParts" class="fa-solid fa-xmark fa-xl cursor-pointer"></i>
                <p>項目登録</p>
                <div class="dummy"></div>
            </div>
            <form @submit.prevent="manuSubmit" class="searchGroup">
                <div class="manuChild">
                    <InputLabel for="cat" value="カテゴリー" />
                    <div class="cat">
                        {{ manuCat }}
                    </div>
                    <InputError class="mt-2" :message="manuForm.errors.cat" />
                </div>
                <div class="mt-4">
                    <InputLabel for="name" value="項目名" :requireNum="1" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="manuForm.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="manuForm.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="price" value="金額" :requireNum="1" />
                    <div class="flex items-end">
                        <TextInput
                            id="price"
                            type="number"
                            class="mt-1 block rounded-md mr-1"
                            v-model="manuForm.price"
                            required
                        />円
                    </div>
                    <InputError class="mt-2" :message="manuForm.errors.price" />
                </div>

                <div class="mt-4">
                    <InputLabel for="date" value="賞味期限（必要な場合）" />
                    <VueDatePicker
                        id="date"
                        v-model="manuForm.ex_date"
                        format="yyyy年M月d日"
                        locale="ja"
                        model-type="yyyy-MM-dd"
                        :enable-time-picker="false"
                        auto-apply
                        week-start="0"
                        :day-class="getDayClass"
                        autocomplate="date"
                    />
                    <InputError class="mt-2" :message="manuForm.errors.ex_date" />
                </div>

                <div class="flex items-center justify-center mt-7">
                    <button class="signupButton">
                        登録 + 追加
                    </button>
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
    label { cursor: pointer; }

    .note { font-size: 12px; color: red; }

    .tempImg { margin-bottom: 10px; }
    .tempImg img { width: 100px; }
    li { display: flex; margin: 10px 0; background: #fff; }

    .addBtn { width: 150px; height: 35px; line-height: 35px; margin: 10px 0 0 0; background: rgb(31 41 55); color: #fff; font-size: 14px; font-weight: bold; text-align: center; border-radius: 20px; cursor: pointer; }
    .removeBtn { cursor: pointer; }

    .totalPrice { font-weight: bold; background: #324a6c; color: #fff; padding: 10px; text-align: center; border-radius: 10px; }

    /* manual item wrap */
    .dummy { width: 78px; }

    .showSearchWrap { position: fixed; z-index: 10; bottom: -500px; width: 100%; padding: 0 20px 20px; background: #fff; border: 5px solid #324a6c; border-bottom: 0; border-radius: 30px 30px 0 0; font-weight: bold; }
    .showSearchWrap.active { animation: slideUp .7s ease forwards; }
    .showSearchWrap.close { animation: slideDown .7s ease forwards; }
    @keyframes slideUp {
        from { bottom: -500px; }
        to { bottom: 0; }
    }

    @keyframes slideDown {
        from { bottom: 0; }
        to { bottom: -500px; }
    }

    .searchTitleWrap { padding: 15px 0 20px; text-align: center; display: flex; justify-content: space-between; align-items: center; }
    .fa-xmark { width: 78px; text-align: left; }
    .resetBtn { border: 1px solid #324a6c; color: #324a6c; padding: 2px 10px; font-size: 14px; border-radius: 5px; }

    .manuChild { padding-bottom: 15px; }
    .manuChild p { padding: 0 0 0 5px; font-size: 14px; }
    .manuChild .date { display: flex; justify-content: space-between; }
    .manuChild .date select { width: 49%; font-size: 14px;  }
    .manuChild .cat select { width: 100%; font-size: 14px; }

    .searchSubmitWrap { display: flex; justify-content: center;  padding: 10px 0 20px; }
    .searchSubmit { display: block; width: 60%; height: 40px; line-height: 40px; text-align: center; background: #324a6c; color: #fff; border-radius: 10px; cursor: pointer; }

    .signupButton { width: 180px; height: 40px; line-height: 40px; text-align: center; background: #324a6c; color: #fff; border-radius: 10px; cursor: pointer; }

</style>