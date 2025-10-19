<script setup lang="ts">
import { useSpeechRecognition } from '@vueuse/core';
import { useLlama } from '@/composables/useLlama';
import { Mic } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const isListening = ref<boolean>(false);
const ollamaResult = ref<string>('nothing yet');
const speechResult = ref<string>('nothing yet');

const recognition = useSpeechRecognition({
    interimResults: false,
    continuous: true,
    lang: 'en-CA',
});

const { talkToLlama } = useLlama();

// Watch for changes in recognition result and update speechResult
watch(recognition.result, (newResult) => {
    if (newResult) {
        speechResult.value = newResult;
        talkToLlamaAboutMacro();
    }
});

const talkToMe = () => {
    if (isListening.value) {
        recognition.stop();
    } else {
        recognition.start();
    }
    isListening.value = !isListening.value;
};

const sendTextToLlama = async () => {
    await talkToLlamaAboutMacro();
    speechResult.value = '';
};

const talkToLlamaAboutMacro = async () => {
    const prompt = `You are a spending record parser. Analyze this sentence and extract information such as vendor, items, amount, date, and category out of a transaction:

    "${speechResult.value}"

    IMPORTANT: You must respond with ONLY a valid JSON object that contains the keys (Date, Items, Vendor, Amount,Category), Amount should be a 2 decimal point number

    Rules:
    - return a 2 decimal number for amount
    - if date is not mentioned, make it a date string of today, today is ${new Date().toDateString()} always return the date in format of yyyy-mm-dd
    - infer category, for example (eat-out, health, food, gas, insurance)
    - The JSON must be valid and parseable, if the value is a string, make sure it's wrapped in double quotes
    - Example: If someone says "I spent 7.45 on coffee at Lucero", return  {"Amount": 7.45, "Items": "coffee", "Vendor": "Lucero", "Date": "2025-10-13", "Category": "eat-out"}`;

    ollamaResult.value = 'Ollama is processing';

    const format = {
        type: 'object',
        properties: {
            Date: {
                type: 'string',
            },
            Amount: {
                type: 'number',
            },
            Items: {
                type: 'string',
            },
            Vendor: {
                type: 'string',
            },
            Category: {
                type: 'string',
            },
        },
        required: ['Date', 'Items', 'Vendor', 'Amount', 'Category'],
    };

    const responseData = await talkToLlama(prompt, format);
    console.log({ prompt, responseData });

    // Try to parse the response as JSON
    try {
        const parsedSpending = JSON.parse(responseData);
        if (
            parsedSpending &&
            typeof parsedSpending === 'object' &&
            'Date' in parsedSpending &&
            'Items' in parsedSpending &&
            'Vendor' in parsedSpending &&
            'Amount' in parsedSpending &&
            'Category' in parsedSpending
        ) {
            ollamaResult.value = `
                Date: ${parsedSpending.Date},
                Items: ${parsedSpending.Items},
                Vendor: ${parsedSpending.Vendor},
                Amount: ${parsedSpending.Amount.toFixed(2)},
                Category: ${parsedSpending.Category}`;
        } else {
            ollamaResult.value = `Invalid format: ${responseData}`;
        }
    } catch (error: any) {
        ollamaResult.value = `Invalid JSON: ${responseData}`;
        console.log(error.message);
    }
};
</script>

<template>
    <div
        class="flex h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div
            class="flex h-full w-full flex-col items-center justify-center gap-10 opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <h1>Spending Recording</h1>

            <button
                @click="talkToMe"
                class="rounded-full border-2 border-slate-800 p-10"
                :class="
                    isListening
                        ? 'border-slate-200 bg-green-500'
                        : 'bg-slate-200'
                "
            >
                <Mic class="h-20 w-20" />
            </button>

            <label>
                Or put the text here:
                <input
                    type="text"
                    class="rounded-md border border-blue-300 p-2"
                    v-model="speechResult"
                />
            </label>
            <button
                class="font-md rounded-md border border-blue-800 bg-blue-200 p-2"
                @click="sendTextToLlama"
            >
                Send to Llama
            </button>

            <div>
                {{ `You said: "${speechResult}"` }}
            </div>

            <div>{{ `Llama heard: ${ollamaResult}` }}</div>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
