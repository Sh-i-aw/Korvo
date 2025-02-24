<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    amount: 0,
    category: "",
});
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
            <input type="text" v-model="form.name" />
        </label>

        <label>
            amount
            <input type="number" v-model="form.amount" />
        </label>
        <label>
            category
            <input type="text" v-model="form.category" />
        </label>
        <button type="submit">submit</button>
    </form>
</template>

<style scoped></style>
