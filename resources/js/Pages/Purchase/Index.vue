<script setup>
    defineProps({
        'purchases': Array,
    })

</script>

<template>
    <p class="text-red-200"> Is it gon be here?</p>
    <table >
        <thead>
            <tr>
                <th>Name</th>
                <th>Amount</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="purchase in purchases">
                <td>{{ purchase.name }}</td>
                <td>{{ purchase.amount }}</td>
                <td>{{ purchase.category }}</td>
            </tr>
        </tbody>
    </table>
</template>

<style scoped>

</style>
