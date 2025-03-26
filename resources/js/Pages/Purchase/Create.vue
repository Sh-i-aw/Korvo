<script setup>
import { useForm } from "@inertiajs/vue3";
import {computed, ref } from "vue";

const props = defineProps({
    categories: {
        type: Array,
        default: []
    }
})

const categoryInput = ref('')
const filteredCategories = computed(() => {
    return props.categories.filter(item => item.name.includes(categoryInput.value))
})

const form = useForm({
    name: "",
    amount: 0,
    category_id: null,
});

const categoryClick = (category) => {
    categoryInput.value = category.name
    form.category_id = category.id
}
</script>

<template>
    Store the record!
    <form
        @submit.prevent="form.post('/purchase')"
        method="post"
        class="flex flex-col gap-6"
    >
        <label>
            name
            <input id="name" value="name" type="text" v-model="form.name" />
        </label>

        <label>
            amount
            <input id="amount" value="amount" type="number" v-model="form.amount" />
        </label>

        <label for="category">
            Category
        </label>

        <div class="relative">
            <input id="category" name="category" type="text" v-model="categoryInput" class="border" />
            <div class="absolute top-10 border shadow rounded ">
                <div class="min-w-64 cursor-pointer hover:bg-pink-100 text-pink-950"
                    v-for="category in filteredCategories" :key="category.id" @click="categoryClick(category)">{{category.name}}</div>
            </div>
        </div>

        <button>submit</button>
    </form>
</template>

<style scoped></style>
