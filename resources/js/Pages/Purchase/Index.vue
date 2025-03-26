<script setup>
    defineProps({
        'purchases': Array,
    })

</script>

<template>
    <div class="flex min-h-screen justify-center mt-20">
        <div class="w-3/4 overflow-x-auto max-3-xl">
            <table class="w-full text-left text-sm text-gray-800">
                <thead class="bg-gray-100 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="purchase in purchases" class="border-b last:border-0">
                        <td class="px-4 py-3">{{ purchase.CreatedAt}}</td>
                        <td class="px-4 py-3">{{ purchase.name }}</td>
                        <td class="px-4 py-3">{{ purchase.amount }}</td>
                        <td class="px-4 py-3">{{ purchase.category.name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
